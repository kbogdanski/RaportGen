<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2020-03-04
 * Time: 21:25
 */
class DaneBranzowe {

    private $kod;       // Kod branży z której jest firma
    private $opis;      // Opis brażny z której jest firma

    private $wskaznikiNazwa;
    private $wskaznikRok;
    private $wskaznikFirmy;
    private $wskaznikBranzy;
    private $wskaznikIloscFirm;

    public function __construct() {
        $this->kod = null;
        $this->opis = null;
        $this->wskaznikiNazwa = null;
    }

    public function getKod() {
        return $this->kod;
    }

    private function setKod($kod) {
        $this->kod = iconv("UTF-8", "ISO-8859-2", $kod);
    }

    public function getOpis() {
        return $this->opis;
    }

    private function setOpis($opis) {
        $this->opis = iconv("UTF-8", "ISO-8859-2", $opis);
    }

    public function getWskaznikiNazwa() {
        return $this->wskaznikiNazwa;
    }

    private function setWskaznikiNazwa() {
        $this->wskaznikiNazwa = [
            'p' => 'przychody',
            'z' => 'zysk_netto',
            'dp' => 'dynamika_przychodow',
            'dz' => 'dynamika_zysku_netto',
            'rp' => 'rentownosc_przychodow',
            'pg' => 'plynnosc_gotowkowa',
            'roe' => 'roe',
            'roi' => 'roi'];
    }

    public function getWskaznikRok() {
        return $this->wskaznikRok;
    }

    private function setWskaznikRok($rok) {
        $this->wskaznikRok[] = (string)$rok;
    }

    public function getWskaznikFirmy() {
        return $this->wskaznikFirmy;
    }

    private function setWskaznikFirmy($rok, $nazwaWskaznika, $wartoscWskaznika) {
        if ($wartoscWskaznika != null) {
            if ($nazwaWskaznika == 'przychody' || $nazwaWskaznika == 'zysk_netto') {
                $wartoscWsk = (int)$wartoscWskaznika/1000000;
            } else {
                $wartoscWsk = (float)$wartoscWskaznika;
            }
            $this->wskaznikFirmy[$nazwaWskaznika][$rok] = round($wartoscWsk, 3);
        } else {
            $this->wskaznikFirmy[$nazwaWskaznika][$rok] = $wartoscWskaznika;
        }
    }

    public function getWskaznikBranzy() {
        return $this->wskaznikBranzy;
    }

    private function setWskaznikBranzy($rok, $nazwaWskaznika, $wartoscWskaznika) {
        if ($wartoscWskaznika != null) {
            if ($nazwaWskaznika == 'przychody' || $nazwaWskaznika == 'zysk_netto') {
                $wartoscWsk = (int)$wartoscWskaznika/1000000;
            } else {
                $wartoscWsk = (float)$wartoscWskaznika;
            }
            $this->wskaznikBranzy[$nazwaWskaznika][$rok] = round($wartoscWsk, 3);
        } else {
            $this->wskaznikBranzy[$nazwaWskaznika][$rok] = $wartoscWskaznika;
        }
    }

    public function getWskaznikIloscFirm() {
        return $this->wskaznikIloscFirm;
    }

    private function setWskaznikIloscFirm($rok, $nazwaWskaznika, $przychodyIloscFirm) {
        $this->wskaznikIloscFirm[$nazwaWskaznika][$rok] = (int)$przychodyIloscFirm;
    }

    private function loadLata($wskaznik) {
        foreach ($wskaznik->za_rok as $value) {
            $rok = (string)$value->rok;
            $this->setWskaznikRok($rok);
        }
    }

    private function loadWskaznik($wskaznik, $nazwaWskaznika) {
        foreach ($wskaznik->za_rok as $value) {
            $rok = (string)$value->rok;

            if (isset($value->wartosci_wskaznika->dla_firmy)) {
                $this->setWskaznikFirmy($rok, $nazwaWskaznika, $value->wartosci_wskaznika->dla_firmy);
            } else {
                $this->setWskaznikFirmy($rok, $nazwaWskaznika, null);
            }

            if (isset($value->wartosci_wskaznika->dla_branzy)) {
                $this->setWskaznikBranzy($rok, $nazwaWskaznika, $value->wartosci_wskaznika->dla_branzy);
            } else {
                $this->setWskaznikBranzy($rok, $nazwaWskaznika, null);
            }

            if (isset($value->wartosci_wskaznika->ilosc_firm_dla_wskaznika_w_branzy)) {
                $this->setWskaznikIloscFirm($rok, $nazwaWskaznika, $value->wartosci_wskaznika->ilosc_firm_dla_wskaznika_w_branzy);
            } else {
                $this->setWskaznikIloscFirm($rok, $nazwaWskaznika, null);
            }
        }
    }

    public function loadDataFromXLMRaport($xml, $krs) {
        $this->setWskaznikiNazwa();
        $reader = new XMLReader();
        $reader->xml($xml);
        $doc = new DOMDocument();

        while ($reader->read() !== FALSE) {
            if ($reader->name === 'pozycja' && $reader->getAttribute('schemat_pkd') === 'PKD2007' && $reader->nodeType === XMLReader::ELEMENT) {
                $node = simplexml_import_dom($doc->importNode($reader->expand(), true));
                $this->setKod($node->kod);
                $this->setOpis($node->opis);
            }

            if ($reader->name === 'AnalizaBranzowa' && $reader->getAttribute('krs') === "$krs" && $reader->nodeType === XMLReader::ELEMENT) {
                $node = simplexml_import_dom($doc->importNode($reader->expand(), true));
                $wskazniki = $node->children()->children();
                $this->loadLata($wskazniki->przychody);
                $this->loadWskaznik($wskazniki->przychody, 'przychody');
                $this->loadWskaznik($wskazniki->zysk_netto, 'zysk_netto');
                $this->loadWskaznik($wskazniki->dynamika_przychody, 'dynamika_przychodow');
                $this->loadWskaznik($wskazniki->dynamika_zysk_netto, 'dynamika_zysku_netto');
                $this->loadWskaznik($wskazniki->anl_zysk_przych, 'rentownosc_przychodow');
                $this->loadWskaznik($wskazniki->plynnosc_fin_wsk_gotow, 'plynnosc_gotowkowa');
                $this->loadWskaznik($wskazniki->anl_zysk_roe, 'roe');
                $this->loadWskaznik($wskazniki->anl_zysk_roi, 'roi');

                //var_dump($this->wskaznikRok);
                //var_dump($this->wskaznikFirmy);
                //var_dump($this->wskaznikBranzy);
                //var_dump($this->wskaznikIloscFirm);
            }
        }
    }



}