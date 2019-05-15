<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-04-24
 * Time: 20:22
 */

class CHART_Aktywa {

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
    private $data;

    /**
     * @return mixed
     */
    public function getPlot() {
        return $this->plot;
    }

    /**
     * @param mixed $plot
     */
    public function setPlot($plot) {
        $this->plot = $plot;
    }

    /**
     * @return mixed
     */
    public function getWskaznik() {
        return $this->wskaznik;
    }

    /**
     * @param mixed $wskaznik
     */
    public function setWskaznik($wskaznik) {
        $this->wskaznik = $wskaznik;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed
     */
    public function setData() {
        $data = [];
        $years = $this->wskaznik->getYearsTabel();
        $aktywaTrwaleProcent = $this->wskaznik->getAktywaTrwaleProcent();
        $aktywaObrotoweProcent = $this->wskaznik->getAktywaObrotoweProcent();
        $i = 0;

        foreach($years as $value) {
            $data[] = array(
                "$value",
                (int)round($aktywaTrwaleProcent[$i],0),
                (int)round($aktywaObrotoweProcent[$i],0));
            $i++;
        }

        $this->data = $data;
        //var_dump($data);
    }



    /**
     * CHART_Aktywa constructor.
     * @param $plot
     * @param $wskaznik
     */
    public function __construct(PHPlot $plot, Wskaznik $wskaznik) {
        $this->plot = $plot;
        $this->wskaznik = $wskaznik;
        $this->setData();
    }

    public function createChartAktywaImg() {
        //var_dump($this->data);
        $os_x = count($this->wskaznik->getYearsTabel());
        $this->plot->SetImageBorderType('plain');
        $this->plot->SetPlotType('stackedbars');
        $this->plot->SetDataType('text-data');
        $this->plot->SetDataValues($this->data);

        //Legenda
        $this->plot->SetLegend(array('Aktywa trwale', 'Aktywa obrotowe'));
        $this->plot->SetLegendStyle('left', 'right');
        $this->plot->SetPlotAreaWorld(0,0,$os_x + 1,NULL);
        $this->plot->SetLegendBgColor('white');                   // ustawia kolor tła legendy

        $this->plot->SetBackgroundColor('gray');                  // ustawia kolor tła wykresu i legendy
        $this->plot->SetDataColors(array('SlateBlue', 'orange')); // ustawia kolor zestawów danych (słupków)
        $this->plot->SetShading(20);                              // ustawia rozmiar cienia dla wykresu 3D (nadaje trójwymiarowy wygląd)
        $this->plot->SetFont('x_label',5);                        // ustawia rozmiar czcionki danych osi X
        $this->plot->SetFont('y_label',5);                        // ustawia rozmiar czcionki danych osi Y
        $this->plot->SetDrawYGrid(true);                          // włącza rysowanie linii siatki Y
        $this->plot->SetLightGridColor('black');                  // ustawia kolor linii siatki i linii etykiety danych

        $this->plot->SetXTickLabelPos('none');
        $this->plot->SetXTickPos('none');
        $this->plot->SetIsInline(true);
        $this->plot->SetOutputFile('temp/chart_aktywa.png');
        $this->plot->DrawGraph();
        return "temp/chart_aktywa.png";
    }




}