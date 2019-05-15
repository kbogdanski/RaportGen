<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-05-09
 * Time: 21:32
 */

class CHART_WskROIROE {

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
    private $dataWskROIROE;

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
    public function getDataWskROIROE() {
        return $this->dataWskROIROE;
    }

    /**
     * @param array
     */
    public function setDataWskROIROE() {
        $data = [];
        $years = $this->wskaznik->getYearsTabel();
        $ROI = $this->wskaznik->getROI();
        $ROE = $this->wskaznik->getROE();
        $zyskownoscPrzychodow = $this->wskaznik->getZyskownoscPrzychodow();
        $i = 0;

        foreach($years as $value) {
            $data[] = array(
                "$value",
                round($ROI[$i],2),
                round($ROE[$i],2),
                round($zyskownoscPrzychodow[$i],2));
            $i++;
        }
        //var_dump($data);
        $this->dataWskROIROE = $data;
    }

    /**
     * CHART_WskROIROE constructor.
     * @param PHPlot $plot
     * @param Wskaznik $wskaznik
     */
    public function __construct(PHPlot $plot, Wskaznik $wskaznik) {
        $this->plot = $plot;
        $this->wskaznik = $wskaznik;
        $this->setDataWskROIROE();
    }

    public function createChartWskROIROEImg() {
        //Tablica dla kształtów punktów
        $use_shapes = array('diamond', 'rect', 'delta');
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPlotType('linepoints');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($this->dataWskROIROE);

        $this->plot->SetBackgroundColor('lavender');                    // ustawia kolor tła wykresu i legendy
        $this->plot->SetTitle('Wskaznik ROI i ROE');                    // tytuł wykresu
        $this->plot->SetFont('title',5);                                // ustawia rozmiar czcionki tytułu wykresu
        $this->plot->SetFont('x_label',3);                              // ustawia rozmiar czcionki danych osi X
        $this->plot->SetFont('y_label',3);                              // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                                // włącza rysowanie linii siatki Y
        $this->plot->SetLightGridColor('black');                        // ustawia kolor linii siatki i linii etykiety danych

        $this->plot->SetDataColors(array('orange', 'violet','gold'));   // ustaw kolory lini
        $this->plot->SetPointShapes($use_shapes);                       // ustaw uzywane kształty
        $this->plot->SetPointSizes(15);                                 // Zrób punkty większe
        $this->plot->SetLineStyles('solid');                            // ustaw pełne linie
        $this->plot->SetLineWidths(4);                                  // ustaw grubosć linii
        $this->plot->SetDrawXGrid(False);                               // nie rysuj siatki X

        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/chart_wskROIROE.png');
        $this->plot->DrawGraph();

        return "temp/chart_wskROIROE.png";
    }

}