<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-04-11
 * Time: 20:27
 */
session_start();

require_once ("Classes/PHPExcel.php");
require_once ("Classes/IRRHelper.php");
require_once ("Classes/phplot.php");
require_once ("vendor/autoload.php");
require_once ("Classes/Bilans.php");
require_once ("Classes/DCF.php");
require_once ("Classes/Wskaznik.php");
require_once ("Classes/CHART_Aktywa.php");
require_once ("Classes/CHART_WskPlynnosci.php");
require_once ("Classes/CHART_WskCyklu.php");
require_once ("Classes/CHART_WskROIROE.php");
require_once ("Classes/CHART_WskZadluzenia.php");
require_once ("insertion_functions.php");

$bilans = unserialize($_SESSION['bilans']);
$bilansWariantBranzowy = unserialize($_SESSION['bilansWariantBranzowy']);
$bilansWariantSredniejDynamiki = unserialize($_SESSION['bilansWariantSredniejDynamiki']);
$wartoscDCF = unserialize($_SESSION['wartoscDCF']);
$wskaznik = unserialize($_SESSION['wskaznik']);

/* Ładuje plik z szablonem raportu */
$ilosc = $wskaznik->getIloscLatDoAnalizy();
if ($ilosc == 5) { $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('szablon.docx'); }
if ($ilosc == 4) { $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('szablon4.docx'); }
if ($ilosc == 3) { $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('szablon3.docx'); }

insertNazwaFirmy($templateWord, $bilans);                                   // Wstawiam nazwę firmy
insertYears($templateWord, $bilans, $wskaznik);                             // Wstawiam lata do raportu
insertWartoscLikwidacyjna($templateWord, $bilans);                          // Wstawiam wartosc likwidacyjna
insertWartoscDCF($templateWord, $wartoscDCF);                               // Wstawiam wartosc szacowana metodą DCF
insertZalozeniaDoWycenyDCF($templateWord, $bilans);                         // Wstawiam założenia do wyceny DCF
insertBilans($templateWord, $bilans);                                       // Wstawiam bilans firmy
insertBilansOtherData($templateWord, $bilans);                              // Wstawiam pozostałe dane bilansu firmy
insertStopyWzrostu($templateWord, $bilans);                                 // Wstawianie stopy wzrostu zmiennych
insertDaneDlaWariantu($templateWord, $bilans, 0);                           // Wstawiam dane do raportu - WARIANT ZEROWY
insertDaneDlaWariantu($templateWord, $bilansWariantBranzowy, 1);            // Wstawiam dane do raportu - WARIANT BRANŻOWY
insertDaneDlaWariantu($templateWord, $bilansWariantSredniejDynamiki, 2);    // Wstawiam dane do raportu - WARIANT ŚREDNIEJ DYNAMIKI
insertWybraneDaneFirmy($templateWord, $wskaznik);                           // Wstawiam wybrane dane finansowe
insertWskazniki($templateWord, $wskaznik);                                  // Wstawiam analize wskaźnikową

/* Wstawiam słowa */
insertPorownaniePrzychodowZeSprzedazy($templateWord, $wskaznik);            // WYBRANE DANE FIRMY - (niższym/wyższym/takim samym) Wstawiam informacje o różnicy w przychodach ze sprzedaży
insertOkreslenieDynamikiPrzychodow($templateWord, $wskaznik);               // WYBRANE DANE FIRMY - (dodatnią/ujemną) Wstawiam informacje o dynamice przychodów w roku bazowym

/* Zapisanie raportu */
$firma = $bilans->getFirma();
$data = date('Y-m-d');
$fileName = $firma.' '.$data;


$plot = new PHPlot(800, 400);
$plot1 = new PHPlot(1100, 400);
$plot2 = new PHPlot(1100, 400);
$plot3 = new PHPlot(1100, 400);
$plot4 = new PHPlot(1100, 400);
$chartAktywa = new CHART_Aktywa($plot, $wskaznik);
$path_aktywa = $chartAktywa->createChartAktywaImg();
insertChartAktywa($templateWord, $path_aktywa);

$chartWskPlynnosci = new CHART_WskPlynnosci($plot1, $wskaznik);
$path_wskPlynnosci = $chartWskPlynnosci->createChartWskPlynnosciImg();
insertChartWskPlynnosci($templateWord, $path_wskPlynnosci);

$chartWskCyklu = new CHART_WskCyklu($plot2, $wskaznik);
$path_wskCyklu = $chartWskCyklu->createChartWskCykluImg();
insertChartWskCyklu($templateWord, $path_wskCyklu);

$chartWskROIROE = new CHART_WskROIROE($plot3, $wskaznik);
$path_wskROIROE = $chartWskROIROE->createChartWskROIROEImg();
insertChartWskROIROE($templateWord, $path_wskROIROE);

$chartWskZadluzenia = new CHART_WskZadluzenia($plot4, $wskaznik);
$path_wskZadluzenia = $chartWskZadluzenia->createChartWskZadluzeniaImg();
insertChartWskZadluzenia($templateWord, $path_wskZadluzenia);


//$templateWord->setImg('IMGD#1',array('src' => 'image.jpg','swh'=>'250'));

$templateWord->saveAs("$fileName.docx");


?>
<!DOCTYPE>
<html lang="PL" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>RaportGen</title>
    <link rel="stylesheet" href="" type="text/css"/> <!-- plik CSS-->
    <link href="css/bootstrap.min.css" rel="stylesheet"> <!-- plik bootstrap-->
    <script src="js/jquery-1.12.4.min.js"></script> <!-- plik jquery-->
    <script src="js/bootstrap.min.js"></script> <!-- plik js/boorstrap-->
</head>
<body>
<div class="container">
    <div class="col-sm-12">
        <br><br><br><br>
        <div align="center" class="alert alert-success">Raport <?php echo '<b>'.$fileName.'.docx</b>' ?> został wygenerowany</div>
        <a  class="btn btn-primary btn-block" href="index.php">STRONA GŁÓWNA</a>
    </div>
</body>

<?php
session_destroy();
?>
