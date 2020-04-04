<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2020-04-04
 * Time: 14:58
 */
class CHART_DaneBranzowe {

    /**
     * @var PHPlot
     */
    private $plot;

    /**
     * @var DaneBranzowe
     */
    private $daneBranzowe;

    /**
     * @var array
     */
    private $dataPrzychody;

    /**
     * @var array
     */
    private $dataZyskNetto;

    /**
     * @var array
     */
    private $dataDynamikaPrzychodow;

    /**
     * @var array
     */
    private $dataDynamikaZyskuNetto;

    /**
     * @var array
     */
    private $dataRentownoscPrzychodow;

    /**
     * @var array
     */
    private $dataPlynnoscGotowkowa;

    /**
     * @var array
     */
    private $dataROE;

    /**
     * @var array
     */
    private $dataROI;

    /**
     * @return PHPlot
     */
    public function getPlot() {
        return $this->plot;
    }

    /**
     * @param PHPlot $plot
     */
    public function setPlot($plot) {
        $this->plot = $plot;
    }

    /**
     * @return DaneBranzowe
     */
    public function getDaneBranzowe() {
        return $this->daneBranzowe;
    }

    /**
     * @param DaneBranzowe $daneBranzowe
     */
    public function setDaneBranzowe($daneBranzowe) {
        $this->daneBranzowe = $daneBranzowe;
    }

    /**
     * @param array
     * @return array
     */
    public function setData($wskaznikName) {
        $data = [];
        $years = $this->daneBranzowe->getWskaznikRok();
        $daneFirmy = $this->daneBranzowe->getWskaznikFirmy();
        $daneBranzy = $this->daneBranzowe->getWskaznikBranzy();
        $daneFirmyDlaWskaznika = $daneFirmy[$wskaznikName];
        $daneBranyDlaWskaznika = $daneBranzy[$wskaznikName];

        foreach($years as $value) {
            $data['firmy'][] = array(
                "$value",
                $daneFirmyDlaWskaznika[$value]);

            $data['branzy'][] = array(
                "$value",
                $daneBranyDlaWskaznika[$value]);
        }
        //var_dump($data);
        return $data;
    }

    /**
     * @return array
     */
    public function getDataPrzychody() {
        return $this->dataPrzychody;
    }

    /**
     * @param array
     */
    public function setDataPrzychody() {
        $this->dataPrzychody = $this->setData('przychody');
    }

    /**
     * @return array
     */
    public function getDataZyskNetto() {
        return $this->dataZyskNetto;
    }

    /**
     * @param array
     */
    public function setDataZyskNetto() {
        $this->dataZyskNetto = $this->setData('zysk_netto');
    }

    /**
     * @return array
     */
    public function getDataDynamikaPrzychodow() {
        return $this->dataDynamikaPrzychodow;
    }

    /**
     * @param array
     */
    public function setDataDynamikaPrzychodow() {
        $this->dataDynamikaPrzychodow = $this->setData('dynamika_przychodow');
    }

    /**
     * @return array
     */
    public function getDataDynamikaZyskuNetto() {
        return $this->dataDynamikaZyskuNetto;
    }

    /**
     * @param array
     */
    public function setDataDynamikaZyskuNetto() {
        $this->dataDynamikaZyskuNetto = $this->setData('dynamika_zysku_netto');
    }

    /**
     * @return array
     */
    public function getDataRentownoscPrzychodow() {
        return $this->dataRentownoscPrzychodow;
    }

    /**
     * @param array
     */
    public function setDataRentownoscPrzychodow() {
        $this->dataRentownoscPrzychodow = $this->setData('rentownosc_przychodow');
    }

    /**
     * @return array
     */
    public function getDataPlynnoscGotowkowa() {
        return $this->dataPlynnoscGotowkowa;
    }

    /**
     * @param array
     */
    public function setDataPlynnoscGotowkowa() {
        $this->dataPlynnoscGotowkowa = $this->setData('plynnosc_gotowkowa');
    }

    /**
     * @return array
     */
    public function getDataROE() {
        return $this->dataROE;
    }

    /**
     * @param array
     */
    public function setDataROE() {
        $this->dataROE = $this->setData('roe');
    }

    /**
     * @return array
     */
    public function getDataROI() {
        return $this->dataROI;
    }

    /**
     * @param array
     */
    public function setDataROI() {
        $this->dataROI = $this->setData('roi');
    }

