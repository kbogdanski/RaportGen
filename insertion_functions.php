<?php
/**
 * Created by PhpStorm.
 * Funkcje wstawiajace dane do raportu
 * User: Kamil
 * Date: 2019-02-19
 * Time: 19:52
 */

//Wstawiam nazwę firmy do raportu
function insertNazwaFirmy(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
    $templateWord->setValue('firma', $bilans->getFirma());
}

//Wstawiam lata do raportu
function insertYears(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, Wskaznik $wskaznik) {
    $templateWord->setValue('B0', $bilans->getRok0());
    $templateWord->setValue('B1', $bilans->getRok1());
    $templateWord->setValue('B2', $bilans->getRok2());
    $i = 0;
    foreach($wskaznik->getYearsTabel() as $value) {
        if ($i > 2) {
            $templateWord->setValue('B'.$i, $value);
        }
        $i++;
    }
    for($i=1; $i<=6; $i++) {
        $templateWord->setValue('B0+'.$i, ((int)($bilans->getRok0()) + $i));
    }
}

/* Wstawiam wartość likwidacyjna */
function insertWartoscLikwidacyjna(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
    $value = $bilans->getWartoscLikwidacyjna();
    $templateWord->setValue('wartoscLikwidacyjna', number_format(round($value),0,',',' '));
}

/* Wstawiam wartość szacowaną moetodą DCF*/
function insertWartoscDCF(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $wartoscDCF) {
    $templateWord->setValue('wartoscDCF', number_format(round($wartoscDCF),0,',',' '));
}

/* Wstawiam założenia do wyceny DCF - dane z formularza */
function insertZalozeniaDoWycenyDCF(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
    $templateWord->setValue('srOprZadlDl',number_format(($bilans->getSrOprZadlDl()*100),1,',',' '));
    $templateWord->setValue('stopaPodDoch',number_format(($bilans->getStopaPodDoch()*100),1,',',' '));
    $templateWord->setValue('stopaDyskontowa',number_format(($bilans->getStopaDyskontowa()*100),2,',',' '));
    $templateWord->setValue('premiaRynkowaRyzyka',number_format(($bilans->getPremiaRynkowaRyzyka()*100),1,',',' '));
    $templateWord->setValue('wspBeta', number_format($bilans->getWspBeta(),2,',',' '));
    $templateWord->setValue('premiaWielkosci',round($bilans->getPremiaWielkosci()*100));
    $templateWord->setValue('premiaRyzykaSpec',round($bilans->getPremiaRyzykaSpec()*100));
}

