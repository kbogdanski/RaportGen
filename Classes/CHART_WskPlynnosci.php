<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-04-28
 * Time: 20:45
 */

class CHART_WskPlynnosci {

    /**
     * @var PHPlot
     */
    private $plot;

    /**
     * @var Wskaznik
     */
    private $wskaznik;

    /**
     * @var array
     */
    private $dataWskPlynnosci;

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
     * @return Wskaznik
     */
    public function getWskaznik() {
        return $this->wskaznik;
    }

    /**
     * @param Wskaznik $wskaznik
     */
    public function setWskaznik($wskaznik) {
        $this->wskaznik = $wskaznik;
    }

    /**
     * @return array
     */
    public function getDataWskPlynnosci() {
        return $this->dataWskPlynnosci;
    }

    /**
     * @param array
     */
    public function setDataWskPlynnosci() {
        $data = [];
        $years = $this->wskaznik->getYearsTabel();
        $wskPlynnosciBiezacej = $this->wskaznik->getWskPlynnosciBiezacej();
        $wskPlynnosciSzybkiej = $this->wskaznik->getWskPlynnosciSzybkiej();
        $wskPlynnosciGotowka = $this->wskaznik->getWskPlynnosciGotowka();
        $i = 0;

        foreach($years as $value) {
            $data[] = array(
                "$value",
                round($wskPlynnosciBiezacej[$i],2),
                round($wskPlynnosciSzybkiej[$i],2),
                round($wskPlynnosciGotowka[$i],2));
            $i++;
        }
        //var_dump($data);
        $this->dataWskPlynnosci = $data;
    }

    /**
     * CHART_WskPlynnosci constructor.
     * @param PHPlot $plot
     * @param Wskaznik $wskaznik
     */
    public function __construct(PHPlot $plot, Wskaznik $wskaznik) {
        $this->plot = $plot;
        $this->wskaznik = $wskaznik;
        $this->setDataWskPlynnosci();
    }

    public function createChartWskPlynnosciImg() {
        //Tablica dla kształtów punktów
        $use_shapes = array('diamond', 'rect', 'delta');
        $shapes = array('Wskaznik plynnosci biezacej', 'Wskaznik plynnosci szybkiej', 'Wskaznik plynnosci gotowka');
        $os_x = count($this->wskaznik->getYearsTabel());
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPlotType('linepoints');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($this->dataWskPlynnosci);

        //Legenda
        $this->plot->SetLegend($shapes);
        $this->plot->SetLegendStyle('left', 'right');
        $this->plot->SetPlotAreaWorld(0,0,$os_x + 1,NULL);

        $this->plot->SetBackgroundColor('lavender');    // ustawia kolor tła wykresu i legendy
        $this->plot->SetLegendBgColor('white');         // ustawia kolor tła legendy

        $this->plot->SetTitle('Wskaznik plynnosci');    // tytuł wykresu
        $this->plot->SetFont('title',5);                // ustawia rozmiar czcionki tytułu wykresu
        $this->plot->SetFont('x_label',3);              // ustawia rozmiar czcionki danych osi X
        $this->plot->SetFont('y_label',3);              // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                // włącza rysowanie linii siatki Y
        $this->plot->SetLightGridColor('black');        // ustawia kolor linii siatki i linii etykiety danych

        $this->plot->SetDataColors(array('orange', 'violet','gold'));   // ustaw kolory lini
        $this->plot->SetPointShapes($use_shapes);                       // ustaw uzywane kształty
        $this->plot->SetPointSizes(15);                                 // Zrób punkty większe
        $this->plot->SetLineStyles('solid');                            // ustaw pełne linie
        $this->plot->SetLineWidths(4);                                  // ustaw grubosć linii
        $this->plot->SetDrawXGrid(False);                               // nie rysuj siatki X

        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/chart_wskPlynnosci.png');
        $this->plot->DrawGraph();

        return "temp/chart_wskPlynnosci.png";
    }

}