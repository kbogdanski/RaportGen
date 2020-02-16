<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2018-11-20
 * Time: 20:32
 */
class Bilans {

    // Static REPOSITORY methods
    static public function CheckBilansYear($file, $rok) {
        $rok0 = $rok;
        $rok1 = $rok - 1;
        $rok2 = $rok - 2;
        $status0 = 0;
        $status1 = 0;
        $status2 = 0;
        $toReturn = [];
        $COLUMN = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E",
            "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J",
            "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O",
            "16" => "P", "17" => "Q", "18" => "R", "19" => "S", "20" => "T",
            "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y",
            "26" => "Z",);
        $col = 3;
        $excel = PHPExcel_IOFactory::load($file);
        $excel->setActiveSheetIndex(0);
        if ($excel != false) {
            $value = $excel->getActiveSheet()->getCell("B7")->getValue();
            if ($value == $rok0) {
                $status0 = 1;
                $toReturn["B"] = $value;
            }
            if ($value == $rok1) {
                $status1 = 1;
                $toReturn["B"] = $value;
            }
            if ($value == $rok2) {
                $status2 = 1;
                $toReturn["B"] = $value;
            }
            while ($value != null && $col <= 26) {
                $value = $excel->getActiveSheet()->getCell("$COLUMN[$col]7")->getValue();
                if ($value == $rok0) {
                    $status0 = 1;
                    $toReturn["$COLUMN[$col]"] = $value;
                }
                if ($value == $rok1) {
                    $status1 = 1;
                    $toReturn["$COLUMN[$col]"] = $value;
                }
                if ($value == $rok2) {
                    $status2 = 1;
                    $toReturn["$COLUMN[$col]"] = $value;
                }
                $col++;
            }
        }
        if ($status0 == 1 && $status1 == 1 && $status2 == 1) {
            return $toReturn;
        } else {
            return 0;
        }
    }

    // Static REPOSITORY methods
    // W sumie już nie potrzebna. Pobiera do tablicy wszystkie lata jakie są w zaczytanym pliku i ją zwraca
    static public function GetBilansYear($file) {
        $toReturn = [];
        $COLUMN = array("1" => "A", "2" => "B", "3" => "C", "4" => "D", "5" => "E",
            "6" => "F", "7" => "G", "8" => "H", "9" => "I", "10" => "J",
            "11" => "K", "12" => "L", "13" => "M", "14" => "N", "15" => "O",
            "16" => "P", "17" => "Q", "18" => "R", "19" => "S", "20" => "T",
            "21" => "U", "22" => "V", "23" => "W", "24" => "X", "25" => "Y",
            "26" => "Z",);
        $col = 3;
        $excel = PHPExcel_IOFactory::load($file);
        $excel->setActiveSheetIndex(0);
        if ($excel != false) {
            $value = $excel->getActiveSheet()->getCell("B7")->getValue();
            $toReturn[] = $value;
            while ($value != null && $col <= 26) {
                $value = $excel->getActiveSheet()->getCell("$COLUMN[$col]7")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                }
                $col++;
            }
        }
        return $toReturn;
    }


    //ATTRIBUTES
    private $firma;                                 // Nazwa firmy
    private $KRS;                                   // Numer KRS firmy (pobierany z zaczytanego excela - komórka A3)
    private $wartoscLikwidacyjna;                   // LICZBA wyliczana z algorytmu
    private $rok0;                                  // Rok bazowy           z bilansu firmy
    private $rok1;                                  // Rok bazowy minus 1   z bilansu firmy
    private $rok2;                                  // Rok bazowy minus 2   z bilansu firmy
    private $bilansTablica;                         // TABLICA[3-elementowa] Element to TABLICA[129-elementowa]

    private $przychodyZeSprzedazy;                  // TABLICA[3-elemenowa] Przychody ze sprzedaży
    private $kosztyDzialanosciOperacyjnej;          // TABLICA[3-elemenowa] Koszty działalności operacyjnej
    private $wynagrodzenia;                         // TABLICA[3-elemenowa] Wynagrodzenia
    private $zyskStrataZeSprzedazy;                 // TABLICA[3-elemenowa] Zysk/strata ze sprzedaży
    private $pozostalePrzychodyOperacyjne;          // TABLICA[3-elemenowa] Pozostałe przychody operacyjne
    private $pozostaleKosztyOperacyjne;             // TABLICA[3-elemenowa] Pozostałe koszty operacyjne
    private $zyskStrataZDzialanosciOperacyjnej;     // TABLICA[3-elemenowa] Zysk/strata z działalności operacyjnej
    private $przychodyFinansowe;                    // TABLICA[3-elemenowa] Przychody finansowe
    private $kosztyFinansowe;                       // TABLICA[3-elemenowa] Koszty finansowe
    private $zyskStrataZDzialalnosciGospodarczej;   // TABLICA[3-elemenowa] Zysk/strata z działalności gospodarczej
    private $wynikZdarzenNadzwyczajnych;            // TABLICA[3-elemenowa] Wynik zdarzeń nadzwyczajnych
    private $zyskBrutto;                            // TABLICA[3-elemenowa] Zysk brutto
    private $podatekDochodowy;                      // TABLICA[3-elemenowa] Podatek dochodowy
    private $zyskNetto;                             // TABLICA[3-elemenowa] Zysk netto
    private $amortyzacja;                           // TABLICA[3-elemenowa] Amortyzacja

    private $umorzenieSrTrwalych;                   // LICZBA = 0           Umorzenie środków trwałych w roku bazowym
    private $sprzedazPierwszyRokPrognozy;           // ZMIENNA LICZONA      Sprzedaż w pierwszym roku prognozy
    private $kosztyOperacyjnePierwszyRokPrognozy;   // ZMIENNA LICZONA      Koszty operacyjne w pierwszym roku prognozy (minus amortyzacja)

    /* Z formularza */
    private $dywidendy;                             // PROCENT - $_POST['dywidendy']           Dywidendy jako odsetek przepływów pieniężnych netto
    private $srOprZadlDl;                           // PROCENT - $_POST['srOprZadlDl']         Średnie oprocentowanie zadłużenia długoterminowego
    private $stopaPodDoch;                          // PROCENT - $_POST['stopaPodDoch']        Stopa podatku dochodowego
    private $stopaDyskontowa;                       // PROCENT - $_POST['stopaDyskontowa']     Wolna od ryzyka stopa dyskontowa
    private $premiaRynkowaRyzyka;                   // PROCENT - $_POST['premiaRynkowaRyzyka'] Premia rynkowa z tytułu ryzyka
    private $wspBeta;                               // Liczba  - $_POST['wspBeta']             Współczynnik beta
    private $premiaWielkosci;                       // PROCENT - $_POST['premiaWielkosci']     Premia z tytułu wielkości
    private $premiaRyzykaSpec;                      // PROCENT - $_POST['premiaRyzykaSpec']    Premia z tytułu ryzyka specyficznego
    private $wartoscKorekty;                        // PROCENT - $_POST['wartoscKorekty0-9']   Procenty korygujące 10 pozycji z bilansu

    /* Stopa wzrostu w 3 wariantach */
    private $oczekiwanaStopaWzrostu;                // PROCENT - TABLICA[3-elementowa] Element to TABLICA[11-elementowa]

    private $bilansPrognozaTable;                   // TABLICA[11-elementowa] Element to TABLICA[8-elementowa] wyliczana według algorytmów
    private $rachunekKosztowTable;                  // TABLICA[10-elementowa] Element to TABLICA[7-elementowa] wyliczana według algorytmów

    /* Obliczenie przepływów pieniężnych dla właścicieli */
    private $przyrostKapitaluPracujacego;           // TABLICA[6-elementowa] Element wyliczany według algorytmu - Przyrost kapitału pracującego (-)
    private $przyrostZadluzeniaDl;                  // TABLICA[6-elementowa] Element wyliczany według algorytmu - Przyrost zadłużenia dł. (+)
    private $przeplywyPieniezPrzynWlas;             // TABLICA[6-elementowa] Element wyliczany według algorytmu - Przepływy pienięż. przyn. właś.

    /* Zmiana stanu kapitału własnego */
    private $bilansZamkniecia;                      // TABLICA[6-elementowa] Element wyliczany według algorytmu - Bilans zamknięcia

    /* Obliczenie przepływów pieniężnych przynależnych wszystkim stronom finansującym */
    private $odsetkiNetto;                          // TABLICA[6-elementowa] Element wyliczany według algorytmu - Odsetki netto (+)
    private $przepPienPrzynStFinan;                 // TABLICA[6-elementowa] Element wyliczany według algorytmu - Przep. pien. przyn. st. finan.

    /* Wyznaczenie kosztu kapitału własnego */
    private $razemKosztKapitaluWlasnego;            // PROCENT wyliczany z algorytmu - Razem koszt kapitału własnego

    /* DANE WYLICZANE WSTAWIANE DO RAPORTU */
    /* Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom */
    private $przeplywyPieniezPrzynWlasTerminalValue;        // LICZBA wyliczana z algorytmu - Zdyskontowane przepływy wg stopy Terminal Value
    private $szacunekWartosciKapitaluWlasnego;              // TABLICA[6-elementowa] Element wyliczany według algotymu - Szacunek wartości kapitału własnego
    private $szacunekWartosciKapitaluWlasnegoSuma;          // LICZBA SUMA $szacunekWartosciKapitaluWlasnego i $szacunekWartosciKapitaluWlasnegoTerminalValue
    private $szacunekWartosciKapitaluWlasnegoTerminalValue; // LICZBA wyliczana z algorytmu - Szacunek wartości kapitału własnego Terminal Value

    /* Wyznaczenie WACC */
    private $rynkowaWartoscDluguProcent;                    // PROCENT wyliczany według algorytmu - Rynkowa wartość długu Procent
    private $rynkowaWartoscDluguKoszt;                      // PROCENT wyliczany według algorytmu - Rynkowa wartość długu Koszt
    private $rynkowaWartoscDluguWACC;                       // PROCENT wyliczany według algorytmu - Rynkowa wartość długu WACC
    private $rynkowaWartoscKapitaluWlasnegoProcent;         // PROCENT wyliczany według algorytmu - Rynkowa wartość kapitału własnego Procent
    private $rynkowaWartoscKapitaluWlasnegoWACC;            // PROCENT wyliczany według algorytmu - Rynkowa wartość kapitału własnego WACC
    private $sredniWazonyKosztKapitalu;                     // PROCENT wyliczany według algorytmu - Średni ważony koszt kapitału

    /* Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom i wierzycielom */
    private $przepPienPrzynStFinanTerminalValue;            // LICZBA wyliczana z algorytmu - Zdyskontowane przepływy wg stopy Terminal Value
    private $szacunekWartosciCalejFirmy;                    // TABLICA[6-elementowa] Element wyliczany według algotymu - Szacunek wartości całej firmy
    private $szacunekWartosciCalejFirmySuma;                // LICZBA SUMA $szacunekWartosciCalejFirmy i $szacunekWartosciCalejFirmyTerminalValue
    private $szacunekWartosciCalejFirmyTerminalValue;       // LICZBA wyliczana z algorytmu - Szacunek wartości całej firmy Terminal Value

    /* Obliczanie NPV projektu */
    private $NPV;                                           // LICZBA wyliczana według algorytmu
    private $IRR_wgRzeczywistejWartosciPVTable;             // TABLICA[7-elementowa] Elemety wyliczane według algorytmu - Obliczenie IRR wg rzeczywistej wartości PV (IRR do porównania z kosztem kapitału własnego)
    private $IRR_wgRzeczywistejWartosciPV;                  // PROCENT wyliczany za pomocą klasy IRRHelper - Wewnętrzna Stopa Zwrotu
    private $IRR_wgCenyOfertowejTable;                      // TABLICA[7-elementowa] Elemety wyliczane według algorytmu - Obliczenie IRR wg ceny ofertowej (IRR do porównania z kosztem kapitału własnego)
    private $IRR_wgCenyOfertowej;                           // PROCENT wyliczany za pomocą klasy IRRHelper - Wewnętrzna Stopa Zwrotu

    /* Klasyczny okres zwrotu */
    private $klasycznyOkresZwrotuSkumulowaneFCFE;           // TABLICA[7-elementowa] Element wyliczany według algotytmu - Skumulowane FCFE
    private $klasycznyOkresZwrotuSkumulowaneFCFErelacja;    // TABLICA[7 elementowa] Element wyliczany według algorytmu - Relacja skumulowane FCFE do CF0
    /* Zdyskontowany okres zwrotu */
    private $zdyskontowanyOkresZwrotuSkumulowaneFCFE;       // TABLICA[7-elementowa] Element wyliczany według algotytmu - Skumulowane zdyskontowane FCFE
    private $zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja;// TABLICA[7 elementowa] Element wyliczany według algorytmu


    //FUNCTIONS
    public function __construct() {
        $this->firma = '';
        $this->KRS = 0;
        $this->wartoscLikwidacyjna = 0;
        $this->rok0 = 0;
        $this->rok1 = 0;
        $this->rok2 = 0;
        $this->bilansTablica = [];
        $this->przychodyZeSprzedazy = [];
        $this->kosztyDzialanosciOperacyjnej = [];
        $this->wynagrodzenia = [];
        $this->zyskStrataZeSprzedazy = [];
        $this->pozostalePrzychodyOperacyjne = [];
        $this->pozostaleKosztyOperacyjne = [];
        $this->zyskStrataZDzialanosciOperacyjnej = [];
        $this->przychodyFinansowe = [];
        $this->kosztyFinansowe = [];
        $this->zyskStrataZDzialalnosciGospodarczej = [];
        $this->wynikZdarzenNadzwyczajnych = [];
        $this->zyskBrutto = [];
        $this->podatekDochodowy = [];
        $this->zyskNetto = [];
        $this->amortyzacja = [];
        $this->umorzenieSrTrwalych = 0;
        $this->sprzedazPierwszyRokPrognozy = 0;
        $this->kosztyOperacyjnePierwszyRokPrognozy = 0;
        $this->dywidendy = 0;
        $this->srOprZadlDl = 0;
        $this->stopaPodDoch = 0;
        $this->stopaDyskontowa = 0;
        $this->premiaRynkowaRyzyka = 0;
        $this->wspBeta = 0;
        $this->premiaWielkosci = 0;
        $this->premiaRyzykaSpec = 0;
        $this->wartoscKorekty = [];
        $this->oczekiwanaStopaWzrostu = [];
        $this->bilansPrognozaTable = [];
        $this->rachunekKosztowTable = [];
        $this->przyrostKapitaluPracujacego = [];
        $this->przyrostZadluzeniaDl = [];
        $this->przeplywyPieniezPrzynWlas = [];
        $this->bilansZamkniecia = [];
        $this->odsetkiNetto = [];
        $this->przepPienPrzynStFinan = [];
        $this->razemKosztKapitaluWlasnego = 0;
        $this->przeplywyPieniezPrzynWlasTerminalValue = 0;
        $this->szacunekWartosciKapitaluWlasnego = [];
        $this->szacunekWartosciKapitaluWlasnegoSuma = 0;
        $this->szacunekWartosciKapitaluWlasnegoTerminalValue = 0;
        $this->rynkowaWartoscDluguProcent = 0;
        $this->rynkowaWartoscDluguKoszt = 0;
        $this->rynkowaWartoscDluguWACC = 0;
        $this->rynkowaWartoscKapitaluWlasnegoProcent = 0;
        $this->rynkowaWartoscKapitaluWlasnegoWACC = 0;
        $this->sredniWazonyKosztKapitalu = 0;
        $this->przepPienPrzynStFinanTerminalValue = 0;
        $this->szacunekWartosciCalejFirmy = [];
        $this->szacunekWartosciCalejFirmySuma = 0;
        $this->szacunekWartosciCalejFirmyTerminalValue = 0;
        $this->NPV = 0;
        $this->IRR_wgRzeczywistejWartosciPVTable = [];
        $this->IRR_wgRzeczywistejWartosciPV = 0;
        $this->IRR_wgCenyOfertowejTable = [];
        $this->IRR_wgCenyOfertowej = 0;
        $this->klasycznyOkresZwrotuSkumulowaneFCFE = [];
        $this->klasycznyOkresZwrotuSkumulowaneFCFErelacja = [];
        $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE = [];
        $this->zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja = [];
    }

    public function getFirma() {
        return $this->firma;
    }

    private function setFirma(PHPExcel $excel) {
        $firma = $excel->getActiveSheet()->getCell('A1')->getValue();
        $iloscZnakowNazwyFirmy = strpos($firma, ",");
        if ($iloscZnakowNazwyFirmy != false) {
            $this->firma = substr($firma, 0, $iloscZnakowNazwyFirmy);
        } else {
            $this->firma = $firma;
        }
    }

    public function getKRS() {
        return $this->KRS;
    }

    private function setKRS(PHPExcel $excel) {
        $tekst = $excel->getActiveSheet()->getCell('A3')->getValue();
        $wyrazy = explode(" ", $tekst);
        $this->KRS = (int)($wyrazy[1]);
    }

    public function getWartoscLikwidacyjna() {
        return $this->wartoscLikwidacyjna;
    }

    /* Wartość likwidacyjna = aktywa trwałe + zapasy + należności + środki pieniężne - zobowiązania dł. - zobowiązania kr. */
    /* $wartoscLikwidacyjna = ($bilansTablica[rok][11] * $wartoscKorekty[0])
     *                         + ($bilansTablica[rok][48] * $wartoscKorekty[1])
     *                         + ($bilansTablica[rok][54] * $wartoscKorekty[2])
     *                         + ($bilansTablica[rok][67] * $wartoscKorekty[3])
     *                         - ($bilansTablica[rok][105] * $wartoscKorekty[5])
     *                         - ($bilansTablica[rok][112] * $wartoscKorekty[6])
     */
    private function setWartoscLikwidacyjna() {
        $this->wartoscLikwidacyjna = ($this->bilansTablica[$this->rok0][11] * $this->wartoscKorekty[0])
            + ($this->bilansTablica[$this->rok0][48] * $this->wartoscKorekty[1])
            + ($this->bilansTablica[$this->rok0][54] * $this->wartoscKorekty[2])
            + ($this->bilansTablica[$this->rok0][67] * $this->wartoscKorekty[3])
            - ($this->bilansTablica[$this->rok0][105] * $this->wartoscKorekty[5])
            - ($this->bilansTablica[$this->rok0][112] * $this->wartoscKorekty[6]);
    }

    public function getRok0() {
        return $this->rok0;
    }

    private function setRok0($rok) {
        $this->rok0 = (int)$rok;
    }

    public function getRok1() {
        return $this->rok1;
    }

    private function setRok1($rok) {
        $this->rok1 = (int)$rok - 1;
    }

    public function getRok2() {
        return $this->rok2;
    }

    private function setRok2($rok) {
        $this->rok2 = (int)$rok - 2;
    }

    /**
     * @return mixed
     */
    public function getPrzychodyZeSprzedazy() {
        return $this->przychodyZeSprzedazy;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setPrzychodyZeSprzedazy($excelFile, $yearsTable) {
        //wiersze w pliku Excel 145 lub 193
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "145")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "193")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->przychodyZeSprzedazy = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getKosztyDzialanosciOperacyjnej() {
        return $this->kosztyDzialanosciOperacyjnej;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setKosztyDzialanosciOperacyjnej($excelFile, $yearsTable) {
        //wiersz w pliku Excel 199 (w wersji porównawczej)
        //wiersz w pliku Excel 145 - 156 (w wersji kalkulacyjnej)
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "199")->getValue();
                if ($value == null) {
                    //Jeśli nie ma w wersji porownawczej to liczymy na podstawie danych z wersji kalkulacyjnej.
                    //Wiersze (145 - 156) A - F
                    $value = ($excelFile->getActiveSheet()->getCell("$key" . "145")->getValue()) - ($excelFile->getActiveSheet()->getCell("$key" . "156")->getValue());
                }
                $toReturn[] = $value;
            }
        }
        $this->kosztyDzialanosciOperacyjnej = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getWynagrodzenia() {
        return $this->wynagrodzenia;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setWynagrodzenia($excelFile, $yearsTable) {
        //wiersz w pliku Excel 205
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "205")->getValue();
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->wynagrodzenia = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZyskStrataZeSprzedazy() {
        return $this->zyskStrataZeSprzedazy;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setZyskStrataZeSprzedazy($excelFile, $yearsTable) {
        //wiersz w pliku Excel 156 lub 209
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "156")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "209")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->zyskStrataZeSprzedazy = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getPozostalePrzychodyOperacyjne() {
        return $this->pozostalePrzychodyOperacyjne;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setPozostalePrzychodyOperacyjne($excelFile, $yearsTable) {
        //wiersz w pliku Excel 157 lub 210
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "157")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "210")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->pozostalePrzychodyOperacyjne = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getPozostaleKosztyOperacyjne() {
        return $this->pozostaleKosztyOperacyjne;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setPozostaleKosztyOperacyjne($excelFile, $yearsTable) {
        //wiersz w pliku Excel 161 lub 214
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "161")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "214")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->pozostaleKosztyOperacyjne = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZyskStrataZDzialanosciOperacyjnej() {
        return $this->zyskStrataZDzialanosciOperacyjnej;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setZyskStrataZDzialanosciOperacyjnej($excelFile, $yearsTable) {
        //wiersz w pliku Excel 165 lub 218
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "165")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "218")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->zyskStrataZDzialanosciOperacyjnej = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getPrzychodyFinansowe() {
        return $this->przychodyFinansowe;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setPrzychodyFinansowe($excelFile, $yearsTable) {
        //wiersz w pliku Excel 166 lub 219
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "166")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "219")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->przychodyFinansowe = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getKosztyFinansowe() {
        return $this->kosztyFinansowe;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setKosztyFinansowe($excelFile, $yearsTable) {
        //wiersz w pliku Excel 174 lub 227
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "174")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "227")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->kosztyFinansowe = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZyskStrataZDzialalnosciGospodarczej() {
        return $this->zyskStrataZDzialalnosciGospodarczej;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setZyskStrataZDzialalnosciGospodarczej($excelFile, $yearsTable) {
        //wiersz w pliku Excel 180 lub 233
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "180")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "233")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->zyskStrataZDzialalnosciGospodarczej = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getWynikZdarzenNadzwyczajnych() {
        return $this->wynikZdarzenNadzwyczajnych;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setWynikZdarzenNadzwyczajnych($excelFile, $yearsTable) {
        //wiersz w pliku Excel 181 lub 234
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "181")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "234")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->wynikZdarzenNadzwyczajnych = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZyskBrutto() {
        return $this->zyskBrutto;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setZyskBrutto($excelFile, $yearsTable) {
        //wiersz w pliku Excel 184 lub 237
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "184")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "237")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->zyskBrutto = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getPodatekDochodowy() {
        return $this->podatekDochodowy;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setPodatekDochodowy($excelFile, $yearsTable) {
        //wiersz w pliku Excel 185 lub 238
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "185")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "238")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->podatekDochodowy = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZyskNetto() {
        return $this->zyskNetto;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setZyskNetto($excelFile, $yearsTable) {
        //wiersz w pliku Excel 187 lub 240
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "187")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "240")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->zyskNetto = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getAmortyzacja() {
        return $this->amortyzacja;
    }

    /**
     * @param mixed $excelFile , $yearsTable
     */
    private function setAmortyzacja($excelFile, $yearsTable) {
        //wiersz w pliku Excel 200
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "200")->getValue();
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->amortyzacja = $toReturn;
    }

    public function changeAmortyzacja($amortyzacja) {
        $this->amortyzacja = $amortyzacja;
    }

    public function getBilansTablica() {
        return $this->bilansTablica;
    }

    private function setBilansTablica($excelFile, $yearsTable) {
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                for ($row = 11; $row <= 139; $row++) {
                    $value = $excelFile->getActiveSheet()->getCell("$key$row")->getValue();
                    if ($value != null) {
                        $toReturn[$year][$row] = $value;
                    } else {
                        $toReturn[$year][$row] = 0.00;
                    }
                }
            }
        }
        $this->bilansTablica = $toReturn;
    }


    //ALOGORTYMY

    /*
    Dane bilansowe
    Aktywa bieżące w roku bazowym               = $bilansTablica[rok][47]
    Aktywa trwałe brutto w roku bazowym			= $bilansTablica[rok][11]
    Umorzenie środków trwałych w roku bazowym	= $umorzenieSrTrwalych (na stałe 0)
    Pasywa bieżące w roku bazowym				= $bilansTablica[rok][112]
    Kapitał własny w roku bazowym				= $bilansTablica[rok][86]
    Zadłużenie długoterminowe w roku bazowym	= $bilansTablica[rok][105]
    Odsetki					                    = $kosztyFinansowe
    */

    public function displayDaneBilansowe() {
        echo "<br>" . "Dane bilansowe" . "<br>";
        echo $this->bilansTablica[$this->getRok0()][47] . " - Aktywa bieżące w roku bazowym" . "<br>";
        echo $this->bilansTablica[$this->getRok0()][11] . " - Aktywa trwałe brutto w roku bazowym" . "<br>";
        echo $this->umorzenieSrTrwalych . " - Umorzenie środków trwałych w roku bazowym" . "<br>";
        echo $this->bilansTablica[$this->getRok0()][112] . " - Pasywa bieżące w roku bazowym" . "<br>";
        echo $this->bilansTablica[$this->getRok0()][86] . " - Kapitał własny w roku bazowym" . "<br>";
        echo $this->bilansTablica[$this->getRok0()][105] . " - Zadłużenie długoterminowe w roku bazowym" . "<br>";
        echo $this->kosztyFinansowe[0] . " -  Odsetki" . "<br>";
    }

    /*
    Dane z rachunku wyników
    Sprzedaż w pierwszym roku prognozy					            = $przychodyZeSprzedazy + $pozostalePrzychodyOperacyjne + $przychodyFinansowe = $sprzedazPierwszyRokPrognozy
    Koszty operacyjne w pierwszym roku prognozy (minus amortyzacja) = ($kosztyDzialalnosciOperacyjnej - $amortyzacja)/$sprzedazPierwszyRokPrognozy
    Koszty ogólne w pierwszym roku prognozy					        = $pozostaleKosztyOperacyjne
    Amortyzacja w pierwszym roku prognozy					        = $amortyzacja
    Podatek					                                        = $podatekDochodowy
    */

    public function getSprzedazPierwszyRokPrognozy() {
        return $this->sprzedazPierwszyRokPrognozy;
    }

    private function setSprzedazPierwszyRokPrognozy() {
        $this->sprzedazPierwszyRokPrognozy = $this->przychodyZeSprzedazy[0] + $this->pozostalePrzychodyOperacyjne[0] + $this->przychodyFinansowe[0];
    }

    public function getKosztyOperacyjnePierwszyRokPrognozy() {
        return $this->kosztyOperacyjnePierwszyRokPrognozy;
    }

    private function setKosztyOperacyjnePierwszyRokPrognozy() {
        $this->kosztyOperacyjnePierwszyRokPrognozy = ($this->kosztyDzialanosciOperacyjnej[0] - $this->amortyzacja[0]) / $this->sprzedazPierwszyRokPrognozy;
    }

    public function displayDaneRachunkuWyników() {
        echo "<br>" . "Dane z rachunku wyników" . "<br>";
        echo $this->getSprzedazPierwszyRokPrognozy() . " - Sprzedaż w pierwszym roku prognozy	" . "<br>";
        echo $this->getKosztyOperacyjnePierwszyRokPrognozy() . " - Koszty operacyjne w pierwszym roku prognozy (minus amortyzacja)" . "<br>";
        echo $this->pozostaleKosztyOperacyjne[0] . " - Koszty ogólne w pierwszym roku prognozy" . "<br>";
        echo $this->amortyzacja[0] . " - Amortyzacja w pierwszym roku prognozy" . "<br>";
        echo $this->podatekDochodowy[0] . " - Podatek" . "<br>";
    }

    /*
    Dane ze sprawozdania przepływów pieniężnych
    Wydatki inwestycyjne w pierwszym roku prognozy      = $amortyzacja
    Dywidendy jako odsetek przepływów pieniężnych netto = z formularza $_POST['dywidendy']

    Średnie oprocentowanie zadłużenia długoterminowego  = z formularza $_POST['srOprZadlDl']
    Stopa podatku dochodowego                           = z formularza $_POST['stopaPodDoch']
    Wolna od ryzyka stopa dyskontowa                    = z formularza $_POST['stopaDyskontowa']
    Premia rynkowa z tytułu ryzyka                      = z formularza $_POST['premiaRynkowaRyzyka']
    Współczynnik beta                                   = z formularza $_POST['wspBeta']
    Premia z tytułu wielkości                           = z formularza $_POST['premiaWielkosci']
    Premia z tytułu ryzyka specyficznego                = z formularza $_POST['premiaRyzykaSpec']
    */

    public function getDywidendy() {
        return $this->dywidendy;
    }

    private function setDywidendy($dywidendy) {
        $value = floatval($dywidendy);
        $this->dywidendy = round($value / 100, 3);
    }

    public function getSrOprZadlDl() {
        return $this->srOprZadlDl;
    }

    private function setSrOprZadlDl($srOprZadlDl) {
        $value = floatval($srOprZadlDl);
        $this->srOprZadlDl = round($value / 100, 3);
    }

    public function getStopaPodDoch() {
        return $this->stopaPodDoch;
    }

    private function setStopaPodDoch($stopaPodDoch) {
        $value = floatval($stopaPodDoch);
        $this->stopaPodDoch = round($value / 100, 3);
    }

    public function getStopaDyskontowa() {
        return $this->stopaDyskontowa;
    }

    private function setStopaDyskontowa($stopaDyskontowa) {
        $value = floatval($stopaDyskontowa);
        $this->stopaDyskontowa = round($value / 100, 3);
    }

    public function getPremiaRynkowaRyzyka() {
        return $this->premiaRynkowaRyzyka;
    }

    private function setPremiaRynkowaRyzyka($premiaRynkowaRyzyka) {
        $value = floatval($premiaRynkowaRyzyka);
        $this->premiaRynkowaRyzyka = round($value / 100, 3);
    }

    public function getWspBeta() {
        return $this->wspBeta;
    }

    private function setWspBeta($wspBeta) {
        $this->wspBeta = floatval($wspBeta);
    }

    public function getPremiaWielkosci() {
        return $this->premiaWielkosci;
    }

    private function setPremiaWielkosci($premiaWielkosci) {
        $value = floatval($premiaWielkosci);
        $this->premiaWielkosci = round($value / 100, 3);
    }

    public function getPremiaRyzykaSpec() {
        return $this->premiaRyzykaSpec;
    }

    private function setPremiaRyzykaSpec($premiaRyzykaSpec) {
        $value = floatval($premiaRyzykaSpec);
        $this->premiaRyzykaSpec = round($value / 100, 3);
    }

    public function displayDaneZeSprawozdaniaPrzeplywowPienieznych() {
        echo "<br>" . "Dane ze sprawozdania przepływów pieniężnych" . "<br>";
        echo $this->amortyzacja[0] . " - Wydatki inwestycyjne w pierwszym roku prognozy" . "<br>";
        echo $this->getDywidendy() . " - Dywidendy jako odsetek przepływów pieniężnych netto" . "<br><br>";
        echo $this->getSrOprZadlDl() . " - Średnie oprocentowanie zadłużenia długoterminowego" . "<br>";
        echo $this->getStopaPodDoch() . " - Stopa podatku dochodowego" . "<br>";
        echo $this->getStopaDyskontowa() . " - Wolna od ryzyka stopa dyskontowa" . "<br>";
        echo $this->getPremiaRynkowaRyzyka() . " - Premia rynkowa z tytułu ryzyka" . "<br>";
        echo $this->getWspBeta() . " - Współczynnik beta" . "<br>";
        echo $this->getPremiaWielkosci() . " - Premia z tytułu wielkości" . "<br>";
        echo $this->getPremiaRyzykaSpec() . " - Premia z tytułu ryzyka specyficznego" . "<br>";
    }

    /*
    Wartość korekty z formularza - PROCENTY
    TABLICA $wartoscKorekty
    $wartoscKorekty[0]   - Aktywa trwałe      = $_POST[wartoscKorekty0]
    $wartoscKorekty[1]   - Zapasy             = $_POST[wartoscKorekty1]
    $wartoscKorekty[2]   - Należności         = $_POST[wartoscKorekty2]
    $wartoscKorekty[3]   - Środki pieniężne   = $_POST[wartoscKorekty3]
    $wartoscKorekty[4]   - Kapital własny     = $_POST[wartoscKorekty4]
    $wartoscKorekty[5]   - Zobowiązania dł.   = $_POST[wartoscKorekty5]
    $wartoscKorekty[6]   - Zobowiązania kr.   = $_POST[wartoscKorekty6]
    $wartoscKorekty[7]   - Suma bilansowa     = $_POST[wartoscKorekty7]
    $wartoscKorekty[8]   - Przychody          = $_POST[wartoscKorekty8]
    $wartoscKorekty[9]   - Zysk netto         = $_POST[wartoscKorekty9]
    */

    public function getWartoscKorekty() {
        return $this->wartoscKorekty;
    }

    private function setWartoscKorekty($wartoscKorektyForm) {
        $toReturn = [];
        if (isset($wartoscKorektyForm)) {
            for ($i = 0; $i < 10; $i++) {
                $value = floatval($wartoscKorektyForm["wartoscKorekty$i"]);
                $toReturn[] = round($value / 100, 3);
            }
        }
        $this->wartoscKorekty = $toReturn;
    }

    /*
    Stopy wzrostu zmiennych ze sprawozdania finansowego
    $oczekiwanaStopaWzrostu[wariant][0] - Oczekiwana stopa wzrostu
    $oczekiwanaStopaWzrostu[wariant][1] - Oczekiwana stopa wzrosty sprzedaży
    $oczekiwanaStopaWzrostu[wariant][2] - Oczekiwana stopa wzrostu kosztów operacyjnych
    $oczekiwanaStopaWzrostu[wariant][3] - Oczekiwana stopa wzrostu kosztów ogólnych
    $oczekiwanaStopaWzrostu[wariant][4] - Oczekiwana stopa wzrostu aktywów bieżących
    $oczekiwanaStopaWzrostu[wariant][5] - Oczekiwana stopa wzrostu pasywów bieżących
    $oczekiwanaStopaWzrostu[wariant][6] - Oczekiwana stopa wzrostu zadłużenia długoterminowego
    $oczekiwanaStopaWzrostu[wariant][7] - Oczekiwana stopa wzrostu amortyzacji
    $oczekiwanaStopaWzrostu[wariant][8] - Oczekiwana stopa wzrostu inwestycji odtworzeniowych
    $oczekiwanaStopaWzrostu[wariant][9] - Oczekiwana stopa wzrostu wolnych przepływów po okresie szczegółowej prognozy
    $oczekiwanaStopaWzrostu[wariant][10] - Oczekiwana stopa wzrostu kapitałów własnych

    Wwariant =
        0 - wariant zerowy
        1 - wariant branżowy
        2 - wariant średniej dynamiki
    */

    public function getOczekiwanaStopaWzrostu() {
        return $this->oczekiwanaStopaWzrostu;
    }

    private function setOczekiwanaStopaWzrostu($oczekiwabaStopaWzrostuForm) {
        $toReturn = [];
        if (isset($oczekiwabaStopaWzrostuForm)) {
            for ($wariant = 0; $wariant < 3; $wariant++) {
                for ($i = 0; $i < 11; $i++) {
                    $value = floatval($oczekiwabaStopaWzrostuForm["oczekiwanaStopaWzrostu-$wariant$i"]);
                    $toReturn[$wariant][$i] = round($value / 100, 3);
                }
            }
        }
        $this->oczekiwanaStopaWzrostu = $toReturn;
    }

    public function displayOczekiwanaStopatWzrostu() {
        echo "<br>" . "Stopy wzrostu zmiennych ze sprawozdania finansowego" . "<br>";
        for ($wariant = 0; $wariant < 3; $wariant++) {
            echo "WARIANT $wariant <br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][0] . " - Oczekiwana stopa wzrostu" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][1] . " - Oczekiwana stopa wzrostu sprzedaży" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][2] . " - Oczekiwana stopa wzrostu kosztów operacyjnych" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][3] . " - Oczekiwana stopa wzrostu kosztów ogólnych" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][4] . " - Oczekiwana stopa wzrostu aktywów bieżących" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][5] . " - Oczekiwana stopa wzrostu pasywów bieżących" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][6] . " - Oczekiwana stopa wzrostu zadłużenia długoterminowego" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][7] . " - Oczekiwana stopa wzrostu amortyzacji" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][8] . " - Oczekiwana stopa wzrostu inwestycji odtworzeniowych" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][9] . " - Oczekiwana stopa wzrostu wolnych przepływów po okresie szczegółowej prognozy" . "<br>";
            echo $this->oczekiwanaStopaWzrostu[$wariant][10] . " - Oczekiwana stopa wzrostu kapitałów własnych" . "<br>";
        }

    }

    public function loadFormInformation($form) {
        $this->setDywidendy($form['dywidendy']);
        $this->setSrOprZadlDl($form['srOprZadlDl']);
        $this->setStopaPodDoch($form['stopaPodDoch']);
        $this->setStopaDyskontowa($form['stopaDyskontowa']);
        $this->setPremiaRynkowaRyzyka($form['premiaRynkowaRyzyka']);
        $this->setWspBeta($form['wspBeta']);
        $this->setPremiaWielkosci($form['premiaWielkosci']);
        $this->setPremiaRyzykaSpec($form['premiaRyzykaSpec']);
        $this->setOczekiwanaStopaWzrostu($form);
        $this->setWartoscKorekty($form);
    }

    /*
    Bilans - tablica dwuwymiarowa wykorzystywana w algorytmach.
    Każda pozycja jest tablicą. Pierwsza wartość to wykonanie w badanym roku, kolejne to prognoza na 7 lat do przodu
    0 - Aktywa bieżące
    1 - Aktywa trwałe brutto
    2 - Inwestycje odtworzeniowe
    3 - Umorzenie
    4 - Amortyzacja
    5 - Aktywa trwałe netto
    6 - Aktywa ogółem
    7 - Pasywa bieżące
    8 - Zadłużenie długoterminowe
    9 - Kapitał pracujący
    10 - Pasywa ogółem
    */

    public function getBilansPrognozaTable() {
        return $this->bilansPrognozaTable;
    }

    public function setBilansPrognozaTable($wariant) {
        $toReturn = [];

        //0 - Aktywa bieżące = (Aktywa biezące * $oczekiwanaStopaWzrostu[$wariant][4]) + Aktywa bieżące
        $aktywaBiezace[0] = $this->bilansTablica[$this->getRok0()][47];
        for ($i = 1; $i <= 7; $i++) {
            $aktywaBiezace[$i] = ($aktywaBiezace[$i - 1] * $this->oczekiwanaStopaWzrostu[$wariant][4]) + $aktywaBiezace[$i - 1];
            //echo "$i = ".number_format(round($aktywaBiezace[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['aktywa biezace'] = $aktywaBiezace;

        //2 - Inwestycje odtworzeniowe = Inwestycje odtworzeniowe * 1,05
        $inwestycjeOdtworzeniowe[0] = 0;
        $inwestycjeOdtworzeniowe[1] = $this->amortyzacja[0];
        for ($i = 2; $i <= 7; $i++) {
            $inwestycjeOdtworzeniowe[$i] = $inwestycjeOdtworzeniowe[$i - 1] * 1.05;
            //echo "$i = ".number_format(round($inwestycjeOdtworzeniowe[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['inwestycje odtworzeniowe'] = $inwestycjeOdtworzeniowe;

        //1 - Aktywa trwałe brutto = Aktywa trwale brutto[$i-1] + $inwestycjeOdtworzeniowe[$i]
        $aktywaTrwaleBrutto[0] = $this->bilansTablica[$this->getRok0()][11];
        for ($i = 1; $i <= 7; $i++) {
            $aktywaTrwaleBrutto[$i] = $aktywaTrwaleBrutto[$i - 1] + $inwestycjeOdtworzeniowe[$i];
            //echo "$i = ".number_format(round($aktywaTrwaleBrutto[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['aktywa trwale brutto'] = $aktywaTrwaleBrutto;

        //4 - Amortyzacja = $amortyzacja[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][7])
        $amortyzacja[0] = 0;
        $amortyzacja[1] = $this->amortyzacja[0];
        for ($i = 2; $i <= 7; $i++) {
            $amortyzacja[$i] = $amortyzacja[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][7]);
            //echo "$i = ".number_format(round($amortyzacja[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['amortyzacja'] = $amortyzacja;

        //3 - Umorzenie = $umorzenie[i-1] + amortyzacja[i]
        $umorzenie[0] = $this->umorzenieSrTrwalych;
        $umorzenie[1] = $umorzenie[0] + $this->amortyzacja[0];
        for ($i = 2; $i <= 7; $i++) {
            $umorzenie[$i] = $umorzenie[$i - 1] + $amortyzacja[$i];
            //echo "$i = ".number_format(round($umorzenie[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['umorzenie'] = $umorzenie;

        //5 - Aktywa trwałe netto = $aktywaTrwaleBrutto[i] - $umorzenie[i]
        $aktywaTrwaleNetto = [];
        for ($i = 0; $i <= 7; $i++) {
            $aktywaTrwaleNetto[$i] = $aktywaTrwaleBrutto[$i] - $umorzenie[$i];
            //echo "$i = ".number_format(round($aktywaTrwaleNetto[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['aktywa trwale netto'] = $aktywaTrwaleNetto;

        //6 - Aktywa ogółem = $aktywaBiezace[i] + $aktywaTrwaleNetto[i]
        $aktywaOgolem = [];
        for ($i = 0; $i <= 7; $i++) {
            $aktywaOgolem[$i] = $aktywaBiezace[$i] + $aktywaTrwaleNetto[$i];
            //echo "$i = ".number_format(round($aktywaOgolem[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['aktywa ogolem'] = $aktywaOgolem;

        //7 - Pasywa bieżące = $pasywaBiezace[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][5])
        $pasywaBiezace[0] = $this->bilansTablica[$this->getRok0()][112];
        for ($i = 1; $i <= 7; $i++) {
            $pasywaBiezace[$i] = $pasywaBiezace[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][5]);
            //echo "$i = ".number_format(round($pasywaBiezace[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['pasywa biezace'] = $pasywaBiezace;

        //8 - Zadłużenie długoterminowe = $zadluzenieDlugoterminowe[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][6])
        $zadluzenieDlugoterminowe[0] = $this->bilansTablica[$this->getRok0()][105];
        for ($i = 1; $i <= 7; $i++) {
            $zadluzenieDlugoterminowe[$i] = $zadluzenieDlugoterminowe[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][6]);
            //echo "$i = ".number_format(round($zadluzenieDlugoterminowe[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['zadluzenie dlugoterminowe'] = $zadluzenieDlugoterminowe;

        //9 - Kapitał pracujący = $aktywaOgolem[i] - $pasywabiezace[i] - $zadluzenieDlugoterminowe[i]
        $kapitalPracujacy = [];
        for ($i = 0; $i <= 7; $i++) {
            $kapitalPracujacy[$i] = $aktywaOgolem[$i] - $pasywaBiezace[$i] - $zadluzenieDlugoterminowe[$i];
            //echo "$i = ".number_format(round($kapitalPracujacy[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['kapital pracujacy'] = $kapitalPracujacy;

        //10 - Pasywa ogółem = $pasywabiezace[i] + $zadluzenieDlugoterminowe[i] + $kapitalPracujacy[i]
        $pasywaOgolem = [];
        for ($i = 0; $i <= 7; $i++) {
            $pasywaOgolem[$i] = $pasywaBiezace[$i] + $zadluzenieDlugoterminowe[$i] + $kapitalPracujacy[$i];
            //echo "$i = ".number_format(round($pasywaOgolem[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['pasywa ogolem'] = $pasywaOgolem;

        $this->bilansPrognozaTable = $toReturn;
    }

    /*
    Rachunek kosztów - tablica dwuwymiarowa wykorzystywana w algorytmach.
    Każda pozycja jest tablicą. Pierwsza wartość to wykonanie w badanym roku, kolejne to prognoza na 6 lat do przodu
    0 - Sprzedaż
    1 - Koszty operacyjne (bez amortyzacji)
    2 - Zysk operacyjny
    3 - Koszty ogólne
    4 - Amortyzacja
    5 - EBIT
    6 - Odsetki
    7 - Zysk brutto
    8 - Podatek
    9 - Zysk netto
    */

    public function getRachunekKosztowTable() {
        return $this->rachunekKosztowTable;
    }

    public function setRachunekKosztowTable($wariant) {
        $toReturn = [];

        //0 - Sprzedaż = $sprzedaz[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][1])
        $sprzedaz[0] = $this->sprzedazPierwszyRokPrognozy;
        for ($i = 1; $i <= 6; $i++) {
            $sprzedaz[$i] = $sprzedaz[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][1]);
            //echo "$i = ".number_format(round($sprzedaz[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['sprzedaz'] = $sprzedaz;

        //1 - Koszty operacyjne (bez amortyzacji) = $kosztyOperacyjneBezAmortyzacji[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][2])
        $kosztyOperacyjneBezAmortyzacji[0] = $this->sprzedazPierwszyRokPrognozy * $this->kosztyOperacyjnePierwszyRokPrognozy;
        for ($i = 1; $i <= 6; $i++) {
            $kosztyOperacyjneBezAmortyzacji[$i] = $kosztyOperacyjneBezAmortyzacji[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][2]);
            //echo "$i = ".number_format(round($kosztyOperacyjneBezAmortyzacji[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['koszty operacyjne bez amortyzacji'] = $kosztyOperacyjneBezAmortyzacji;

        //2 - Zysk operacyjny = $sprzedaz[i] - $kosztyOperacyjneBezAmortyzacji[i]
        $zyskOperacyjny = [];
        for ($i = 0; $i <= 6; $i++) {
            $zyskOperacyjny[$i] = $sprzedaz[$i] - $kosztyOperacyjneBezAmortyzacji[$i];
            //echo "$i = ".number_format(round($zyskOperacyjny[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['zysk operacyjny'] = $zyskOperacyjny;

        //3 - Koszty ogólne = $kosztyOgolne[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][3])
        $kosztyOgolne[0] = $this->pozostaleKosztyOperacyjne[0];
        for ($i = 1; $i <= 6; $i++) {
            $kosztyOgolne[$i] = $kosztyOgolne[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][3]);
            //echo "$i = ".number_format(round($kosztyOgolne[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['koszty ogolne'] = $kosztyOgolne;

        //4 - Amortyzacja = $amortyzacja[i-1] * (1 + $oczekiwanaStopaWzrostu[$wariant][7])
        $amort[0] = $this->amortyzacja[0];
        for ($i = 1; $i <= 6; $i++) {
            $amort[$i] = $amort[$i - 1] * (1 + $this->oczekiwanaStopaWzrostu[$wariant][7]);
            //echo "$i = ".number_format(round($amort[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['amortyzacja'] = $amort;

        //5 - EBIT = $zyskOperacyjny[i] - $kosztyOgolne[i] - $amort[i]
        $EBIT = [];
        for ($i = 0; $i <= 6; $i++) {
            $EBIT[$i] = $zyskOperacyjny[$i] - $kosztyOgolne[$i] - $amort[$i];
            //echo "$i = ".number_format(round($EBIT[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['EBIT'] = $EBIT;

        //6 - Odsetki = $odsetki[i-1] * 1.05
        $odsetki[0] = $this->kosztyFinansowe[0];
        for ($i = 1; $i <= 6; $i++) {
            $odsetki[$i] = $odsetki[$i - 1] * 1.05;
            //echo "$i = ".number_format(round($odsetki[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['odsetki'] = $odsetki;

        //7 - Zysk brutto = $EBIT[i] - $odsetki[i]
        $zyskBrutto = [];
        for ($i = 0; $i <= 6; $i++) {
            $zyskBrutto[$i] = $EBIT[$i] - $odsetki[$i];
            //echo "$i = ".number_format(round($zyskBrutto[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['zysk brutto'] = $zyskBrutto;

        //8 - Podatek = $zyskBrutto[i] * $this->stopaPodDoch
        $podatek[0] = $this->podatekDochodowy[0];
        for ($i = 1; $i <= 6; $i++) {
            $podatek[$i] = $zyskBrutto[$i] * $this->stopaPodDoch;
            //echo "$i = ".number_format(round($podatek[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['podatek'] = $podatek;

        //9 - Zysk netto = $zyskBrutto[i] - $podatek[i]
        $zyskNetto = [];
        for ($i = 0; $i <= 6; $i++) {
            $zyskNetto[$i] = $zyskBrutto[$i] - $podatek[$i];
            //echo "$i = ".number_format(round($zyskNetto[$i]), 2, ',', ' ')."<br>";
        }
        //echo"<br>";
        $toReturn['zysk netto'] = $zyskNetto;

        $this->rachunekKosztowTable = $toReturn;
    }

    /*
    Obliczenie przepływów pieniężnych dla właścicieli
    Zysk netto                          = $rachunekKosztowTable[zysk netto][0-5]
    Amortyzacja (+)                     = $bilansPrognozaTable[amortyzacja][1-6]
    Wydatki inwestycyjne (-)            = $bilansPrognozaTable[inwestycje odtworzeniowe][1-6]
    Przyrost kapitału pracującego (-)   = $przyrostKapitaluPracujacego[]
    Przyrost zadłużenia dł. (+)         = $przyrostZadluzeniaDl[]
    Przepływy pienięż. przyn. właś.     = $przeplywyPieniezPrzynWlas[]
    */

    public function getPrzyrostKapitaluPracujacego() {
        return $this->przyrostKapitaluPracujacego;
    }

    public function setPrzyrostKapitaluPracujacego() {
        //Przyrost kapitału pracującego (-)  = $bilansPrognozaTable[kapital pracujacy][i+1] - $bilansPrognozaTable[kapital pracujacy][i]
        for ($i = 0; $i <= 5; $i++) {
            $this->przyrostKapitaluPracujacego[$i] = $this->bilansPrognozaTable['kapital pracujacy'][$i + 1] - $this->bilansPrognozaTable['kapital pracujacy'][$i];
            //echo "$i = ".number_format(round($this->przyrostKapitaluPracujacego[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    public function getPrzyrostZadluzeniaDl() {
        return $this->przyrostZadluzeniaDl;
    }

    public function setPrzyrostZadluzeniaDl() {
        //Przyrost zadłużenia dł. (+)   = $bilansPrognozaTable[zadluzenie dlugoterminowe][i+1] - $bilansPrognozaTable[zadluzenie dlugoterminowe][i]
        for ($i = 0; $i <= 5; $i++) {
            $this->przyrostZadluzeniaDl[$i] = $this->bilansPrognozaTable['zadluzenie dlugoterminowe'][$i + 1] - $this->bilansPrognozaTable['zadluzenie dlugoterminowe'][$i];
            //echo "$i = ".number_format(round($this->przyrostZadluzeniaDl[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    public function getPrzeplywyPieniezPrzynWlas() {
        return $this->przeplywyPieniezPrzynWlas;
    }

    public function setPrzeplywyPieniezPrzynWlas() {
        //Przepływy pienięż. przyn. właś. = $rachunekKosztowTable[zysk netto][i] + $bilansPrognozaTable[amortyzacja][i+1] - $bilansPrognozaTable[inwestycje odtworzeniowe][i+1]
        //                                      - $przyrostKapitaluPracujacego[i] + $przyrostZadluzeniaDl[i]
        for ($i = 0; $i <= 5; $i++) {
            $this->przeplywyPieniezPrzynWlas[$i] = $this->rachunekKosztowTable['zysk netto'][$i]
                + $this->bilansPrognozaTable['amortyzacja'][$i + 1]
                - $this->bilansPrognozaTable['inwestycje odtworzeniowe'][$i + 1]
                - $this->przyrostKapitaluPracujacego[$i]
                + $this->przyrostZadluzeniaDl[$i];
            //echo "$i = ".number_format(round($this->przeplywyPieniezPrzynWlas[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    /*
    Zmiana stanu kapitału własnego
    Bilans otwarcia             = $bilansPrognozaTable[kapital pracujacy][0-5]
    Zysk netto (+)              = $rachunekKosztowTable[zysk netto][0-5]
    Dywidendy wypłacone (-)     = $przeplywyPieniezPrzynWlas[0-5]
    Bilans zamknięcia           = $bilansZamkniecia[]
    */

    public function getBilansZamkniecia() {
        return $this->bilansZamkniecia;
    }

    public function setBilansZamkniecia() {
        //Bilans zamknięcia = $bilansPrognozaTable[kapital pracujacy][i] + $rachunekKosztowTable[zysk netto][i] - $przeplywyPieniezPrzynWlas[i]
        for ($i = 0; $i <= 5; $i++) {
            $this->bilansZamkniecia[$i] = $this->bilansPrognozaTable['kapital pracujacy'][$i]
                + $this->rachunekKosztowTable['zysk netto'][$i]
                - $this->przeplywyPieniezPrzynWlas[$i];
            //echo "$i = ".number_format(round($this->bilansZamkniecia[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    /*
    Obliczenie przepływów pieniężnych przynależnych wszystkim stronom finansującym
    Zysk netto                          = $rachunekKosztowTable[zysk netto][0-5]
    Amortyzacja (+)                     = $bilansPrognozaTable[amortyzacja][1-6]
    Wydatki inwestycyjne (-)            = $bilansPrognozaTable[inwestycje odtworzeniowe][1-6]
    Przyrost kapitału pracującego (-)   = $przyrostKapitaluPracujacego[0-5]
    Odsetki netto (+)                   = $odsetkiNetto[]
    Przep. pien. przyn. st. finan.      = $przepPienPrzynStFinan[]
    */

    public function getOdsetkiNetto() {
        return $this->odsetkiNetto;
    }

    public function setOdsetkiNetto() {
        //Odsetki netto (+) = $rachunekKosztowTable[odsetki][i] * (1 - $stopaPodDoch)
        for ($i = 0; $i <= 5; $i++) {
            $this->odsetkiNetto[$i] = $this->rachunekKosztowTable['odsetki'][$i] * (1 - $this->stopaPodDoch);
            //echo "$i = ".number_format(round($this->odsetkiNetto[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    public function getPrzepPienPrzynStFinan() {
        return $this->przepPienPrzynStFinan;
    }

    public function setPrzepPienPrzynStFinan() {
        //Przep. pien. przyn. st. finan. = $rachunekKosztowTable[zysk netto][i] + $bilansPrognozaTable[amortyzacja][i+1]
        // - $bilansPrognozaTable[inwestycje odtworzeniowe][i+1] - $przyrostKapitaluPracujacego[i] + $odsetkiNetto[i]
        for ($i = 0; $i <= 5; $i++) {
            $this->przepPienPrzynStFinan[$i] = $this->rachunekKosztowTable['zysk netto'][$i]
                + $this->bilansPrognozaTable['amortyzacja'][$i + 1]
                - $this->bilansPrognozaTable['inwestycje odtworzeniowe'][$i + 1]
                - $this->przyrostKapitaluPracujacego[$i]
                + $this->odsetkiNetto[$i];
            //echo "$i = ".number_format(round($this->przepPienPrzynStFinan[$i]), 2, ',', ' ')."<br>";
        }
        //echo "<br>";
    }

    /*
    Wyznaczenie kosztu kapitału własnego
    Wolna od ryzyka stopa dyskontowa plus ryzyko systematyczne  = $stopaDyskontowa              - procenty z formularza
    Premia rynkowa z tytułu ryzyka                              = $premiaRynkowaRyzyka          - procenty z formularza
    Współczynnik Beta                                           = $wspBeta                      - liczba z formularza
    Premia z tytułu wielkości                                   = $premiaWielkosci              - procenty z formularza
    Ryzyko specyficzne                                          = $premiaRyzykaSpec             - procenty z formularza
    Razem koszt kapitału własnego                               = $razemKosztKapitaluWlasnego   - procenty wyliczone
    */

    public function getRazemKosztKapitaluWlasnego() {
        return $this->razemKosztKapitaluWlasnego;
    }

    public function setRazemKosztKapitaluWlasnego() {
        //Razem koszt kapitału własnego = $stopaDyskontowa + ($premiaRynkowaRyzyka * $wspBeta) + $premiaWielkosci + $premiaRyzykaSpec
        $this->razemKosztKapitaluWlasnego = $this->stopaDyskontowa
            + ($this->premiaRynkowaRyzyka * $this->wspBeta)
            + $this->premiaWielkosci
            + $this->premiaRyzykaSpec;
        //echo "Razem koszt kapitału własnego = ".($this->razemKosztKapitaluWlasnego*100)."%<br><br>";
    }

    /* Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom */
    /*********************************************************************************
     * Wartość procentowa w tabeli                                          = $razemKosztKapitaluWlasnego
     * Zdyskontowane przepływy wg stopy [za lata (rok0 + 1) do (rok0 + 6 ]  = $przeplywyPieniezPrzynWlas[0-5]
     * Zdyskontowane przepływy wg stopy Terminal Value                      = $przeplywyPieniezPrzynWlasTerminalValue
     *
     * Szacunek wartości kapitału własnego SUMA                             = $szacunekWartosciKapitaluWlasnegoSuma
     * Szacunek wartości kapitału własnego                                  = $szacunekWartosciKapitaluWlasnego[0-5]
     * Szacunek wartości kapitału własnego Terminal Value                   = $szacunekWartosciKapitaluWlasnegoTerminalValue
     */

    public function getPrzeplywyPieniezPrzynWlasTerminalValue() {
        return $this->przeplywyPieniezPrzynWlasTerminalValue;
    }

    public function setPrzeplywyPieniezPrzynWlasTerminalValue() {
        //Zdyskontowane przepływy wg stopy Terminal Value = ($przeplywyPieniezPrzynWlas[5] * (1 + 0,05)/($razemKosztKapitaluWlasnego - 0,05))/1
        $this->przeplywyPieniezPrzynWlasTerminalValue = ($this->przeplywyPieniezPrzynWlas[5] * (1 + 0.05) / ($this->razemKosztKapitaluWlasnego - 0.05)) / 1;
        //echo "Zdyskontowane przepływy wg stopy Terminal Value = ".number_format(round($this->przeplywyPieniezPrzynWlasTerminalValue), 2, ',', ' ')."<br><br>";
    }

    public function getSzacunekWartosciKapitaluWlasnego() {
        return $this->szacunekWartosciKapitaluWlasnego;
    }

    public function setSzacunekWartosciKapitaluWlasnego() {
        //Szacunek wartości kapitału własnego[0] = $przeplywyPieniezPrzynWlas[0]/(1 + $razemKosztKapitaluWlasnego)
        //Szacunek wartości kapitału własnego[1] = $przeplywyPieniezPrzynWlas[1]/((1 + $razemKosztKapitaluWlasnego) * (1 + $razemKosztKapitaluWlasnego))
        //itd aż do 5
        $this->szacunekWartosciKapitaluWlasnego[0] = $this->przeplywyPieniezPrzynWlas[0] / (1 + $this->razemKosztKapitaluWlasnego);
        $this->szacunekWartosciKapitaluWlasnego[1] = $this->przeplywyPieniezPrzynWlas[1] / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
        $this->szacunekWartosciKapitaluWlasnego[2] = $this->przeplywyPieniezPrzynWlas[2] / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
        $this->szacunekWartosciKapitaluWlasnego[3] = $this->przeplywyPieniezPrzynWlas[3] / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
        $this->szacunekWartosciKapitaluWlasnego[4] = $this->przeplywyPieniezPrzynWlas[4] / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
        $this->szacunekWartosciKapitaluWlasnego[5] = $this->przeplywyPieniezPrzynWlas[5] / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
    }

    public function getSzacunekWartosciKapitaluWlasnegoTerminalValue() {
        return $this->szacunekWartosciKapitaluWlasnegoTerminalValue;
    }

    public function setSzacunekWartosciKapitaluWlasnegoTerminalValue() {
        $this->szacunekWartosciKapitaluWlasnegoTerminalValue = $this->przeplywyPieniezPrzynWlasTerminalValue
            / ((1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego)
                * (1 + $this->razemKosztKapitaluWlasnego));
    }

    public function getSzacunekWartosciKapitaluWlasnegoSuma() {
        return $this->szacunekWartosciKapitaluWlasnegoSuma;
    }

    public function setSzacunekWartosciKapitaluWlasnegoSuma() {
        $this->szacunekWartosciKapitaluWlasnegoSuma = 0;
        foreach ($this->szacunekWartosciKapitaluWlasnego as $value) {
            $this->szacunekWartosciKapitaluWlasnegoSuma = $this->szacunekWartosciKapitaluWlasnegoSuma + $value;
        }
        $this->szacunekWartosciKapitaluWlasnegoSuma = $this->szacunekWartosciKapitaluWlasnegoSuma + $this->szacunekWartosciKapitaluWlasnegoTerminalValue;
    }

    /* Wyznaczenie WACC */
    /********************
     * Rynkowa wartość długu:
     *      - Kwota     = $bilansTablica[rok0][105]
     *      - Procent   = $rynkowaWartoscDluguProcent
     *      - Koszt     = $rynkowaWartoscDluguKoszt
     *      - WACC      = $rynkowaWartoscDluguWACC
     * Rynkowa wartość kapitału własnego:
     *      - Kwota     = $szacunekWartosciKapitaluWlasnegoSuma
     *      - Procent   = $rynkowaWartoscKapitaluWlasnegoProcent
     *      - Koszt     = $razemKosztKapitaluWlasnego
     *      - WACC      = $rynkowaWartoscKapitaluWlasnegoWACC
     * Średni ważony koszt kapitału = $sredniWazonyKosztKapitalu
     */

    public function getRynkowaWartoscDluguProcent() {
        return $this->rynkowaWartoscDluguProcent;
    }

    public function setRynkowaWartoscDluguProcent() {
        //$rynkowaWartoscDluguProcent = $bilansTablica[rok0][105]/($bilansTablica[rok0][105] + $szacunekWartosciKapitaluWlasnegoSuma)
        $this->rynkowaWartoscDluguProcent = $this->bilansTablica[$this->rok0][105] / ($this->bilansTablica[$this->rok0][105] + $this->szacunekWartosciKapitaluWlasnegoSuma);
    }

    public function getRynkowaWartoscDluguKoszt() {
        return $this->rynkowaWartoscDluguKoszt;
    }

    public function setRynkowaWartoscDluguKoszt() {
        //$rynkowaWartoscDluguKoszt = $premiaRynkowaRyzyka * $wspBeta
        $this->rynkowaWartoscDluguKoszt = $this->premiaRynkowaRyzyka * $this->wspBeta;
    }

    public function getRynkowaWartoscDluguWACC() {
        return $this->rynkowaWartoscDluguWACC;
    }

    public function setRynkowaWartoscDluguWACC() {
        //$rynkowaWartoscDluguWACC = $rynkowaWartoscDluguProcent * $rynkowaWartoscDluguKoszt
        $this->rynkowaWartoscDluguWACC = $this->rynkowaWartoscDluguProcent * $this->rynkowaWartoscDluguKoszt;
    }

    public function getRynkowaWartoscKapitaluWlasnegoProcent() {
        return $this->rynkowaWartoscKapitaluWlasnegoProcent;
    }

    public function setRynkowaWartoscKapitaluWlasnegoProcent() {
        //$rynkowaWartoscKapitaluWlasnegoProcent = $szacunekWartosciKapitaluWlasnegoSuma /($szacunekWartosciKapitaluWlasnegoSuma + $bilansTablica[rok0][105])
        $this->rynkowaWartoscKapitaluWlasnegoProcent = $this->szacunekWartosciKapitaluWlasnegoSuma / ($this->szacunekWartosciKapitaluWlasnegoSuma + $this->bilansTablica[$this->rok0][105]);
    }

    public function getRynkowaWartoscKapitaluWlasnegoWACC() {
        return $this->rynkowaWartoscKapitaluWlasnegoWACC;
    }

    public function setRynkowaWartoscKapitaluWlasnegoWACC() {
        //$rynkowaWartoscKapitaluWlasnegoWACC = $rynkowaWartoscKapitaluWlasnegoProcent * $razemKosztKapitaluWlasnego
        $this->rynkowaWartoscKapitaluWlasnegoWACC = $this->rynkowaWartoscKapitaluWlasnegoProcent * $this->razemKosztKapitaluWlasnego;
    }

    public function getSredniWazonyKosztKapitalu() {
        return $this->sredniWazonyKosztKapitalu;
    }

    public function setSredniWazonyKosztKapitalu() {
        //$sredniWazonyKosztKapitalu = $rynkowaWartoscDluguWACC + $rynkowaWartoscKapitaluWlasnegoWACC
        $this->sredniWazonyKosztKapitalu = $this->rynkowaWartoscDluguWACC + $this->rynkowaWartoscKapitaluWlasnegoWACC;
    }


    /* Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom i wierzycielom */
    /************************************************************************************************
     * Wartość procentowa w tabeli                                          = $sredniWazonyKosztKapitalu
     * Zdyskontowane przepływy wg stopy [za lata (rok0 + 1) do (rok0 + 6 ]  = $przepPienPrzynStFinan[0-5]
     * Zdyskontowane przepływy wg stopy Terminal Value                      = $przepPienPrzynStFinanTerminalValue
     *
     * Szacunek wartości całej firmy SUMA                                   = $szacunekWartosciCalejFirmySuma
     * Szacunek wartości całej firmy                                        = $szacunekWartosciCalejFirmy[0-5]
     * Szacunek wartości całej firmy Terminal Value                         = $szacunekWartosciCalejFirmyTerminalValue
     */

    public function getPrzepPienPrzynStFinanTerminalValue() {
        return $this->przepPienPrzynStFinanTerminalValue;
    }

    public function setPrzepPienPrzynStFinanTerminalValue() {
        //Zdyskontowane przepływy wg stopy Terminal Value = ($przepPienPrzynStFinan[5] * (1 + 0,05)/($sredniWazonyKosztKapitalu - 0,05))/1
        $this->przepPienPrzynStFinanTerminalValue = ($this->przepPienPrzynStFinan[5] * (1 + 0.05) / ($this->sredniWazonyKosztKapitalu - 0.05)) / 1;
        //echo "Zdyskontowane przepływy wg stopy Terminal Value = ".number_format(round($this->przepPienPrzynStFinanTerminalValue), 2, ',', ' ')."<br><br>";
    }

    public function getSzacunekWartosciCalejFirmy() {
        return $this->szacunekWartosciCalejFirmy;
    }

    public function setSzacunekWartosciCalejFirmy() {
        //Szacunek wartości całej firmy[0] = $przepPienPrzynStFinan[0]/(1 + $sredniWazonyKosztKapitalu)
        //Szacunek wartości kapitału własnego[1] = $przepPienPrzynStFinan[1]/((1 + $sredniWazonyKosztKapitalu) * (1 + $sredniWazonyKosztKapitalu))
        //itd aż do 5
        $this->szacunekWartosciCalejFirmy[0] = $this->przepPienPrzynStFinan[0] / (1 + $this->sredniWazonyKosztKapitalu);
        $this->szacunekWartosciCalejFirmy[1] = $this->przepPienPrzynStFinan[1] / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
        $this->szacunekWartosciCalejFirmy[2] = $this->przepPienPrzynStFinan[2] / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
        $this->szacunekWartosciCalejFirmy[3] = $this->przepPienPrzynStFinan[3] / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
        $this->szacunekWartosciCalejFirmy[4] = $this->przepPienPrzynStFinan[4] / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
        $this->szacunekWartosciCalejFirmy[5] = $this->przepPienPrzynStFinan[5] / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
    }

    public function getSzacunekWartosciCalejFirmyTerminalValue() {
        return $this->szacunekWartosciCalejFirmyTerminalValue;
    }

    public function setSzacunekWartosciCalejFirmyTerminalValue() {
        $this->szacunekWartosciCalejFirmyTerminalValue = $this->przepPienPrzynStFinanTerminalValue
            / ((1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu)
                * (1 + $this->sredniWazonyKosztKapitalu));
    }

    public function getSzacunekWartosciCalejFirmySuma() {
        return $this->szacunekWartosciCalejFirmySuma;
    }

    public function setSzacunekWartosciCalejFirmySuma() {
        $this->szacunekWartosciCalejFirmySuma = 0;
        foreach ($this->szacunekWartosciCalejFirmy as $value) {
            $this->szacunekWartosciCalejFirmySuma = $this->szacunekWartosciCalejFirmySuma + $value;
        }
        $this->szacunekWartosciCalejFirmySuma = $this->szacunekWartosciCalejFirmySuma + $this->szacunekWartosciCalejFirmyTerminalValue;
    }

    /* Obliczanie NPV projektu */
    /***************************
     * PV           = $szacunekWartosciCalejFirmySuma
     * minus cena   = $bilansTablica[rok][105]
     * mimus dług   = $szacunekWartosciKapitaluWlasnegoSuma
     * NPV          = $NPV
     */

    public function getNPV() {
        return $this->NPV;
    }

    public function setNPV() {
        //NPV = $szacunekWartosciCalejFirmySuma - $bilansTablica[rok0][105] - $szacunekWartosciKapitaluWlasnegoSuma
        $this->NPV = $this->szacunekWartosciCalejFirmySuma - $this->bilansTablica[$this->rok0][105] - $this->szacunekWartosciKapitaluWlasnegoSuma;
    }

    /* Obliczenie IRR wg rzeczywistej wartości PV (IRR do porównania z kosztem kapitału własnego) */
    /**********************************************************************************************
     * $IRR_wgRzeczywistejWartosciPVTable[0-6] =
     * [0]      - $szacunekWartosciCalejFirmySuma*-1
     * [1-5]    - $przepPienPrzynStFinan[0-4]
     * [6]      - $przepPienPrzynStFinanTerminalValue + $przepPienPrzynStFinan[5]
     *
     * $IRR_wgRzeczywistejWartosciPV = IRRHelper z parametrem $IRR_wgRzeczywistejWartosciPVTable
     */

    public function getIRR_wgRzeczywistejWartosciPVTable() {
        return $this->IRR_wgRzeczywistejWartosciPVTable;
    }

    public function setIRR_wgRzeczywistejWartosciPVTable() {
        $this->IRR_wgRzeczywistejWartosciPVTable[0] = $this->szacunekWartosciCalejFirmySuma * -1;
        for ($i = 0; $i <= 4; $i++) {
            $this->IRR_wgRzeczywistejWartosciPVTable[$i + 1] = $this->przepPienPrzynStFinan[$i];
        }
        $this->IRR_wgRzeczywistejWartosciPVTable[6] = $this->przepPienPrzynStFinanTerminalValue + $this->przepPienPrzynStFinan[5];
    }

    public function getIRR_wgRzeczywistejWartosciPV() {
        return $this->IRR_wgRzeczywistejWartosciPV;
    }

    public function setIRR_wgRzeczywistejWartosciPV() {
        $IRR = IRRHelper::IRR($this->IRR_wgRzeczywistejWartosciPVTable);
        $this->IRR_wgRzeczywistejWartosciPV = $IRR*100;
    }

    /* Obliczenie IRR wg ceny ofertowej (IRR do porównania z kosztem kapitału własnego) */
    /************************************************************************************
     * $IRR_wgCenyOfertowejTable[0-6] =
     * [0]      - ($szacunekWartosciCalejFirmySuma * -1) + $NPV
     * [1-6]    - $IRR_wgRzeczywistejWartosciPVTable[1-6]
     *
     * $IRR_wgCenyOfertowej = IRRHelper z parametrem $IRR_wgCenyOfertowejTable
     */

    public function getIRR_wgCenyOfertowejTable() {
        return $this->IRR_wgCenyOfertowejTable;
    }

    public function setIRR_wgCenyOfertowejTable() {
        $this->IRR_wgCenyOfertowejTable[0] = ($this->szacunekWartosciCalejFirmySuma * -1) + $this->NPV;
        for ($i = 1; $i <= 6; $i++) {
            $this->IRR_wgCenyOfertowejTable[$i] = $this->IRR_wgRzeczywistejWartosciPVTable[$i];
        }
    }

    public function getIRR_wgCenyOfertowej() {
        return $this->IRR_wgCenyOfertowej;
    }

    public function setIRR_wgCenyOfertowej() {
        $IRR = IRRHelper::IRR($this->IRR_wgCenyOfertowejTable);
        $this->IRR_wgCenyOfertowej = $IRR*100;
    }

    /* Klasyczny okres zwrotu */
    /**************************
     * Skumulowane FCFE = $klasycznyOkresZwrotuSkumulowaneFCFE[0-6] :
     *                      [0]     - $szacunekWartosciKapitaluWlasnegoSuma
     *                      [1]     - $przeplywyPieniezPrzynWlas[0]
     *                      [2-6]   - $przeplywyPieniezPrzynWlas[1-5] + $klasycznyOkresZwrotuSkumulowaneFCFE[1-5]
     * Relacja skumulowane FCFE do CF0 = $klasycznyOkresZwrotuSkumulowaneFCFErelacja[0-6] :
     *                      [0]     - 0
     *                      [1-6]   - $klasycznyOkresZwrotuSkumulowaneFCFE[1-6]/$klasycznyOkresZwrotuSkumulowaneFCFE[0]
     */

    public function getKlasycznyOkresZwrotuSkumulowaneFCFE() {
        return $this->klasycznyOkresZwrotuSkumulowaneFCFE;
    }

    public function setKlasycznyOkresZwrotuSkumulowaneFCFE() {
        $this->klasycznyOkresZwrotuSkumulowaneFCFE[0] = $this->szacunekWartosciKapitaluWlasnegoSuma;
        $this->klasycznyOkresZwrotuSkumulowaneFCFE[1] = $this->przeplywyPieniezPrzynWlas[0];
        for ($i = 2; $i <= 6; $i++) {
            $this->klasycznyOkresZwrotuSkumulowaneFCFE[$i] = $this->przeplywyPieniezPrzynWlas[$i - 1] + $this->klasycznyOkresZwrotuSkumulowaneFCFE[$i - 1];
        }
    }

    public function getKlasycznyOkresZwrotuSkumulowaneFCFErelacja() {
        return $this->klasycznyOkresZwrotuSkumulowaneFCFErelacja;
    }

    public function setKlasycznyOkresZwrotuSkumulowaneFCFErelacja() {
        $this->klasycznyOkresZwrotuSkumulowaneFCFErelacja[0] = 0;
        for ($i = 1; $i <= 6; $i++) {
            $this->klasycznyOkresZwrotuSkumulowaneFCFErelacja[$i] = $this->klasycznyOkresZwrotuSkumulowaneFCFE[$i] / $this->klasycznyOkresZwrotuSkumulowaneFCFE[0];
        }
    }

    /* Zdyskontowany okres zwrotu */
    /******************************
     * Skumulowane zdyskontowane FCFE = $zdyskontowanyOkresZwrotuSkumulowaneFCFE[0-6] :
     *                      [0]     - $szacunekWartosciKapitaluWlasnegoSuma
     *                      [1]     - $szacunekWartosciKapitaluWlasnego[0]
     *                      [2-6]   - $szacunekWartosciKapitaluWlasnego[1-5] + $zdyskontowanyOkresZwrotuSkumulowaneFCFE[1-5]
     * Relacja skumulowane zdyskontowane FCFE do CF0 = $zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja[0-6] :
     *                      [0]     - 0
     *                      [1-6]   - $zdyskontowanyOkresZwrotuSkumulowaneFCFE[1-6]/$zdyskontowanyOkresZwrotuSkumulowaneFCFE[0]
     */

    public function getZdyskontowanyOkresZwrotuSkumulowaneFCFE() {
        return $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE;
    }

    public function setZdyskontowanyOkresZwrotuSkumulowaneFCFE() {
        $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[0] = $this->szacunekWartosciKapitaluWlasnegoSuma;
        $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[1] = $this->szacunekWartosciKapitaluWlasnego[0];
        for ($i = 2; $i <= 6; $i++) {
            $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[$i] = $this->szacunekWartosciKapitaluWlasnego[$i - 1] + $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[$i - 1];
        }
    }

    public function getZdyskontowanyOkresZwrotuSkumulowaneFCFErelacja() {
        return $this->zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja;
    }

    public function setZdyskontowanyOkresZwrotuSkumulowaneFCFErelacja() {
        $this->zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja[0] = 0;
        for ($i = 1; $i <= 6; $i++) {
            $this->zdyskontowanyOkresZwrotuSkumulowaneFCFErelacja[$i] = $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[$i] / $this->zdyskontowanyOkresZwrotuSkumulowaneFCFE[0];
        }
    }


    public function loadDataForBilansObject($file, $yearsTable, $form) {
        //Wczytuje plik EXCELA z danymi finansowymi firmy oraz ustawia aktywny arkusz z którego będzie czytał dane
        $excel = PHPExcel_IOFactory::load($file);
        $excel->setActiveSheetIndex(0);

        //Ustawia atrybut FIRMA - nazwa firmy z wsadowego pliku Excel
        $this->setFirma($excel);

        //Ustawia atrybut KRS - numer KRS firmy z wsadowego pliku Excel
        $this->setKRS($excel);

        //Ustawia atrybuty ROK z których będzie pobierał bilans firmy
        $this->setRok0($form['rok']);
        $this->setRok1($form['rok']);
        $this->setRok2($form['rok']);

        //Ustawia dane w atrybucie BilansTablica - dane finansowe firmy za wskazane lata
        $this->setBilansTablica($excel, $yearsTable);

        //Ustawia pozostałe atrybuty do bilansu finansowego firmy
        $this->setPrzychodyZeSprzedazy($excel, $yearsTable);
        $this->setKosztyDzialanosciOperacyjnej($excel, $yearsTable);
        $this->setWynagrodzenia($excel, $yearsTable);
        $this->setZyskStrataZeSprzedazy($excel, $yearsTable);
        $this->setPozostalePrzychodyOperacyjne($excel, $yearsTable);
        $this->setPozostaleKosztyOperacyjne($excel, $yearsTable);
        $this->setZyskStrataZDzialanosciOperacyjnej($excel, $yearsTable);
        $this->setPrzychodyFinansowe($excel, $yearsTable);
        $this->setKosztyFinansowe($excel, $yearsTable);
        $this->setZyskStrataZDzialalnosciGospodarczej($excel, $yearsTable);
        $this->setWynikZdarzenNadzwyczajnych($excel, $yearsTable);
        $this->setZyskBrutto($excel, $yearsTable);
        $this->setPodatekDochodowy($excel, $yearsTable);
        $this->setZyskNetto($excel, $yearsTable);
        $this->setAmortyzacja($excel, $yearsTable);

        //Ustawia atrybuty dla których dane pobiera z formularza
        $this->loadFormInformation($form);
    }


    public function calculateOthersData($wariant) {
        $this->umorzenieSrTrwalych = 0;
        $this->setSprzedazPierwszyRokPrognozy();
        $this->setKosztyOperacyjnePierwszyRokPrognozy();

        //Ustawia atrybut wartosc likwidacyjna
        $this->setWartoscLikwidacyjna();

        //Liczy: Tablice BILANS według algorytmów dla podanego wariantu
        $this->setBilansPrognozaTable($wariant);

        //Liczy: Tablice RACHUNEK KOSZTÓW według algorytmów dla podanego wariantu
        $this->setRachunekKosztowTable($wariant);

        //Liczy: Obliczenie przepływów pieniężnych dla właścicieli
        $this->setPrzyrostKapitaluPracujacego();
        $this->setPrzyrostZadluzeniaDl();
        $this->setPrzeplywyPieniezPrzynWlas();

        //Liczy: Zmiana stanu kapitału własnego
        $this->setBilansZamkniecia();

        //Liczy: Obliczenie przepływów pieniężnych przynależnych wszystkim stronom finansującym
        $this->setOdsetkiNetto();
        $this->setPrzepPienPrzynStFinan();

        //Liczy: Wyznaczenie kosztu kapitału własnego
        $this->setRazemKosztKapitaluWlasnego();

        //USTAWIA ATRYBUTY KTÓRE BĘDĄ WSTAWIANE DO RAPORTU
        //Liczy: Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom
        $this->setPrzeplywyPieniezPrzynWlasTerminalValue();
        $this->setSzacunekWartosciKapitaluWlasnego();
        $this->setSzacunekWartosciKapitaluWlasnegoTerminalValue();
        $this->setSzacunekWartosciKapitaluWlasnegoSuma();

        //Liczy: Wyznaczenie WACC
        $this->setRynkowaWartoscDluguProcent();
        $this->setRynkowaWartoscDluguKoszt();
        $this->setRynkowaWartoscDluguWACC();
        $this->setRynkowaWartoscKapitaluWlasnegoProcent();
        $this->setRynkowaWartoscKapitaluWlasnegoWACC();
        $this->setSredniWazonyKosztKapitalu();

        //Liczy: Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom i wierzycielom
        $this->setPrzepPienPrzynStFinanTerminalValue();
        $this->setSzacunekWartosciCalejFirmy();
        $this->setSzacunekWartosciCalejFirmyTerminalValue();
        $this->setSzacunekWartosciCalejFirmySuma();

        //Liczy: Obliczanie NPV projektu
        $this->setNPV();

        //Liczy: Obliczenie IRR wg rzeczywistej wartości PV (IRR do porównania z kosztem kapitału własnego)
        $this->setIRR_wgRzeczywistejWartosciPVTable();
        $this->setIRR_wgRzeczywistejWartosciPV();

        //Liczy: Obliczenie IRR wg ceny ofertowej (IRR do porównania z kosztem kapitału własnego)
        $this->setIRR_wgCenyOfertowejTable();
        $this->setIRR_wgCenyOfertowej();

        //Liczy: Klasyczny okres zwrotu
        $this->setKlasycznyOkresZwrotuSkumulowaneFCFE();
        $this->setKlasycznyOkresZwrotuSkumulowaneFCFErelacja();

        //Liczy: Zdyskontowany okres zwrotu
        $this->setZdyskontowanyOkresZwrotuSkumulowaneFCFE();
        $this->setZdyskontowanyOkresZwrotuSkumulowaneFCFErelacja();
    }

}