    /**
     * CHART_WskCyklu constructor.
     * @param PHPlot $plot
     * @param DaneBranzowe $daneBranzowe
     */
    public function __construct(PHPlot $plot, DaneBranzowe $daneBranzowe) {
        $this->plot = $plot;
        $this->daneBranzowe = $daneBranzowe;
        $this->setDataPrzychody();
        $this->setDataZyskNetto();
        $this->setDataDynamikaPrzychodow();
        $this->setDataDynamikaZyskuNetto();
        $this->setDataRentownoscPrzychodow();
        $this->setDataPlynnoscGotowkowa();
        $this->setDataROE();
        $this->setDataROI();
    }

    public function createChartPrzychodyImg() {
        $data = $this->dataPrzychody;

        //Ustawienia
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPrintImage(False);
        $this->plot->SetLightGridColor('black');                        // ustawia kolor linii siatki i linii etykiety danych
        $this->plot->SetBackgroundColor('white');                       // ustawia kolor tła wykresu i legendy
        //$this->plot->SetFont('x_label',3);                              // ustawia rozmiar czcionki danych osi X
        //$this->plot->SetFont('y_label',3);                              // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                                // włącza rysowanie linii siatki Y

        //Wykres 1 - dla firmy
        $this->plot->SetDrawPlotAreaBackground (True);
        $this->plot->SetPlotType('stackedbars');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($data['firmy']);
        //$this->plot->SetYTitle('Dla firmy w mln.');
        $this->plot->SetMarginsPixels(30, 30);
        $this->plot->SetPlotAreaWorld();
        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->DrawGraph();

        //Wykres 2 - dla branży
        $this->plot->SetDrawPlotAreaBackground (False);
        $this->plot->SetDrawYGrid(False);                               // anuluj siatkę bo jest już narysowana
        $this->plot->SetPlotType('linepoints');
        $this->plot->SetDataValues($data['branzy']);
        //$this->plot->SetYTitle('Dla branzy w mln', 'plotright');
        $this->plot->SetPlotAreaWorld();
        $this->plot->SetYTickPos ('plotright');
        $this->plot->SetYTickLabelPos ('plotright');
        $this->plot->SetDataColors(array('black'));                     // ustaw kolory lini
        $this->plot->SetLineStyles('solid');                            // ustaw pełne linie
        $this->plot->SetLineWidths(2);                                  // ustaw grubosć linii
        $this->plot->SetDrawXGrid(False);                               // nie rysuj siatki X

        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/chart_przychody.png');
        $this->plot->DrawGraph();
        $this->plot->PrintImage();

        return "temp/chart_przychody.png";
    }

    public function createChartDaneBranzoweImg($dane, $plikName) {
        $data = $dane;

        //Ustawienia
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPrintImage(False);
        $this->plot->SetLightGridColor('black');                        // ustawia kolor linii siatki i linii etykiety danych
        $this->plot->SetBackgroundColor('white');                       // ustawia kolor tła wykresu i legendy
        //$this->plot->SetFont('x_label',3);                              // ustawia rozmiar czcionki danych osi X
        //$this->plot->SetFont('y_label',3);                              // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                                // włącza rysowanie linii siatki Y

        //Wykres 1 - dla firmy
        $this->plot->SetDrawPlotAreaBackground (True);
        $this->plot->SetPlotType('stackedbars');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($data['firmy']);
        //$this->plot->SetYTitle('Dla firmy w mln.');
        $this->plot->SetMarginsPixels(40, 40);
        $this->plot->SetPlotAreaWorld();
        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->DrawGraph();

        //Wykres 2 - dla branży
        $this->plot->SetDrawPlotAreaBackground (False);
        $this->plot->SetDrawYGrid(False);                               // anuluj siatkę bo jest już narysowana
        $this->plot->SetPlotType('linepoints');
        $this->plot->SetDataValues($data['branzy']);
        //$this->plot->SetYTitle('Dla branzy w mln', 'plotright');
        $this->plot->SetPlotAreaWorld();
        $this->plot->SetYTickPos ('plotright');
        $this->plot->SetYTickLabelPos ('plotright');
        $this->plot->SetDataColors(array('black'));                     // ustaw kolory lini
        $this->plot->SetLineStyles('solid');                            // ustaw pełne linie
        $this->plot->SetLineWidths(2);                                  // ustaw grubosć linii
        $this->plot->SetDrawXGrid(False);                               // nie rysuj siatki X

        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/'.$plikName.'.png');
        $this->plot->DrawGraph();
        $this->plot->PrintImage();

        return "temp/$plikName.png";
    }
}