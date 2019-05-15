<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-05-09
 * Time: 21:58
 */

class CHART_WskZadluzenia {

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
    private $dataWskZadluzenia;

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
    public function getDataWskZadluzenia() {
        return $this->dataWskZadluzenia;
    }

    /**
     * @param array
     */
    public function setDataWskZadluzenia() {
        $data = [];
        $years = $this->wskaznik->getYearsTabel();
        $pokrycieAktywow = $this->wskaznik->getPokrycieAktywow();
        $zadluzenieOgolne = $this->wskaznik->getZadluzenieOgolne();
        $i = 0;

        foreach($years as $value) {
            $data[] = array(
                "$value",
                round($pokrycieAktywow[$i],2),
                round($zadluzenieOgolne[$i],2));
            $i++;
        }
        //var_dump($data);
        $this->dataWskZadluzenia = $data;
    }

    /**
     * CHART_WskZadluzenia constructor.
     * @param PHPlot $plot
     * @param Wskaznik $wskaznik
     */
    public function __construct(PHPlot $plot, Wskaznik $wskaznik) {
        $this->plot = $plot;
        $this->wskaznik = $wskaznik;
        $this->setDataWskZadluzenia();
    }

    public function createChartWskZadluzeniaImg() {
        //Tablica dla kształtów punktów
        $use_shapes = array('diamond', 'rect');
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPlotType('linepoints');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($this->dataWskZadluzenia);

        $this->plot->SetBackgroundColor('lavender');                                // ustawia kolor tła wykresu i legendy
        $this->plot->SetTitle('Wskaznik zadluzenia ogolnego i pokrycia aktywow');   // tytuł wykresu
        $this->plot->SetFont('title',5);                                            // ustawia rozmiar czcionki tytułu wykresu
        $this->plot->SetFont('x_label',3);                                          // ustawia rozmiar czcionki danych osi X
        $this->plot->SetFont('y_label',3);                                          // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                                            // włącza rysowanie linii siatki Y
        $this->plot->SetLightGridColor('black');                                    // ustawia kolor linii siatki i linii etykiety danych

        $this->plot->SetDataColors(array('orange', 'violet'));                      // ustaw kolory lini
        $this->plot->SetPointShapes($use_shapes);                                   // ustaw uzywane kształty
        $this->plot->SetPointSizes(15);                                             // zrób punkty większe
        $this->plot->SetLineStyles('solid');                                        // ustaw pełne linie
        $this->plot->SetLineWidths(4);                                              // ustaw grubosć linii
        $this->plot->SetDrawXGrid(False);                                           // nie rysuj siatki X

        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/chart_wskZadluzenia.png');
        $this->plot->DrawGraph();

        return "temp/chart_wskZadluzenia.png";
    }

}