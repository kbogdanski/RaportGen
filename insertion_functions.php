<?php
/**
 * Created by PhpStorm.
 * Funkcje wstawiajace dane do raportu
 * User: Kamil
 * Date: 2019-02-19
 * Time: 19:52
 */

//Wstawiam nazwę firmy do raportu
function insertNazwaFirmy($templateWord, $bilans) {
    $templateWord->setValue('firma', $bilans->getFirma());
}

//Wstawiam lata do raportu
function insertYears($templateWord, $bilans, $yearsTableforWskaznik) {
    $templateWord->setValue('B0', $bilans->getRok0());
    $templateWord->setValue('B1', $bilans->getRok1());
    $templateWord->setValue('B2', $bilans->getRok2());
    $templateWord->setValue('B3', $yearsTableforWskaznik[3]);
    $templateWord->setValue('B4', $yearsTableforWskaznik[4]);
    for($i=1; $i<=6; $i++) {
        $templateWord->setValue('B0+'.$i, ((int)($bilans->getRok0()) + $i));
    }
}

/* Wstawiam wartość likwidacyjna */
function insertWartoscLikwidacyjna($templateWord, $bilans) {
    $value = $bilans->getWartoscLikwidacyjna();
    $templateWord->setValue('wartoscLikwidacyjna', number_format(round($value),0,',',' '));
}

/* Wstawiam wartość szacowaną moetodą DCF*/
function insertWartoscDCF($templateWord, $wartoscDCF) {
    $templateWord->setValue('wartoscDCF', number_format(round($wartoscDCF),0,',',' '));
}

/* Wstawiam założenia do wyceny DCF - dane z formularza */
function insertZalozeniaDoWycenyDCF($templateWord, $bilans) {
    $templateWord->setValue('srOprZadlDl',number_format(($bilans->getSrOprZadlDl()*100),1,',',' '));
    $templateWord->setValue('stopaPodDoch',number_format(($bilans->getStopaPodDoch()*100),1,',',' '));
    $templateWord->setValue('stopaDyskontowa',number_format(($bilans->getStopaDyskontowa()*100),2,',',' '));
    $templateWord->setValue('premiaRynkowaRyzyka',number_format(($bilans->getPremiaRynkowaRyzyka()*100),1,',',' '));
    $templateWord->setValue('wspBeta', number_format($bilans->getWspBeta(),2,',',' '));
    $templateWord->setValue('premiaWielkosci',round($bilans->getPremiaWielkosci()*100));
    $templateWord->setValue('premiaRyzykaSpec',round($bilans->getPremiaRyzykaSpec()*100));
}

/* Wstawiam bilans firmy */
function insertBilans($templateWord, $bilans) {
    $index = 0;
    $bilansTable = $bilans->getBilansTablica();
    foreach ($bilansTable as $key => $year) {
        foreach ($year as $row => $value) {
            $templateWord->setValue('B'.$index.'_'.$row, number_format($value, 2, ',', ' '));
        }
        $index++;
    }
}

