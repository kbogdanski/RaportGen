<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-03-18
 * Time: 21:00
 */
class Wskaznik {

    // Static REPOSITORY methods
    static public function CreateBilansTabelYear($file, $rok) {
        $rokBazowy = $rok;
        $status = 0;
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
            if ($value == $rokBazowy) {
                $status = 1;
                $toReturn["B"] = $value;
            }
            while ($value != null && $col <= 26 && $status != 5 ) {
                $value = $excel->getActiveSheet()->getCell("$COLUMN[$col]7")->getValue();
                $toReturn["$COLUMN[$col]"] = $value;
                $status++;
                $col++;
            }
        }
        if ($status == 5) {
            return $toReturn;
        } else {
            return 0;
        }
    }

    static public function CreateWskaznik($excelFile, $yearsTable) {
        $excel = PHPExcel_IOFactory::load($excelFile);
        $excel->setActiveSheetIndex(0);
        $newWskaznik = new Wskaznik();
        $newWskaznik->setYearsTabel($yearsTable);
        $newWskaznik->setAktywaTrwale($excel, $yearsTable);
        $newWskaznik->setRzeczoweAktywaTrwale($excel, $yearsTable);
        $newWskaznik->setSrodkiTrwale($excel, $yearsTable);
        $newWskaznik->setAktywaObrotowe($excel, $yearsTable);
        $newWskaznik->setZapasy($excel, $yearsTable);
        $newWskaznik->setNaleznosciKrotkoterminowe($excel, $yearsTable);
        $newWskaznik->setNaleznosciOdPozostalychJedn($excel, $yearsTable);
        $newWskaznik->setInwestycjeKrotkoterminowe($excel, $yearsTable);
        $newWskaznik->setKapitalWlasny($excel, $yearsTable);
        $newWskaznik->setZobowiazania($excel, $yearsTable);
        $newWskaznik->setZobowiazaniaDlugoterminowe($excel, $yearsTable);
        $newWskaznik->setZobowiazaniaKrotkoterminowe($excel, $yearsTable);
        $newWskaznik->setPasywaRazem($excel, $yearsTable);
        $newWskaznik->setPrzychodyNetto($excel, $yearsTable);
        $newWskaznik->setKosztyDzialanosciOperacyjnej($excel, $yearsTable);
        $newWskaznik->setZyskBrutto($excel, $yearsTable);
        $newWskaznik->setZyskNetto($excel, $yearsTable);


        return $newWskaznik;
    }

    //ATTRIBUTES
    private $yearsTabel;                    // TABLICA[5-elementowa] Zawiera lata z ktorych są wybrane dane finansowe firmy
    private $aktywaTrwale;                  // TABLICA[5-elementowa] Wiersz 11           Aktywa trwałe
    private $rzeczoweAktywaTrwale;          // TABLICA[5-elementowa] Wiersz 17           Rzeczowe aktywa trwałe
    private $srodkiTrwale;                  // TABLICA[5-elementowa] Wiersz 18           Środki trwałe
    private $aktywaObrotowe;                // TABLICA[5-elementowa] Wiersz 47           Aktywa obrotowe
    private $zapasy;                        // TABLICA[5-elementowa] Wiersz 48           Zapasy
    private $naleznosciKrotkoterminowe;     // TABLICA[5-elementowa] Wiersz 54           Należności krótkoterminowe
    private $naleznosciOdPozostalychJedn;   // TABLICA[5-elementowa] Wiersz 60           Należności od pozostałych jednostek
    private $inwestycjeKrotkoterminowe;     // TABLICA[5-elementowa] Wiersz 67           Inwestycje krótkoterminowe
    private $kapitalWlasny;                 // TABLICA[5-elementowa] Wiersz 86           Kapitał (fundusz) własny
    private $zobowiazania;                  // TABLICA[5-elementowa] Wiersz 96           Zobowiązania i rezerwy na zobowiązania
    private $zobowiazaniaDlugoterminowe;    // TABLICA[5-elementowa] Wiersz 105          Zobowiązania długoterminowe
    private $zobowiazaniaKrotkoterminowe;   // TABLICA[5-elementowa] Wiersz 112          Zobowiązania krótkoterminowe
    private $pasywaRazem;                   // TABLICA[5-elementowa] Wiersz 139          Pasywa razem
    private $przychodyNetto;                // TABLICA[5-elementowa] Wiersz 145 + 193    Przychody netto ze sprzedaży
    private $kosztyDzialanosciOperacyjnej;  // TABLICA[5-elementowa] Wiersz 149 + 199    Koszty działalności operacyjnej
    private $zyskBrutto;                    // TABLICA[5-elementowa] Wiersz 184 + 237    Zysk (strata) brutto (L-+M)
    private $zyskNetto;                     // TABLICA[5-elementowa] Wiersz 187 + 240    Zysk (strata) netto (N-O-P)

    /* Wskaźniki płynności finansowej */
    private $wskPlynnosciBiezacej;          // TABLICA[5-elementowa] Wskaźnik płynności bieżącej
    private $wslPlynnosciSzybkiej;          // TABLICA[5-elementowa] Wskaźnik płynności szybkiej
    private $wskPlynnosciGotowka;           // TABLICA[5-elementowa] Wskaźnik płynności gotówką

    /* Wskaźniki sprawności */
    private $rotacjaNaleznosciWrazach;      // TABLICA[5-elementowa] Rotacji należności w razach
    private $rotacjaNaleznosciWdniach;      // TABLICA[5-elementowa] Rotacji należności w dniach
    private $rotacjaZobowiazanWrazach;      // TABLICA[5-elementowa] Rotacji zobowiązań w razach
    private $rotacjaZobowiazanWdniach;      // TABLICA[5-elementowa] Rotacji zobowiązań w dniach
    private $rotacjaZapasowWrazach;         // TABLICA[5-elementowa] Rotacji zapasów w razach
    private $rotacjaZapasowWdniach;         // TABLICA[5-elementowa] Rotacji zapasów w dniach

    /* Wskaźniki rentowności */
    private $ROI;                           // TABLICA[5-elementowa] ROI
    private $ROE;                           // TABLICA[5-elementowa] ROE
    private $zyskownoscPrzychodow;          // TABLICA[5-elementowa] Zyskowność przychodów

    /* Wskaźniki zadłużenia i pokrycia */
    private $pokrycieAktywow;               // TABLICA[5-elementowa] Pokrycia aktywów
    private $zadluzenieOgolne;              // TABLICA[5-elementowa] Zadłużenia ogólnego
    private $pokrycieMajatkuTrwalego;       // TABLICA[5-elementowa] Pokrycia majątku trwałego




    /**
     * @return mixed
     */
    public function getYearsTabel() {
        return $this->yearsTabel;
    }

    /**
     * @param mixed $yearsTabel
     */
    public function setYearsTabel($yearsTabel) {
        $this->yearsTabel = $yearsTabel;
    }

    /**
     * @return array
     */
    public function getAktywaTrwale() {
        return $this->aktywaTrwale;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setAktywaTrwale($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 11 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "11")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->aktywaTrwale = $toReturn;
    }

    /**
     * @return array
     */
    public function getRzeczoweAktywaTrwale() {
        return $this->rzeczoweAktywaTrwale;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setRzeczoweAktywaTrwale($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 17 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "17")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->rzeczoweAktywaTrwale = $toReturn;
    }

    /**
     * @return array
     */
    public function getSrodkiTrwale() {
        return $this->srodkiTrwale;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setSrodkiTrwale($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 18 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "18")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->srodkiTrwale = $toReturn;
    }

    /**
     * @return array
     */
    public function getAktywaObrotowe() {
        return $this->aktywaObrotowe;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setAktywaObrotowe($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 47 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "47")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->aktywaObrotowe = $toReturn;
    }

    /**
     * @return array
     */
    public function getZapasy() {
        return $this->zapasy;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZapasy($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 48 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "48")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->zapasy = $toReturn;
    }

    /**
     * @return array
     */
    public function getNaleznosciKrotkoterminowe() {
        return $this->naleznosciKrotkoterminowe;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setNaleznosciKrotkoterminowe($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 54 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "54")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->naleznosciKrotkoterminowe = $toReturn;
    }

    /**
     * @return array
     */
    public function getNaleznosciOdPozostalychJedn() {
        return $this->naleznosciOdPozostalychJedn;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setNaleznosciOdPozostalychJedn($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 60 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "60")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->naleznosciOdPozostalychJedn = $toReturn;
    }

    /**
     * @return array
     */
    public function getInwestycjeKrotkoterminowe() {
        return $this->inwestycjeKrotkoterminowe;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setInwestycjeKrotkoterminowe($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 67 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "67")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->inwestycjeKrotkoterminowe = $toReturn;
    }

    /**
     * @return array
     */
    public function getKapitalWlasny() {
        return $this->kapitalWlasny;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setKapitalWlasny($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 86 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "86")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->kapitalWlasny = $toReturn;
    }

    /**
     * @return array
     */
    public function getZobowiazania() {
        return $this->zobowiazania;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZobowiazania($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 96 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "96")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->zobowiazania = $toReturn;
    }

    /**
     * @return array
     */
    public function getZobowiazaniaDlugoterminowe() {
        return $this->zobowiazaniaDlugoterminowe;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZobowiazaniaDlugoterminowe($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 105 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "105")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->zobowiazaniaDlugoterminowe = $toReturn;
    }

    /**
     * @return array
     */
    public function getZobowiazaniaKrotkoterminowe() {
        return $this->zobowiazaniaKrotkoterminowe;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZobowiazaniaKrotkoterminowe($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 112 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "112")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->zobowiazaniaKrotkoterminowe = $toReturn;
    }

    /**
     * @return array
     */
    public function getPasywaRazem() {
        return $this->pasywaRazem;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setPasywaRazem($excelFile, $yearsTable) {
        /* wiersze w pliku Excel 139 */
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "139")->getValue();
                if ($value != null) {
                    $toReturn[] = $value;
                } else {
                    $toReturn[] = 0.00;
                }
            }
        }
        $this->pasywaRazem = $toReturn;
    }

    /**
     * @return array
     */
    public function getPrzychodyNetto() {
        return $this->przychodyNetto;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setPrzychodyNetto($excelFile, $yearsTable) {
        //wiersz w pliku Excel 145 lub 193
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
        $this->przychodyNetto = $toReturn;
    }

    /**
     * @return array
     */
    public function getKosztyDzialanosciOperacyjnej() {
        return $this->kosztyDzialanosciOperacyjnej;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setKosztyDzialanosciOperacyjnej($excelFile, $yearsTable) {
        //wiersz w pliku Excel 149 lub 199
        $toReturn = [];
        if ($excelFile != false) {
            foreach ($yearsTable as $key => $year) {
                $value = $excelFile->getActiveSheet()->getCell("$key" . "149")->getValue();
                if ($value == null) {
                    $value = $excelFile->getActiveSheet()->getCell("$key" . "199")->getValue();
                }
                if ($value == null) {
                    $value = 0.00;
                }
                $toReturn[] = $value;
            }
        }
        $this->kosztyDzialanosciOperacyjnej = $toReturn;
    }

    /**
     * @return array
     */
    public function getZyskBrutto() {
        return $this->zyskBrutto;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZyskBrutto($excelFile, $yearsTable) {
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
     * @return array
     */
    public function getZyskNetto() {
        return $this->zyskNetto;
    }

    /**
     * @param mixed $excelFile, $yearsTable
     */
    public function setZyskNetto($excelFile, $yearsTable) {
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
     * @return array
     */
    public function getWskPlynnosciBiezacej() {
        return $this->wskPlynnosciBiezacej;
    }

    /**
     * $wskPlynnosciBiezacej[i] = $aktywaObrotowe[i] / $zobowiazaniaKrotkoterminowe[i]
     */
    public function setWskPlynnosciBiezacej() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->aktywaObrotowe[$i] / $this->zobowiazaniaKrotkoterminowe[$i];
        }
        $this->wskPlynnosciBiezacej = $toReturn;
    }

    /**
     * @return array
     */
    public function getWslPlynnosciSzybkiej() {
        return $this->wslPlynnosciSzybkiej;
    }

    /**
     * $wslPlynnosciSzybkiej = ($naleznosciKrotkoterminowe[i] + $inwestycjeKrotkoterminowe[i]) / $zobowiazaniaKrotkoterminowe[i]
     */
    public function setWslPlynnosciSzybkiej() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = ($this->naleznosciKrotkoterminowe[$i] + $this->inwestycjeKrotkoterminowe[$i]) / $this->zobowiazaniaKrotkoterminowe[$i];
        }
        $this->wslPlynnosciSzybkiej = $toReturn;
    }

    /**
     * @return array
     */
    public function getWskPlynnosciGotowka() {
        return $this->wskPlynnosciGotowka;
    }

    /**
     * $wskPlynnosciGotowka = $inwestycjeKrotkoterminowe[i]) / $zobowiazaniaKrotkoterminowe[i]
     */
    public function setWskPlynnosciGotowka() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->inwestycjeKrotkoterminowe[$i] / $this->zobowiazaniaKrotkoterminowe[$i];
        }
        $this->wskPlynnosciGotowka = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaNaleznosciWrazach() {
        return $this->rotacjaNaleznosciWrazach;
    }

    /**
     * $rotacjaNaleznosciWrazach = $przychodyNetto[i] / $naleznosciKrotkoterminowe[i]
     */
    public function setRotacjaNaleznosciWrazach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->przychodyNetto[$i] / $this->naleznosciKrotkoterminowe[$i];
        }
        $this->rotacjaNaleznosciWrazach = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaNaleznosciWdniach() {
        return $this->rotacjaNaleznosciWdniach;
    }

    /**
     * $rotacjaNaleznosciWdniach = $naleznosciOdPozostalychJedn[i] / $przychodyNetto[i] * 360
     */
    public function setRotacjaNaleznosciWdniach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->naleznosciOdPozostalychJedn[$i] / $this->przychodyNetto[$i] * 360;
        }
        $this->rotacjaNaleznosciWdniach = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaZobowiazanWrazach() {
        return $this->rotacjaZobowiazanWrazach;
    }

    /**
     * $rotacjaZobowiazanWrazach = $przychodyNetto[i] / $zobowiazaniaKrotkoterminowe[i]
     */
    public function setRotacjaZobowiazanWrazach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->przychodyNetto[$i] / $this->zobowiazaniaKrotkoterminowe[$i];
        }
        $this->rotacjaZobowiazanWrazach = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaZobowiazanWdniach() {
        return $this->rotacjaZobowiazanWdniach;
    }

    /**
     * $rotacjaZobowiazanWdniach = $zobowiazaniaKrotkoterminowe[i] / $przychodyNetto[i] * 360
     */
    public function setRotacjaZobowiazanWdniach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->zobowiazaniaKrotkoterminowe[$i] / $this->przychodyNetto[$i] * 360;
        }
        $this->rotacjaZobowiazanWdniach = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaZapasowWrazach() {
        return $this->rotacjaZapasowWrazach;
    }

    /**
     * $rotacjaZapasowWrazach = $przychodyNetto[i] / $zapasy[i]
     */
    public function setRotacjaZapasowWrazach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->przychodyNetto[$i] / $this->zapasy[$i];
        }
        $this->rotacjaZapasowWrazach = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getRotacjaZapasowWdniach() {
        return $this->rotacjaZapasowWdniach;
    }

    /**
     * $rotacjaZapasowWdniach = $zapasy[i] / $przychodyNetto[i] * 360
     */
    public function setRotacjaZapasowWdniach() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->zapasy[$i] / $this->przychodyNetto[$i] * 360;
        }
        $this->rotacjaZapasowWdniach = $toReturn;
    }

    /**
     * @return array
     */
    public function getROI() {
        return $this->ROI;
    }

    /**
     * $ROI = $zyskNetto[i] / $pasywaRazem[i]
     */
    public function setROI() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->zyskNetto[$i] / $this->pasywaRazem[$i];
        }
        $this->ROI = $toReturn;
    }

    /**
     * @return array
     */
    public function getROE() {
        return $this->ROE;
    }

    /**
     * $ROE = $zyskNetto[i] / $kapitalWlasny[i]
     */
    public function setROE() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->zyskNetto[$i] / $this->kapitalWlasny[$i];
        }
        $this->ROE = $toReturn;
    }

    /**
     * @return array
     */
    public function getZyskownoscPrzychodow() {
        return $this->zyskownoscPrzychodow;
    }

    /**
     * $zyskownoscPrzychodow = $zyskNetto[i] / $przychodyNetto[i]
     */
    public function setZyskownoscPrzychodow() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->zyskNetto[$i] / $this->przychodyNetto[$i];
        }
        $this->zyskownoscPrzychodow = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getPokrycieAktywow() {
        return $this->pokrycieAktywow;
    }

    /**
     * $pokrycieAktywow = $pasywaRazem / $kapitalWlasny
     */
    public function setPokrycieAktywow() {
        $toReturn = [];
        for($i=0; $i<5; $i++) {
            $toReturn[] = $this->pasywaRazem[$i] / $this->kapitalWlasny[$i];
        }
        $this->pokrycieAktywow = $toReturn;
    }

    /**
     * @return mixed
     */
    public function getZadluzenieOgolne()
    {
        return $this->zadluzenieOgolne;
    }

    /**
     * @param mixed $zadluzenieOgolne
     */
    public function setZadluzenieOgolne($zadluzenieOgolne)
    {
        $this->zadluzenieOgolne = $zadluzenieOgolne;
    }

    /**
     * @return mixed
     */
    public function getPokrycieMajatkuTrwalego()
    {
        return $this->pokrycieMajatkuTrwalego;
    }

    /**
     * @param mixed $pokrycieMajatkuTrwalego
     */
    public function setPokrycieMajatkuTrwalego($pokrycieMajatkuTrwalego)
    {
        $this->pokrycieMajatkuTrwalego = $pokrycieMajatkuTrwalego;
    }






    //FUNCTIONS
    public function __construct() {
        $this->yearsTabel = [];
        $this->aktywaTrwale = [];
        $this->rzeczoweAktywaTrwale = [];
        $this->srodkiTrwale = [];
        $this->aktywaObrotowe = [];
        $this->zapasy = [];
        $this->naleznosciKrotkoterminowe = [];
        $this->naleznosciOdPozostalychJedn = [];
        $this->inwestycjeKrotkoterminowe = [];
        $this->kapitalWlasny = [];
        $this->zobowiazania = [];
        $this->zobowiazaniaDlugoterminowe = [];
        $this->zobowiazaniaKrotkoterminowe = [];
        $this->pasywaRazem = [];
        $this->przychodyNetto = [];
        $this->kosztyDzialanosciOperacyjnej = [];
        $this->zyskBrutto = [];
        $this->zyskNetto = [];
        $this->wskPlynnosciBiezacej = [];
        $this->wslPlynnosciSzybkiej = [];
        $this->wskPlynnosciGotowka = [];
        $this->rotacjaNaleznosciWrazach = [];
        $this->rotacjaNaleznosciWdniach = [];
        $this->rotacjaZobowiazanWrazach = [];
        $this->rotacjaZobowiazanWdniach = [];
        $this->rotacjaZapasowWrazach = [];
        $this->rotacjaZapasowWdniach = [];
        $this->ROI = [];
        $this->ROE = [];
        $this->zyskownoscPrzychodow = [];
    }



}