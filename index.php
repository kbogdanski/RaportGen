<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2018-10-03
 * Time: 20:00
 */

//require_once ("Classes/PHPExcel.php");
//require_once ("Classes/IRRHelper.php");
//require_once ("vendor/autoload.php");
//require_once ("Classes/Bilans.php");
//require_once ("Classes/DCF.php");
//require_once ("Classes/Wskaznik.php");
//require_once ("insertion_functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //var_dump($_POST);
    //var_dump($_FILES['file']);

    /* Ładuje plik z szablonem raportu */
    $templateWord = new \PhpOffice\PhpWord\TemplateProcessor('szablon.docx');

    $yearsTable = Bilans::CheckBilansYear($_FILES['file']['tmp_name'], $_POST['rok']);
    //var_dump($yearsTable);

    if ($yearsTable != 0) {
        $bilans = new Bilans();                         // TWORZENIE OBIEKTU KLASY BILANS - WARIANT ZEROWY
        $bilansWariantBranzowy = new Bilans();          // TWORZENIE OBIEKTU KLASY BILANS - WARIANT BRANŻOWY
        $bilansWariantSredniejDynamiki = new Bilans();  // TWORZENIE OBIEKTU KLASY BILANS - WARIANT ŚREDNIEJ DYNAMIKI

        /* Ladowanie danych do obiektów */
        $bilans->loadDataForBilansObject($_FILES['file']['tmp_name'], $yearsTable, $_POST, 0);
        $bilansWariantBranzowy->loadDataForBilansObject($_FILES['file']['tmp_name'], $yearsTable, $_POST, 1);
        $bilansWariantSredniejDynamiki->loadDataForBilansObject($_FILES['file']['tmp_name'], $yearsTable, $_POST, 2);
        $wartoscDCF = DCF::calculateDCFvalue($bilans->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantBranzowy->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantSredniejDynamiki->getSzacunekWartosciKapitaluWlasnegoSuma());

        //var_dump($bilans);
        //var_dump($bilansWariantBranzowy);
        //var_dump($bilansWariantSredniejDynamiki);

        $yearsTable2 = Wskaznik::CreateBilansTabelYear($_FILES['file']['tmp_name'], $_POST['rok']);
        $wskaznik = Wskaznik::CreateWskaznik($_FILES['file']['tmp_name'], $yearsTable2);
        //var_dump($yearsTable2);
        //var_dump($wskaznik);

        insertNazwaFirmy($templateWord, $bilans);                                   // Wstawiam nazwę firmy
        insertYears($templateWord, $bilans, $yearsTable2);                          // Wstawiam lata do raportu
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
        $templateWord->saveAs("$fileName.docx");
        //header("Content-Disposition: attachment; filename=raport.docx; ");
        //echo file_get_contents('raport.docx');



    } else {
        echo "Wczytany plik nie zawiera bilansów za wybrane lata ";
        var_dump($_POST);
    }
}

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
    <script src="js/skrypty.js"></script> <!-- plik ze skryptami js-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <br><br>
            <form role="form" method="post" action="summary.php" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row" style="text-align: center">
                        <button type="submit" class="btn btn-success">Wyślij</button>
                        <a id="button_stale_przepl_pienieznych" class="btn btn-primary">Stałe przepływów pienieżnych</a>
                        <a id="button_wartosci_korekty" class="btn btn-primary">Wartości korekty</a>
                        <a id="button_stopy_wzrostu" class="btn btn-primary">Stopy wzrostu</a>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label-sm">Nazwa firmy: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="firma">
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label-sm">PKD firmy: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pkd">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label-sm">Dane za lata: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="rok">
                                        <option value="2022">2022 - 2020</option>
                                        <option value="2021">2021 - 2019</option>
                                        <option value="2020">2020 - 2018</option>
                                        <option value="2019">2019 - 2017</option>
                                        <option value="2018">2018 - 2016</option>
                                        <option value="2017">2017 - 2015</option>
                                        <option value="2016">2016 - 2014</option>
                                        <option value="2015">2015 - 2013</option>
                                        <option value="2014">2014 - 2012</option>
                                        <option value="2013">2013 - 2011</option>
                                        <option value="2012">2012 - 2010</option>
                                        <option value="2011">2011 - 2009</option>
                                        <option value="2010">2010 - 2008</option>
                                        <option value="2019">2019 - 2017</option>
                                        <option value="1902">1902 - 1900</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label-sm">Wczytaj plik: </label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="file"/>
                                </div>
                            </div>
                            <hr>
                            <div class="hidden" id="stale_przeplywow_pienieznych">
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Dywidendy jako odsetek przepływów pieniężnych
                                        netto</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=100 name="dywidendy">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Średnie oprocentowanie zadłużenia
                                        długoterminowego</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=12 name="srOprZadlDl">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Stopa podatku dochodowego</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=19 name="stopaPodDoch">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Wolna od ryzyka stopa dyskontowa</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=6 name="stopaDyskontowa">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Premia rynkowa z tytułu ryzyka</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=7 name="premiaRynkowaRyzyka">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Współczynnik beta</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=1.2 name="wspBeta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Premia z tytułu wielkości</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=4 name="premiaWielkosci">
                                    </div>
                                    <span>%</span>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-8 col-form-label-sm">Premia z tytułu ryzyka specyficznego</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" value=2 name="premiaRyzykaSpec">
                                    </div>
                                    <span>%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 hidden" id="wartosci_korekty">
                            <p style="float: right"><b>Wartość korekty</b></p><br>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Aktywa trwałe</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty0">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Zapasy</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=80 name="wartoscKorekty1">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Należności</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=90 name="wartoscKorekty2">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Środki pieniężne</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty3">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Kapitał własny</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty4">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Zobowiązania długoterminowe</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty5">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Zobowiązania krótkoterminowe</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty6">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Suma bilansowa</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty7">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Przychody</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty8">
                                </div>
                                <span>%</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-8 col-form-label-sm">Zysk netto</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" value=100 name="wartoscKorekty9">
                                </div>
                                <span>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden" id="stopy_wzrostu">
                        <p style="float: right"><b>Wariant zerowy - Wariant branżowy - Wariant średniej dynamiki</b></p>
                        <br>
                        <hr>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-00">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-10">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-20">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu sprzedaży</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-01">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-11">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-21">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu kosztów
                                operacyjnych</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0 name="oczekiwanaStopaWzrostu-02">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.7 name="oczekiwanaStopaWzrostu-12">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=1.8 name="oczekiwanaStopaWzrostu-22">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu kosztów ogólnych</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0 name="oczekiwanaStopaWzrostu-03">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.7 name="oczekiwanaStopaWzrostu-13">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=1.8 name="oczekiwanaStopaWzrostu-23">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu aktywów bieżących</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-04">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-14">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-24">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu pasywów bieżących</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-05">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-15">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-25">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu zadłużenia
                                długoterminowego</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-06">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-16">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-26">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu amortyzacji</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-07">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-17">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-27">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu inwestycji
                                odtworzeniowych</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-08">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-18">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-28">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu wolnych przepływów po
                                okresie szczegółowej prognozy</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-09">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-19">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-29">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label-sm">Oczekiwana stopa wzrostu kapitałów
                                własnych</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=0.5 name="oczekiwanaStopaWzrostu-010">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=3.4 name="oczekiwanaStopaWzrostu-110">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" value=2.2 name="oczekiwanaStopaWzrostu-210">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br><br><br><br>
            <!-- <a href="word.php">Generuj plik Word</a> -->
            <br>
            <!-- <a href="szablon.php">Generuj raport z szablonu</a> -->
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
</body>