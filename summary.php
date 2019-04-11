<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-04-08
 * Time: 19:15
 */
session_start();

require_once ("Classes/PHPExcel.php");
require_once ("Classes/IRRHelper.php");
require_once ("vendor/autoload.php");
require_once ("Classes/Bilans.php");
require_once ("Classes/DCF.php");
require_once ("Classes/Wskaznik.php");
require_once ("insertion_functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['amortyzacja0']) && isset($_POST['amortyzacja1']) && isset($_POST['amortyzacja2'])) {
        $bilans = unserialize($_SESSION['bilans']);
        $bilansWariantBranzowy = unserialize($_SESSION['bilansWariantBranzowy']);
        $bilansWariantSredniejDynamiki = unserialize($_SESSION['bilansWariantSredniejDynamiki']);
        $wskaznik = unserialize($_SESSION['wskaznik']);

        if ($_POST['amortyzacja0'] != '0.00' && $_POST['amortyzacja0'] != null) {
            $amortyzacja[0] = $_POST['amortyzacja0'];
        } else {
            $amortyzacja[0] = $bilans->getAmortyzacja()[0];
        }
        if ($_POST['amortyzacja1'] != '0.00' && $_POST['amortyzacja1'] != null) {
            $amortyzacja[1] = $_POST['amortyzacja1'];
        } else {
            $amortyzacja[1] = $bilans->getAmortyzacja()[1];
        }
        if ($_POST['amortyzacja2'] != '0.00' && $_POST['amortyzacja2'] != null) {
            $amortyzacja[2] = $_POST['amortyzacja2'];
        } else {
            $amortyzacja[2] = $bilans->getAmortyzacja()[2];
        }

        $bilans->changeAmortyzacja($amortyzacja);
        $bilansWariantBranzowy->changeAmortyzacja($amortyzacja);
        $bilansWariantSredniejDynamiki->changeAmortyzacja($amortyzacja);

        $bilans->calculateOthersData(0);
        $bilansWariantBranzowy->calculateOthersData(1);
        $bilansWariantSredniejDynamiki->calculateOthersData(2);

        $wartoscDCF = DCF::calculateDCFvalue($bilans->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantBranzowy->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantSredniejDynamiki->getSzacunekWartosciKapitaluWlasnegoSuma());

        $_SESSION['bilans'] = serialize($bilans);
        $_SESSION['bilansWariantBranzowy'] = serialize($bilansWariantBranzowy);
        $_SESSION['bilansWariantSredniejDynamiki'] = serialize($bilansWariantSredniejDynamiki);
        $_SESSION['wartoscDCF'] = serialize($wartoscDCF);
        $_SESSION['wskaznik'] = serialize($wskaznik);

        //var_dump($_POST);
        //var_dump($bilans);
    } else {
        $file = $_FILES['file']['tmp_name'];
        $rok = (int)$_POST['rok'];
        $yearsTable = Bilans::CheckBilansYear($file, $rok);
        if ($yearsTable != 0) {
            $bilans = new Bilans();                         // TWORZENIE OBIEKTU KLASY BILANS - WARIANT ZEROWY
            $bilansWariantBranzowy = new Bilans();          // TWORZENIE OBIEKTU KLASY BILANS - WARIANT BRANŻOWY
            $bilansWariantSredniejDynamiki = new Bilans();  // TWORZENIE OBIEKTU KLASY BILANS - WARIANT ŚREDNIEJ DYNAMIKI
        }
        /* Ladowanie danych do obiektów */
        $bilans->loadDataForBilansObject($file, $yearsTable, $_POST);
        $bilans->calculateOthersData(0);

        $bilansWariantBranzowy->loadDataForBilansObject($file, $yearsTable, $_POST);
        $bilansWariantBranzowy->calculateOthersData(1);

        $bilansWariantSredniejDynamiki->loadDataForBilansObject($file, $yearsTable, $_POST);
        $bilansWariantSredniejDynamiki->calculateOthersData(2);

        $wartoscDCF = DCF::calculateDCFvalue($bilans->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantBranzowy->getSzacunekWartosciKapitaluWlasnegoSuma(),
            $bilansWariantSredniejDynamiki->getSzacunekWartosciKapitaluWlasnegoSuma());

        $yearsTable2 = Wskaznik::CreateBilansTabelYear($file, $rok);
        $wskaznik = Wskaznik::CreateWskaznik($file, $yearsTable2);
        $wskaznik->calculateOthersData();

        $_SESSION['bilans'] = serialize($bilans);
        $_SESSION['bilansWariantBranzowy'] = serialize($bilansWariantBranzowy);
        $_SESSION['bilansWariantSredniejDynamiki'] = serialize($bilansWariantSredniejDynamiki);
        $_SESSION['wartoscDCF'] = serialize($wartoscDCF);
        $_SESSION['wskaznik'] = serialize($wskaznik);
    }

    //var_dump($_POST);
    //var_dump($_FILES);
    //var_dump($_SESSION);
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
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-6">
            <div class="page-header">
                <h1 align="center">Podsumowanie zaczytanych danych</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form role="form" method="post" action="summary.php">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ROK</th>
                        <th>Amortyzacja</th>
                        <th>Nowa wartość</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row"><?php echo $bilans->getRok0() ?></th>
                        <td><?php echo number_format($bilans->getAmortyzacja()[0],2,',',' ')." zł" ?></td>
                        <td><input type="text" class="form-control" value=0.00 name="amortyzacja0"></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo $bilans->getRok1() ?></th>
                        <td><?php echo number_format($bilans->getAmortyzacja()[1],2,',',' ')." zł" ?></td>
                        <td><input type="text" class="form-control" value=0.00 name="amortyzacja1"></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo $bilans->getRok2() ?></th>
                        <td><?php echo number_format($bilans->getAmortyzacja()[2],2,',',' ')." zł" ?></td>
                        <td><input type="text" class="form-control" value=0.00 name="amortyzacja2"></td>
                    </tr>
                    </tbody>
                </table>
                <?php
                if (($bilans->getAmortyzacja()[0]) == 0 || ($bilans->getAmortyzacja()[1]) == 0 || ($bilans->getAmortyzacja()[2]) == 0) {
                    echo "<div class='alert alert-danger'>Amortyzacja w jedym lub kilku wczytanach latach wynosi 0.
                                                          Przed wygenerowaniem raportu podaj wartośc Amortyzacji dla lat w których jej wartość wynosi 0.</div>";
                } else {
                    echo "<div class='alert alert-success'>Zaczytane dane zawierają wartość Amortyzacji</div>";
                }
                ?>
                <p><button type="submit" class="btn btn-primary btn-block">Przelicz z nowymi wartościami amortyzacji</button></p>
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <dl class="row">
        <dt class="col-sm-4">Nazwa firmy:</dt>
        <dd class="col-sm-8"><?php echo $bilans->getFirma() ?></dd>
        <dt class="col-sm-4">Lata do bilansu firmy:</dt>
        <ul class="col-sm-8">
            <li><?php echo $bilans->getRok0() ?></li>
            <li><?php echo $bilans->getRok1() ?></li>
            <li><?php echo $bilans->getRok2() ?></li>
        </ul>
        <dt class="col-sm-4">Lata do analizy:</dt>
        <ul class="col-sm-8">
            <?php
            foreach($wskaznik->getYearsTabel() as $value) {
                echo "<li>$value</li>";
            }
            ?>
        </ul>
        <dt class="col-sm-4">Wariant zerowy - DCF:</dt>
        <dd class="col-sm-8"><?php echo number_format($bilans->getSzacunekWartosciKapitaluWlasnegoSuma(),2,',',' ') ?></dd>
        <dt class="col-sm-4">Wariant branżowy - DCF:</dt>
        <dd class="col-sm-8"><?php echo number_format($bilansWariantBranzowy->getSzacunekWartosciKapitaluWlasnegoSuma(),2,',',' ') ?></dd>
        <dt class="col-sm-4">Wariant średniej dynamiki - DCF:</dt>
        <dd class="col-sm-8"><?php echo number_format($bilansWariantSredniejDynamiki->getSzacunekWartosciKapitaluWlasnegoSuma(),2,',',' ') ?></dd>
        <dt class="col-sm-4">Wartość szacowana metodą DCF:</dt>
        <dd class="col-sm-8"><?php echo number_format($wartoscDCF,2,',',' ') ?></dd>
        <dt class="col-sm-4">Wartość szacowana metodą likwidacyjną:</dt>
        <dd class="col-sm-8"><?php echo number_format($bilans->getWartoscLikwidacyjna(),2,',',' ') ?></dd>
    </dl>
    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-success btn-block" href="generate.php">GENERUJ RAPORT</a>
        </div>
    </div>
</div>
</body>