/* Wstawiam pozostałe dane bilansu firmy */
function insertBilansOtherData ($templateWord, $bilans) {
    foreach ($bilans->getPrzychodyZeSprzedazy() as $key => $value) {
        $templateWord->setValue('B' . $key . '_PrzyZeSprz', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getKosztyDzialanosciOperacyjnej() as $key => $value) {
        $templateWord->setValue('B' . $key . '_KosztOper', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getWynagrodzenia() as $key => $value) {
        $templateWord->setValue('B' . $key . '_Wynagro', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getZyskStrataZeSprzedazy() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZyskStrSprz', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getPozostalePrzychodyOperacyjne() as $key => $value) {
        $templateWord->setValue('B' . $key . '_PrzyOper', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getPozostaleKosztyOperacyjne() as $key => $value) {
        $templateWord->setValue('B' . $key . '_PoKosztOper', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getZyskStrataZDzialanosciOperacyjnej() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZyskStrOper', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getPrzychodyFinansowe() as $key => $value) {
        $templateWord->setValue('B' . $key . '_PrzyFinans', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getKosztyFinansowe() as $key => $value) {
        $templateWord->setValue('B' . $key . '_KosztFinans', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getZyskStrataZDzialalnosciGospodarczej() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZyskStrGosp', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getWynikZdarzenNadzwyczajnych() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZdarzNadz', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getZyskBrutto() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZyskBrutto', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getPodatekDochodowy() as $key => $value) {
        $templateWord->setValue('B' . $key . '_PodatekDoch', number_format($value, 2, ',', ' '));
    }
    foreach ($bilans->getZyskNetto() as $key => $value) {
        $templateWord->setValue('B' . $key . '_ZyskNetto', number_format($value, 2, ',', ' '));
    }
}

/*Wstawianie stopy wzrostu zmiennych*/
function insertStopyWzrostu($templateWord, $bilans) {
    for($i=1; $i<=2; $i++) {
        foreach($bilans->getOczekiwanaStopaWzrostu()[$i] as $key => $value) {
            $templateWord->setValue('W'.$i.'_'.$key,number_format(($value*100),1,',',' '));
        }
    }
}

/* Wstawiam dane do raportu do rozdziałów z wyceną - 3 warianty  */
/* 0 - WARIANT ZEROWY               */
/* 1 - WARIANT BRANŻOWY             */
/* 2 - WARIANT ŚREDNIEJ DYNAMIKI    */
/************************************/
/* Obliczenie wart. kap. wł. dyskontowanie przepływów przynależnych właścicielom */
function insertPrzeplywyPieniezPrzynWlas($templateWord, $bilans, $wariant) {
    $templateWord->setValue('W'.$wariant.'_RKKW',round($bilans->getRazemKosztKapitaluWlasnego()*100));
    foreach($bilans->getPrzeplywyPieniezPrzynWlas() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T11_'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T11_TV',number_format(round($bilans->getPrzeplywyPieniezPrzynWlasTerminalValue()),0,',',' '));

    $templateWord->setValue('W'.$wariant.'_SWKW',number_format(round($bilans->getSzacunekWartosciKapitaluWlasnegoSuma()),0,',',' '));
    foreach($bilans->getSzacunekWartosciKapitaluWlasnego() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T12_'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T12_TV',number_format(round($bilans->getSzacunekWartosciKapitaluWlasnegoTerminalValue()),0,',',' '));
}

/* Obliczenie wart. kap. wł. dyskontowanie przepływów przynaleznych właścicielom i wierzycielom */
function insertPrzeplywyPieniezPrzynWlasWierz($templateWord, $bilans, $wariant) {
    $templateWord->setValue('W'.$wariant.'_SWKK',number_format(($bilans->getSredniWazonyKosztKapitalu()*100),2,',',' '));
    foreach($bilans->getPrzepPienPrzynStFinan() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T21_'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T21_TV',number_format(round($bilans->getPrzepPienPrzynStFinanTerminalValue()),0,',',' '));

    $templateWord->setValue('W'.$wariant.'_SWCF',number_format(round($bilans->getSzacunekWartosciCalejFirmySuma()),0,',',' '));
    foreach($bilans->getSzacunekWartosciCalejFirmy() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T22_'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T22_TV',number_format(round($bilans->getSzacunekWartosciCalejFirmyTerminalValue()),0,',',' '));

    $templateWord->setValue('W'.$wariant.'_RWD',number_format(round($bilans->getBilansTablica()[$bilans->getRok0()][105]),0,',',' '));
}

/* Obliczenie IRR wg rzeczywistej wartości PV (IRR do porównania z kosztem kapitału własnego) */
function insertIRR_wgRzeczywistejWartosci($templateWord, $bilans, $wariant) {
    foreach($bilans->getIRR_wgRzeczywistejWartosciPVTable() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T3_CF'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T3_IRR',number_format(round($bilans->getIRR_wgRzeczywistejWartosciPV(),2),2,',',' '));
}

/* Obliczenie IRR wg ceny ofertowej (IRR do porównania z kosztem kapitału własnego) */
function insertIRR_wgCenyOfertowej($templateWord, $bilans, $wariant) {
    foreach($bilans->getIRR_wgCenyOfertowejTable() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T4_CF'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T4_IRR',number_format(round($bilans->getIRR_wgCenyOfertowej(),2),2,',',' '));
}

/* Wyznaczenie WACC */
function insertWACC($templateWord, $bilans, $wariant) {
    $templateWord->setValue('W'.$wariant.'_RWDP',number_format(($bilans->getRynkowaWartoscDluguProcent()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWDK',number_format(($bilans->getRynkowaWartoscDluguKoszt()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWD_WACC',number_format(($bilans->getRynkowaWartoscDluguWACC()*100),2,',',' '));

    $templateWord->setValue('W'.$wariant.'_RWKWP',number_format(($bilans->getRynkowaWartoscKapitaluWlasnegoProcent()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWKW_WACC',number_format(($bilans->getRynkowaWartoscKapitaluWlasnegoWACC()*100),2,',',' '));
}

/* Klasyczny okres zwrotu */
function insertKlasycznyOkresZwrotu($templateWord, $bilans, $wariant) {
    foreach($bilans->getKlasycznyOkresZwrotuSkumulowaneFCFE() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T61_CF'.$key, number_format(round($value),0,',',' '));
    }
    foreach($bilans->getKlasycznyOkresZwrotuSkumulowaneFCFErelacja() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T62_CF'.$key, number_format($value,2,',',' '));
    }
}

/* Zdyskontowany okres zwrotu */
function insertZdyskontowanyOkresZwrotu($templateWord, $bilans, $wariant) {
    foreach($bilans->getZdyskontowanyOkresZwrotuSkumulowaneFCFE() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T71_CF'.$key, number_format(round($value),0,',',' '));
    }
    foreach($bilans->getZdyskontowanyOkresZwrotuSkumulowaneFCFErelacja() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T72_CF'.$key, number_format($value,2,',',' '));
    }
}

/* Wstawiam wszystkie dane wariantowe */
function insertDaneDlaWariantu($templateWord, $bilans, $wariant) {
    insertPrzeplywyPieniezPrzynWlas($templateWord, $bilans, $wariant);
    insertPrzeplywyPieniezPrzynWlasWierz($templateWord, $bilans, $wariant);
    insertIRR_wgRzeczywistejWartosci($templateWord, $bilans, $wariant);
    insertIRR_wgCenyOfertowej($templateWord, $bilans, $wariant);
    insertWACC($templateWord, $bilans, $wariant);
    insertKlasycznyOkresZwrotu($templateWord, $bilans, $wariant);
    insertZdyskontowanyOkresZwrotu($templateWord, $bilans, $wariant);
}