/* Wstawiam bilans firmy */
function insertBilans(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
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
function insertBilansOtherData (\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
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
function insertStopyWzrostu(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans) {
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
function insertPrzeplywyPieniezPrzynWlas(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
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
function insertPrzeplywyPieniezPrzynWlasWierz(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
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
function insertIRR_wgRzeczywistejWartosci(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
    foreach($bilans->getIRR_wgRzeczywistejWartosciPVTable() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T3_CF'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T3_IRR',number_format(round($bilans->getIRR_wgRzeczywistejWartosciPV(),2),2,',',' '));
}

/* Obliczenie IRR wg ceny ofertowej (IRR do porównania z kosztem kapitału własnego) */
function insertIRR_wgCenyOfertowej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
    foreach($bilans->getIRR_wgCenyOfertowejTable() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T4_CF'.$key, number_format(round($value),0,',',' '));
    }
    $templateWord->setValue('W'.$wariant.'_T4_IRR',number_format(round($bilans->getIRR_wgCenyOfertowej(),2),2,',',' '));
}

/* Wyznaczenie WACC */
function insertWACC(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
    $templateWord->setValue('W'.$wariant.'_RWDP',number_format(($bilans->getRynkowaWartoscDluguProcent()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWDK',number_format(($bilans->getRynkowaWartoscDluguKoszt()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWD_WACC',number_format(($bilans->getRynkowaWartoscDluguWACC()*100),2,',',' '));

    $templateWord->setValue('W'.$wariant.'_RWKWP',number_format(($bilans->getRynkowaWartoscKapitaluWlasnegoProcent()*100),2,',',' '));
    $templateWord->setValue('W'.$wariant.'_RWKW_WACC',number_format(($bilans->getRynkowaWartoscKapitaluWlasnegoWACC()*100),2,',',' '));
}

/* Klasyczny okres zwrotu */
function insertKlasycznyOkresZwrotu(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
    foreach($bilans->getKlasycznyOkresZwrotuSkumulowaneFCFE() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T61_CF'.$key, number_format(round($value),0,',',' '));
    }
    foreach($bilans->getKlasycznyOkresZwrotuSkumulowaneFCFErelacja() as $key => $value) {
        $templateWord->setValue('W'.$wariant.'_T62_CF'.$key, number_format($value,2,',',' '));
    }
}

/* Zdyskontowany okres zwrotu */
function insertZdyskontowanyOkresZwrotu(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Bilans $bilans, $wariant) {
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

/* Wstawiam wybrane dane finansowe firmy z 5 lat */
function insertWybraneDaneFirmy(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    foreach($wskaznik->getAktywaTrwale() as $key => $value) {
        $templateWord->setValue('DF0_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getRzeczoweAktywaTrwale() as $key => $value) {
        $templateWord->setValue('DF1_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getSrodkiTrwale() as $key => $value) {
        $templateWord->setValue('DF2_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getAktywaObrotowe() as $key => $value) {
        $templateWord->setValue('DF3_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getNaleznosciKrotkoterminowe() as $key => $value) {
        $templateWord->setValue('DF4_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getInwestycjeKrotkoterminowe() as $key => $value) {
        $templateWord->setValue('DF5_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getKapitalWlasny() as $key => $value) {
        $templateWord->setValue('DF6_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getZobowiazania() as $key => $value) {
        $templateWord->setValue('DF7_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getZobowiazaniaDlugoterminowe() as $key => $value) {
        $templateWord->setValue('DF8_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getZobowiazaniaKrotkoterminowe() as $key => $value) {
        $templateWord->setValue('DF9_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getPasywaRazem() as $key => $value) {
        $templateWord->setValue('DF10_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getPrzychodyNetto() as $key => $value) {
        $templateWord->setValue('DF11_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getKosztyDzialanosciOperacyjnej() as $key => $value) {
        $templateWord->setValue('DF12_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getZyskBrutto() as $key => $value) {
        $templateWord->setValue('DF13_'.$key, number_format($value, 2, ',', ' '));
    }
    foreach($wskaznik->getZyskNetto() as $key => $value) {
        $templateWord->setValue('DF14_'.$key, number_format($value, 2, ',', ' '));
    }
}

/* Wstawiam analize wskaźnikową z 5 lat */
function insertWskazniki(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    foreach($wskaznik->getWskPlynnosciBiezacej() as $key => $value) {
        $templateWord->setValue('WSK0_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getWskPlynnosciSzybkiej() as $key => $value) {
        $templateWord->setValue('WSK1_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getWskPlynnosciGotowka() as $key => $value) {
        $templateWord->setValue('WSK2_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaNaleznosciWrazach() as $key => $value) {
        $templateWord->setValue('WSK3_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaNaleznosciWdniach() as $key => $value) {
        $templateWord->setValue('WSK4_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaZobowiazanWrazach() as $key => $value) {
        $templateWord->setValue('WSK5_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaZobowiazanWdniach() as $key => $value) {
        $templateWord->setValue('WSK6_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaZapasowWrazach() as $key => $value) {
        $templateWord->setValue('WSK7_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getRotacjaZapasowWdniach() as $key => $value) {
        $templateWord->setValue('WSK8_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getROI() as $key => $value) {
        $templateWord->setValue('WSK9_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getROE() as $key => $value) {
        $templateWord->setValue('WSK10_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getZyskownoscPrzychodow() as $key => $value) {
        $templateWord->setValue('WSK11_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getPokrycieAktywow() as $key => $value) {
        $templateWord->setValue('WSK12_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getZadluzenieOgolne() as $key => $value) {
        $templateWord->setValue('WSK13_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getPokrycieMajatkuTrwalego() as $key => $value) {
        $templateWord->setValue('WSK14_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getProduktywnoscAktywow() as $key => $value) {
        $templateWord->setValue('WSK15_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getProduktywnoscMajatkuTrwalego() as $key => $value) {
        $templateWord->setValue('WSK16_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getCyklKonwersjiGotowkowej() as $key => $value) {
        $templateWord->setValue('WSK17_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getDynamikaPrzychodow() as $key => $value) {
        $templateWord->setValue('WSK18_'.$key, number_format($value,2,',',' '));
    }
    foreach($wskaznik->getZyski() as $key => $value) {
        $templateWord->setValue('WSK19_'.$key, number_format($value,2,',',' '));
    }
}

/* WYBRANE DANE FIRMY - Wstawiam informacje o różnicy w przychodach ze sprzedaży */
function insertPorownaniePrzychodowZeSprzedazy(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    $przychody = $wskaznik->getPrzychodyNetto();
    $roznica = $przychody[0] - $przychody[1];
    if ($roznica < 0 ) {
        $templateWord->setValue('NIZ-WYZ', 'niższym');
    }
    if ($roznica > 0 ) {
        $templateWord->setValue('NIZ-WYZ', 'wyższym');
    }
    if ($roznica == 0 ) {
        $templateWord->setValue('NIZ-WYZ', 'takim samym');
    }
}

/* WYBRANE DANE FIRMY - Wstawiam informacje o dynamice przychodów w roku bazowym */
function insertOkreslenieDynamikiPrzychodow(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    $dynamika = $wskaznik->getDynamikaPrzychodow();
    if ($dynamika[0] >= 0) {
        $templateWord->setValue('DOD-UJE', 'dodatnią');
    } else {
        $templateWord->setValue('DOD-UJE', 'ujemną');
    }
}