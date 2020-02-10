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
    if ($dynamika[1] >= 0) {
        $templateWord->setValue('DOD-UJE-2', 'dodatnim');
    } else {
        $templateWord->setValue('DOD-UJE-2', 'ujemnym');
    }
}

/* WYBRANE DANE FIRMY - Punkt nr 2 (zysk) */
function insertWybraneDaneFirmy_punkt_2(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    $zyskNettoTable = $wskaznik->getZyskNetto();
    if ($zyskNettoTable[0] >= 0) {
        $templateWord->setValue('podsumowanie_zyski_1', 'dobrym sygnałem jest stała obecność');
        $templateWord->setValue('podsumowanie_zyski_2', 'silna');
    } else {
        $templateWord->setValue('podsumowanie_zyski_1', 'złym sygnałem jest brak obecności');
        $templateWord->setValue('podsumowanie_zyski_2', 'słaba');
    }
}

/***********/
/* WYKRESY */
/***********/

/* ANALIZA SYTUACJI FINANSOWEJ - Wstawiam wykres "Analiza aktywów trwałych" */
function insertChartAktywa(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $path_image) {
    $templateWord->setImg('IMG_AKTYWA',array('src' => "$path_image",'swh'=>'550'));
}

/* ANALIZA SYTUACJI FINANSOWEJ - Wstawiam wykres "Wskaźnik płynności" */
function insertChartWskPlynnosci(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $path_image) {
    $templateWord->setImg('IMG_WSKPLYNNOSCI',array('src' => "$path_image",'swh'=>'650'));
}

/* ANALIZA SYTUACJI FINANSOWEJ - Wstawiam wykres "Wskaźnik cyklu konwersji gotówkowej" */
function insertChartWskCyklu(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $path_image) {
    $templateWord->setImg('IMG_WSKCYKLU',array('src' => "$path_image",'swh'=>'650'));
}

/* ANALIZA SYTUACJI FINANSOWEJ - Wstawiam wykres "Wskaźnik ROI i ROE" */
function insertChartWskROIROE(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $path_image) {
    $templateWord->setImg('IMG_WSKROIROE',array('src' => "$path_image",'swh'=>'650'));
}

/* ANALIZA SYTUACJI FINANSOWEJ - Wstawiam wykres "Wskaźnik zadłużenia ogólnego i pokrycia aktywów" */
function insertChartWskZadluzenia(\PhpOffice\PhpWord\TemplateProcessor $templateWord, $path_image) {
    $templateWord->setImg('IMG_WSKZADLUZENIA',array('src' => "$path_image",'swh'=>'650'));
}

/*********************/
/* Wstawianie treści */
/*********************/

/* Wstawianie treści dotyczącej analizy wskaźnikowej */

/* PŁYNNOŚĆ FINANSOWA */
/* Dotyczy: wskaźnik płynności bieżącej */
function insertWskPlynnosciBiezacej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${wsk_plynnosci_biezacej_numer}
    //Dla wartości powyżej 2,0
    $wsk_plynnosci_biezacej_wysoki = array(
        'bardzo',
        'bardzo korzystny sygnał. Tak dobre wyniki należą do rzadkości.',
        'silną stronę. Zaznaczyć jednocześnie należy, że wyniki tej firmy mieszczą się powyżej wartości średnich dla branży.'
    );

    //Dla wartości 1,0 - 2,0
    $wsk_plynnosci_biezacej_sredni = array(
        '',
        'korzystny sygnał.',
        'dość silną stronę.'
    );

    //Dla wartosci poniżej 1,0
    $wsk_plynnosci_biezacej_niski = array(
        'niezbyt',
        'niekorzystny sygnał.',
        'słabą stronę.'
    );
    $wskPlynnosciBiezacejTable = $wskaznik->getWskPlynnosciBiezacej();

    if ($wskPlynnosciBiezacejTable[0] > 2.0) {
        $templateWord->setValue('wsk_plynnosci_biezacej_0', $wsk_plynnosci_biezacej_wysoki[0]);
        $templateWord->setValue('wsk_plynnosci_biezacej_1', $wsk_plynnosci_biezacej_wysoki[1]);
        $templateWord->setValue('wsk_plynnosci_biezacej_2', $wsk_plynnosci_biezacej_wysoki[2]);
    }

    if ($wskPlynnosciBiezacejTable[0] >= 1.0 && $wskPlynnosciBiezacejTable[0] <= 2.0) {
        $templateWord->setValue('wsk_plynnosci_biezacej_0', $wsk_plynnosci_biezacej_sredni[0]);
        $templateWord->setValue('wsk_plynnosci_biezacej_1', $wsk_plynnosci_biezacej_sredni[1]);
        $templateWord->setValue('wsk_plynnosci_biezacej_2', $wsk_plynnosci_biezacej_sredni[2]);
    }

    if ($wskPlynnosciBiezacejTable[0] < 1.0) {
        $templateWord->setValue('wsk_plynnosci_biezacej_0', $wsk_plynnosci_biezacej_niski[0]);
        $templateWord->setValue('wsk_plynnosci_biezacej_1', $wsk_plynnosci_biezacej_niski[1]);
        $templateWord->setValue('wsk_plynnosci_biezacej_2', $wsk_plynnosci_biezacej_niski[2]);
    }
}

/* Dotyczy: dynamika wskaźnika płynności bieżącej */
function insertDynamikaWskPlynnosciBiezacej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_wsk_plynnosci_biezacej}
    $dynamika_wsk_plynnosci_biezacej = array(
        'korzystnym, rosnącym poziomie.',       //Dla wartości powyżej 1,20
        'korzystnym, rosnącym poziomie.',       //Dla wartości 1,05 - 1,20
        'stabilnym poziomie.',                  //Dla wartosci 0,95 - 1,05
        'niekorzystnym, słabnącym poziomie.'    //Dla wartosci ponizej 0,95
    );
    $wskPlynnosciBiezacejTable = $wskaznik->getWskPlynnosciBiezacej();
    $dynamikaWskPlynnosciBiezacej = $wskPlynnosciBiezacejTable[0]/$wskPlynnosciBiezacejTable[1];

    if ($dynamikaWskPlynnosciBiezacej > 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_biezacej', $dynamika_wsk_plynnosci_biezacej[0]);
    }

    if ($dynamikaWskPlynnosciBiezacej >= 1.05 && $dynamikaWskPlynnosciBiezacej <= 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_biezacej', $dynamika_wsk_plynnosci_biezacej[1]);
    }

    if ($dynamikaWskPlynnosciBiezacej >= 0.95 && $dynamikaWskPlynnosciBiezacej < 1.05) {
        $templateWord->setValue('dynamika_wsk_plynnosci_biezacej', $dynamika_wsk_plynnosci_biezacej[2]);
    }

    if ($dynamikaWskPlynnosciBiezacej < 0.95) {
        $templateWord->setValue('dynamika_wsk_plynnosci_biezacej', $dynamika_wsk_plynnosci_biezacej[3]);
    }
}

/* Dotyczy: wskaźnik płynności szybkiej */
function insertWskPlynnosciSzybkiej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${wsk_plynnosci_szybkiej}
    $wsk_plynnosci_szybkiej = array(
        'bardzo korzystnym poziomie.',  //Dla wartości powyżej 1,5
        'korzystnym poziomie.',         //Dla wartości 0,8 - 1,5
        'niekorzystnym poziomie.'       //Dla wartosci poniżej 0,8
    );
    $wskPlynnosciSzybkiejTable = $wskaznik->getWskPlynnosciSzybkiej();

    if ($wskPlynnosciSzybkiejTable[0] > 1.5) {
        $templateWord->setValue('wsk_plynnosci_szybkiej', $wsk_plynnosci_szybkiej[0]);
    }

    if ($wskPlynnosciSzybkiejTable[0] >= 0.8 && $wskPlynnosciSzybkiejTable[0] <= 1.5) {
        $templateWord->setValue('wsk_plynnosci_szybkiej', $wsk_plynnosci_szybkiej[1]);
    }

    if ($wskPlynnosciSzybkiejTable[0] < 0.8) {
        $templateWord->setValue('wsk_plynnosci_szybkiej', $wsk_plynnosci_szybkiej[2]);
    }
}

/* Dotyczy: dynamika wskaźnik płynności szybkiej */
function insertDynamikaWskPlynnosciSzybkiej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_wsk_plynnosci_szybkiej}
    $yearsTable = $wskaznik->getYearsTabel();
    $rok = current($yearsTable);
    $wskPlynnosciSzybkiejTable = $wskaznik->getWskPlynnosciSzybkiej();
    $dynamikaWskPlynnosciSzybkiej = $wskPlynnosciSzybkiejTable[0]/$wskPlynnosciSzybkiejTable[1];
    $dynamika_wsk_plynnosci_szybkiej = array(
        "Dynamika w $rok roku na płynności szybkiej była na korzystnym poziomie i wyniosła (".number_format($dynamikaWskPlynnosciSzybkiej,2,',',' ').")%.",     //Dla wartości powyżej 1,20
        "Dynamika w $rok roku na płynności szybkiej była na dość korzystnym poziomie i wyniosła (".number_format($dynamikaWskPlynnosciSzybkiej,2,',',' ').")%.",//Dla wartości 1,05 - 1,2
        "Zmiany wartości wskaźnika płynności szybkiej w $rok są na niewielkim poziomie, gdyż nie przekraczają 5% odchyleń od wartości z roku poprzedniego.",    //Dla wartości 0,95 - 1,05
        "Dynamika w $rok roku na płynności szybkiej była na niekorzystnym poziomie i wyniosła (".number_format($dynamikaWskPlynnosciSzybkiej,2,',',' ').")%."   //Dla wartości poniżej 0,95
    );

    if ($dynamikaWskPlynnosciSzybkiej > 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_szybkiej', $dynamika_wsk_plynnosci_szybkiej[0]);
    }

    if ($dynamikaWskPlynnosciSzybkiej >= 1.05 && $dynamikaWskPlynnosciSzybkiej <= 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_szybkiej', $dynamika_wsk_plynnosci_szybkiej[1]);
    }

    if ($dynamikaWskPlynnosciSzybkiej >= 0.95 && $dynamikaWskPlynnosciSzybkiej < 1.05) {
        $templateWord->setValue('dynamika_wsk_plynnosci_szybkiej', $dynamika_wsk_plynnosci_szybkiej[2]);
    }

    if ($dynamikaWskPlynnosciSzybkiej < 0.95) {
        $templateWord->setValue('dynamika_wsk_plynnosci_szybkiej', $dynamika_wsk_plynnosci_szybkiej[3]);
    }
}

/* Dotyczy: wskaźnik płynności gotówkowej */
function insertWskPlynnosciGotowkowej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${wsk_plynnosci_gotowkowej}
    //Dla wartości powyżej 1,0
    $wsk_plynnosci_gotowkowej_wysoki = array(
        '',
        'maksymalnej',
        '',
        'wszystkie zobowiązania'
    );

    //Dla wartości 0,25 - 1,0
    $wsk_plynnosci_gotowkowej_sredni = array(
        '',
        'dużej',
        '',
        'znaczną część zobowiązań'
    );

    //Dla wartosci poniżej 0,25
    $wsk_plynnosci_gotowkowej_niski = array(
        'nie',
        'znacznej',
        'nie',
        'istotnej części zobowiązań'
    );
    $wskPlynnosciGotowkowejTable = $wskaznik->getWskPlynnosciGotowka();

    if ($wskPlynnosciGotowkowejTable[0] > 1.0) {
        $templateWord->setValue('wsk_plynnosci_gotowkowej_0', $wsk_plynnosci_gotowkowej_wysoki[0]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_1', $wsk_plynnosci_gotowkowej_wysoki[1]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_2', $wsk_plynnosci_gotowkowej_wysoki[2]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_3', $wsk_plynnosci_gotowkowej_wysoki[3]);
    }

    if ($wskPlynnosciGotowkowejTable[0] >= 0.25 && $wskPlynnosciGotowkowejTable[0] <= 1.0) {
        $templateWord->setValue('wsk_plynnosci_gotowkowej_0', $wsk_plynnosci_gotowkowej_sredni[0]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_1', $wsk_plynnosci_gotowkowej_sredni[1]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_2', $wsk_plynnosci_gotowkowej_sredni[2]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_3', $wsk_plynnosci_gotowkowej_sredni[3]);
    }

    if ($wskPlynnosciGotowkowejTable[0] < 0.25) {
        $templateWord->setValue('wsk_plynnosci_gotowkowej_0', $wsk_plynnosci_gotowkowej_niski[0]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_1', $wsk_plynnosci_gotowkowej_niski[1]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_2', $wsk_plynnosci_gotowkowej_niski[2]);
        $templateWord->setValue('wsk_plynnosci_gotowkowej_3', $wsk_plynnosci_gotowkowej_niski[3]);
    }
}

/* Dotyczy: dynamika wskaźnik płynności gotówkowej */
function insertDynamikaWskPlynnosciGotowkowej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik, Bilans $bilans) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_wsk_plynnosci_gotowkowej}
    $yearsTable = $wskaznik->getYearsTabel();
    $rok = current($yearsTable);;
    $firma = $bilans->getFirma();
    $wskPlynnosciGotowkowejTable = $wskaznik->getWskPlynnosciGotowka();
    $dynamikaWskPlynnosciGotowkowej = $wskPlynnosciGotowkowejTable[0]/$wskPlynnosciGotowkowejTable[1];
    $dynamika_wsk_plynnosci_gotowkowej = array(
        "Ważną obserwacją są wyniki dynamiki w $rok dla płynności gotówkowej, które wskazują, że $firma zwiększyła w istotny sposób wartość najistotniejszego wskaźnika płynności.",                                    //Dla wartości powyżej 1,20
        "Ważną obserwacją są wyniki dynamiki w $rok dla płynności gotówkowej, które wskazują, że $firma zwiększyła wartość najistotniejszego wskaźnika płynności.",                                                     //Dla wartości 1,05 - 1,2
        "Zmiany wartości wskaźnika płynności gotówkowej w $rok są na niewielkim poziomie, gdyż nie przekraczają 5% odchyleń od wartości z roku poprzedniego.",                                                          //Dla wartości 0,95 - 1,05
        "Ważną obserwacją są wyniki dynamiki w $rok dla płynności gotówkowej, które wskazują, że $firma zmniejszyła wartość najistotniejszego wskaźnika płynności. Nie możemy uznać tego zjawiska za pozytywny sygnał." //Dla wartości poniżej 0,95
    );

    if ($dynamikaWskPlynnosciGotowkowej > 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_gotowkowej', $dynamika_wsk_plynnosci_gotowkowej[0]);
    }

    if ($dynamikaWskPlynnosciGotowkowej >= 1.05 && $dynamikaWskPlynnosciGotowkowej <= 1.20) {
        $templateWord->setValue('dynamika_wsk_plynnosci_gotowkowej', $dynamika_wsk_plynnosci_gotowkowej[1]);
    }

    if ($dynamikaWskPlynnosciGotowkowej >= 0.95 && $dynamikaWskPlynnosciGotowkowej < 1.05) {
        $templateWord->setValue('dynamika_wsk_plynnosci_gotowkowej', $dynamika_wsk_plynnosci_gotowkowej[2]);
    }

    if ($dynamikaWskPlynnosciGotowkowej < 0.95) {
        $templateWord->setValue('dynamika_wsk_plynnosci_gotowkowej', $dynamika_wsk_plynnosci_gotowkowej[3]);
    }
}

/* SPRAWNOŚĆ W ZARZĄDZANIU */
/* Dotyczy: wskaźnik rotacji należności */
function insertWskRotacjiNaleznosci(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik, Bilans $bilans) {
    //W szablonie - ${wsk_rotacji_naleznosci}
    $firma = $bilans->getFirma();
    $wsk_rotacji_naleznosci = array(
        "niekorzystnym poziomie, gdyż wskazują na problemy z inkasowaniem środków od klientów przez $firma.",  //Dla wartości powyżej 180
        "umiarkowanym poziomie.",                                                                              //Dla wartości 90 - 180
        "korzystnym poziomie, gdyż wskazują na szybkie inkasowanie środków od klientów przez $firma."          //Dla wartosci poniżej 90
    );
    $wskRotacjiNaleznosciTable = $wskaznik->getRotacjaNaleznosciWdniach();

    if ($wskRotacjiNaleznosciTable[0] > 180) {
        $templateWord->setValue('wsk_rotacji_naleznosci', $wsk_rotacji_naleznosci[0]);
    }

    if ($wskRotacjiNaleznosciTable[0] >= 90 && $wskRotacjiNaleznosciTable[0] <= 180) {
        $templateWord->setValue('wsk_rotacji_naleznosci', $wsk_rotacji_naleznosci[1]);
    }

    if ($wskRotacjiNaleznosciTable[0] < 90) {
        $templateWord->setValue('wsk_rotacji_naleznosci', $wsk_rotacji_naleznosci[2]);
    }
}

/* Dotyczy: dynamika wskaźnik rotacji należności */
function insertDynamikaWskRotacjiNaleznosci(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_wsk_rotacji_naleznosci}
    $wskRotacjiNaleznosciTable = $wskaznik->getRotacjaNaleznosciWdniach();
    $dynamikaWskRotacjiNaleznosci = $wskRotacjiNaleznosciTable[0]/$wskRotacjiNaleznosciTable[1];
    $dynamika_wsk_rotacji_naleznosci = array(
        "niekorzystnym poziomie, gdyż zauważono istotne zwiększenie wartości na rotacji należności. Jest to negatywny sygnał.",  //Dla wartości powyżej 1,20
        "niekorzystnym poziomie, gdyż zauważono  zwiększenie wartości na rotacji należności. Jest to negatywny sygnał.",         //Dla wartości 1,05 - 1,20
        "niewielkim poziomie, gdyż nie przekraczają 5% odchyleń od wartości z roku poprzedniego.",                               //Dla wartosci 0,95 - 1,05
        "korzystnym poziomie, gdyż zauważono  zmniejszenie wartości na rotacji należności. Jest to pozytywny sygnał."            //Dla wartosci poniżej 0,95
    );

    if ($dynamikaWskRotacjiNaleznosci > 1.20) {
        $templateWord->setValue('dynamika_wsk_rotacji_naleznosci', $dynamika_wsk_rotacji_naleznosci[0]);
    }

    if ($dynamikaWskRotacjiNaleznosci >= 1.05 && $dynamikaWskRotacjiNaleznosci <= 1.20) {
        $templateWord->setValue('dynamika_wsk_rotacji_naleznosci', $dynamika_wsk_rotacji_naleznosci[1]);
    }

    if ($dynamikaWskRotacjiNaleznosci >= 0.95 && $dynamikaWskRotacjiNaleznosci < 1.05) {
        $templateWord->setValue('dynamika_wsk_rotacji_naleznosci', $dynamika_wsk_rotacji_naleznosci[2]);
    }

    if ($dynamikaWskRotacjiNaleznosci < 0.95) {
        $templateWord->setValue('dynamika_wsk_rotacji_naleznosci', $dynamika_wsk_rotacji_naleznosci[3]);
    }
}

/* Dotyczy: wskaźnik rotacji zobowiązań */
function insertWskRotacjiZobowiazan(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${wsk_rotacji_zobowiazan}
    $wsk_rotacji_zobowiazan = array(
        "niekorzystnym poziomie, gdyż wskazują na bardzo wolne spłacanie zobowiązań przez", //Dla wartości powyżej 180
        "niekorzystnym poziomie, gdyż wskazują na dość wolne spłacanie zobowiązań przez",   //Dla wartości 90 - 180
        "korzystnym poziomie, gdyż wskazują na szybkie spłacanie zobowiązań przez"          //Dla wartosci poniżej 90
    );
    $wskRotacjiZobowiazanTable = $wskaznik->getRotacjaZobowiazanWdniach();

    if ($wskRotacjiZobowiazanTable[0] > 180) {
        $templateWord->setValue('wsk_rotacji_zobowiazan', $wsk_rotacji_zobowiazan[0]);
    }

    if ($wskRotacjiZobowiazanTable[0] >= 90 && $wskRotacjiZobowiazanTable[0] <= 180) {
        $templateWord->setValue('wsk_rotacji_zobowiazan', $wsk_rotacji_zobowiazan[1]);
    }

    if ($wskRotacjiZobowiazanTable[0] < 90) {
        $templateWord->setValue('wsk_rotacji_zobowiazan', $wsk_rotacji_zobowiazan[2]);
    }
}

/* Dotyczy: dynamika wskaźnik rotacji zobowiązań */
function insertDynamikaWskRotacjiZobowiazan(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_wsk_rotacji_zobowiazan}
    $yearsTable = $wskaznik->getYearsTabel();
    $rok = current($yearsTable);
    $wskRotacjiZobowiazanTable = $wskaznik->getRotacjaZobowiazanWdniach();
    $dynamikaWskRotacjiZobowiazan = $wskRotacjiZobowiazanTable[0]/$wskRotacjiZobowiazanTable[1];
    $dynamika_wsk_rotacji_zobowiazan = array(
        "Wartości wskaźnika rotacji zobowiązań w $rok zostały istotnie podniesione. Jest to negatywny sygnał, gdyż jednocześnie wzrastają zagrożenia na okresy kolejne.",//Dla wartości powyżej 1,20
        "Wartości wskaźnika rotacji zobowiązań w $rok zostały podniesione. Jest to negatywny sygnał, gdyż jednocześnie wzrastają zagrożenia na okresy kolejne.",         //Dla wartości 1,05 - 1,20
        "Zmiany wartości wskaźnika rotacji zobowiązań w $rok są na niewielkim poziomie, gdyż nie przekraczają 5% odchyleń od wartości z roku poprzedniego.",             //Dla wartosci 0,95 - 1,05
        "Wartości wskaźnika rotacji zobowiązań w $rok zostały zmniejszone. Jest to pozytywny sygnał, gdyż jednocześnie obniżają się zagrożenia na okresy kolejne."       //Dla wartosci poniżej 0,95
    );

    if ($dynamikaWskRotacjiZobowiazan > 1.20) {
        $templateWord->setValue('dynamika_wsk_rotacji_zobowiazan', $dynamika_wsk_rotacji_zobowiazan[0]);
    }

    if ($dynamikaWskRotacjiZobowiazan >= 1.05 && $dynamikaWskRotacjiZobowiazan <= 1.20) {
        $templateWord->setValue('dynamika_wsk_rotacji_zobowiazan', $dynamika_wsk_rotacji_zobowiazan[1]);
    }

    if ($dynamikaWskRotacjiZobowiazan >= 0.95 && $dynamikaWskRotacjiZobowiazan < 1.05) {
        $templateWord->setValue('dynamika_wsk_rotacji_zobowiazan', $dynamika_wsk_rotacji_zobowiazan[2]);
    }

    if ($dynamikaWskRotacjiZobowiazan < 0.95) {
        $templateWord->setValue('dynamika_wsk_rotacji_zobowiazan', $dynamika_wsk_rotacji_zobowiazan[3]);
    }
}

/* Dotyczy: cykl konwersji gotówkowej */
function insertCyklKonwersjiGotowkowej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${cykl_konwersji_gotowkowej}
    $cykl_konwersji_gotowkowej = array(
        "własnych, o czym świadczy dodatnia wartość.", //Dla wartości powyżej 0
        "dostawców, o czym świadczy ujemna wartość."   //Dla wartości poniżej 0
    );
    $cyklKonwersjiGotowkowejTable = $wskaznik->getCyklKonwersjiGotowkowej();

    if ($cyklKonwersjiGotowkowejTable[0] >= 0) {
        $templateWord->setValue('cykl_konwersji_gotowkowej', $cykl_konwersji_gotowkowej[0]);
    }

    if ($cyklKonwersjiGotowkowejTable[0] < 0) {
        $templateWord->setValue('cykl_konwersji_gotowkowej', $cykl_konwersji_gotowkowej[1]);
    }
}

/* Dotyczy: dynamika wskaźnik cykl konwersji gotówkowej */
function insertDynamikaCyklKonwersjiGotowkowej(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik, Bilans $bilans) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_cykl_konwersji_gotowkowej}
    $firma = $bilans->getFirma();
    $yearsTable = $wskaznik->getYearsTabel();
    $rok = current($yearsTable);
    $cyklKonwersjiGotowkowejTable = $wskaznik->getCyklKonwersjiGotowkowej();
    $dynamikaCyklKonwersjiGotowkowej = $cyklKonwersjiGotowkowejTable[0]/$cyklKonwersjiGotowkowejTable[1];
    $dynamika_cykl_konwersji_gotowkowej = array(
        "Wartości wskaźnika cyklu konwersji gotówkowej w $rok zostały podniesione. Jest to pozytywny sygnał, gdyż wskazuje na większe finansowanie cyklu handlowego przez $firma.",         //Dla wartości powyżej 1,20
        "Wartości wskaźnika cyklu konwersji gotówkowej w $rok zostały podniesione. Jest to pozytywny sygnał, gdyż wskazuje na większe finansowanie cyklu handlowego przez $firma.",         //Dla wartości 1,05 - 1,20
        "Zmiany wartości wskaźnika cyklu konwersji gotówkowej w $rok są na niewielkim poziomie, gdyż nie przekraczają 5% odchyleń od wartości z roku poprzedniego.",                        //Dla wartosci 0,95 - 1,05
        "Wartości wskaźnika cyklu konwersji gotówkowej w $rok zostały obniżone. Jest to negatywny sygnał, gdyż wskazuje na większe finansowanie cyklu handlowego przez dostawców $firma."   //Dla wartosci poniżej 0,95
    );

    if ($dynamikaCyklKonwersjiGotowkowej > 1.20) {
        $templateWord->setValue('dynamika_cykl_konwersji_gotowkowej', $dynamika_cykl_konwersji_gotowkowej[0]);
    }

    if ($dynamikaCyklKonwersjiGotowkowej >= 1.05 && $dynamikaCyklKonwersjiGotowkowej <= 1.20) {
        $templateWord->setValue('dynamika_cykl_konwersji_gotowkowej', $dynamika_cykl_konwersji_gotowkowej[1]);
    }

    if ($dynamikaCyklKonwersjiGotowkowej >= 0.95 && $dynamikaCyklKonwersjiGotowkowej < 1.05) {
        $templateWord->setValue('dynamika_cykl_konwersji_gotowkowej', $dynamika_cykl_konwersji_gotowkowej[2]);
    }

    if ($dynamikaCyklKonwersjiGotowkowej < 0.95) {
        $templateWord->setValue('dynamika_cykl_konwersji_gotowkowej', $dynamika_cykl_konwersji_gotowkowej[3]);
    }
}

/* Dotyczy: ROI */
function insertROI(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${roi}
    $roi = array(
        "bardzo wysokim poziomie. Wartości te wskazują na bardzo wysoki zwrot na posiadanych przez Spółkę aktywach.", //Dla wartości powyżej 0,20
        "wysokim poziomie. Wartości te wskazują na wysoki zwrot na posiadanych przez Spółkę aktywach.",               //Dla wartości 0,05 - 0,20
        "umiarkowanym poziomie. Wartości te wskazują na niezbyt wysoki zwrot na posiadanych przez Spółkę aktywach.",  //Dla wartości 0,00 - 0,05
        "słabym poziomie. Wartości te wskazują na ujemny wynik na zwrocie na posiadanych przez Spółkę aktywach."      //Dla wartości poniżej 0,0
    );
    $roiTable = $wskaznik->getROI();

    if ($roiTable[0] > 0.20) {
        $templateWord->setValue('roi', $roi[0]);
    }

    if ($roiTable[0] >= 0.05 && $roiTable[0] <= 0.20) {
        $templateWord->setValue('roi', $roi[1]);
    }

    if ($roiTable[0] >= 0.00 && $roiTable[0] < 0.05) {
        $templateWord->setValue('roi', $roi[2]);
    }

    if ($roiTable[0] < 0.00) {
        $templateWord->setValue('roi', $roi[3]);
    }
}

/* Dotyczy: ROE */
function insertROE(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${roe}
    $roe = array(
        "bardzo wysokim poziomie. Wartość tego wskaźnika pozwala na pozytywne ocenienie", //Dla wartości powyżej 0,20
        "wysokim poziomie. Wartość tego wskaźnika pozwala na pozytywne ocenienie",        //Dla wartości 0,05 - 0,20
        "umiarkowanym poziomie. Wartość tego wskaźnika pozwala na pozytywną ocenę",       //Dla wartości 0,00 - 0,05
        "niekorzystnym poziomie. Wartość tego wskaźnika nie pozwala na pozytywną ocenę"   //Dla wartości poniżej 0,0
    );
    $roeTable = $wskaznik->getROE();

    if ($roeTable[0] > 0.20) {
        $templateWord->setValue('roe', $roe[0]);
    }

    if ($roeTable[0] >= 0.05 && $roeTable[0] <= 0.20) {
        $templateWord->setValue('roe', $roe[1]);
    }

    if ($roeTable[0] >= 0.00 && $roeTable[0] < 0.05) {
        $templateWord->setValue('roe', $roe[2]);
    }

    if ($roeTable[0] < 0.00) {
        $templateWord->setValue('roe', $roe[3]);
    }
}

/* Dotyczy: dynamika ROE */
function insertDynamikaROE(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_roe}
    $roeTable = $wskaznik->getROE();
    $dynamikaROE = $roeTable[0]/$roeTable[1];
    $dynamika_roe = array(
        "znacznie zwiększyła zwrot z inwestycji właścicielskiej. Jest to korzystny sygnał wskazujący na znaczną poprawę sytuacji w tej firmie w tym względzie.",        //Dla wartości powyżej 1,20
        "zwiększyła zwrot z inwestycji właścicielskiej. Jest to korzystny sygnał wskazujący na poprawę sytuacji w tej firmie w tym względzie.",                         //Dla wartości 1,00 - 1,20
        "zmniejszyła zwrot z inwestycji właścicielskiej. Jest to niekorzystny sygnał wskazujący na pogorszenie sytuacji w tej firmie w tym względzie.",                 //Dla wartosci 0,80 - 1,00
        "znacznie zmniejszyła zwrot z inwestycji właścicielskiej. Jest to niekorzystny sygnał wskazujący na znaczne pogorszenie sytuacji w tej firmie w tym względzie." //Dla wartosci poniżej 0,80
    );

    if ($dynamikaROE > 1.20) {
        $templateWord->setValue('dynamika_roe', $dynamika_roe[0]);
    }

    if ($dynamikaROE >= 1.00 && $dynamikaROE <= 1.20) {
        $templateWord->setValue('dynamika_roe', $dynamika_roe[1]);
    }

    if ($dynamikaROE >= 0.80 && $dynamikaROE < 1.00) {
        $templateWord->setValue('dynamika_roe', $dynamika_roe[2]);
    }

    if ($dynamikaROE < 0.80) {
        $templateWord->setValue('dynamika_roe', $dynamika_roe[3]);
    }
}

/* Dotyczy: rentowność przychodów */
function insertRentownoscPrzychodow(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${rentownosc_przychodow}
    $rentownosc_przychodow = array(
        "bardzo korzystne wartości. Rentowność przychodów firmy jest na wysokim poziomie. Wyniki stanowią podstawę do wydania pozytywnej opinii nt. tej firmy.",        //Dla wartości powyżej 0,20
        "korzystne wartości. Rentowność przychodów firmy jest na dość wysokim poziomie. Wyniki stanowią podstawę do wydania pozytywnej opinii nt. tej firmy.",          //Dla wartości 0,05 - 0,20
        "umiarkowane wartości. Rentowność przychodów firmy jest na dość niskim poziomie. Wyniki stanowią jednak podstawę do wydania pozytywnej opinii nt. tej firmy.",  //Dla wartości 0,00 - 0,05
        "niekorzystne wartości. Rentowność przychodów firmy jest na niskim poziomie. Wyniki stanowią podstawę do wydania negatywnej opinii nt. tej firmy."              //Dla wartości poniżej 0,0
    );
    $podsumowanie_rentownosc_przychodow = array(
        "bardzo korzystne wartości na wskaźniku rentowności. Rentowność przychodów firmy jest na wysokim poziomie. Podsumowując wyniki rentowności badanego podmiotu należy stwierdzić, że osiągnął on bardzo korzystne wyniki rentowności. Wyniki stanowią podstawę do wydania pozytywnej opinii nt. tej firmy.", //Dla wartości powyżej 0,20
        "korzystne wartości na wskaźniku rentowności. Rentowność przychodów firmy jest na dość wysokim poziomie. Podsumowując wyniki rentowności badanego podmiotu należy stwierdzić, że osiągnął on korzystne wyniki rentowności. Wyniki stanowią podstawę do wydania pozytywnej opinii nt. tej firmy.",          //Dla wartości 0,05 - 0,20
        "umiarkowane wartości na wskaźniku rentowności. Rentowność przychodów firmy jest na dość niskim poziomie. Podsumowując wyniki rentowności badanego podmiotu należy stwierdzić, że osiągnął on umiarkowane wyniki rentowności. Wyniki stanowią jednak podstawę do wydania pozytywnej opinii nt. tej firmy.",//Dla wartości 0,00 - 0,05
        "niekorzystne wartości na wskaźniku rentowności. Rentowność przychodów firmy jest na niskim poziomie. Podsumowując wyniki rentowności badanego podmiotu należy stwierdzić, że osiągnął on niekorzystne wyniki rentowności. Wyniki stanowią podstawę do wydania negatywnej opinii nt. tej firmy."           //Dla wartości poniżej 0,0
    );
    $rentownoscPrzychodowTable = $wskaznik->getZyskownoscPrzychodow();

    if ($rentownoscPrzychodowTable[0] > 0.20) {
        $templateWord->setValue('rentownosc_przychodow', $rentownosc_przychodow[0]);
        $templateWord->setValue('podsumowanie_rentownosc_przychodow', $podsumowanie_rentownosc_przychodow[0]);
    }

    if ($rentownoscPrzychodowTable[0] >= 0.05 && $rentownoscPrzychodowTable[0] <= 0.20) {
        $templateWord->setValue('rentownosc_przychodow', $rentownosc_przychodow[1]);
        $templateWord->setValue('podsumowanie_rentownosc_przychodow', $podsumowanie_rentownosc_przychodow[1]);
    }

    if ($rentownoscPrzychodowTable[0] >= 0.00 && $rentownoscPrzychodowTable[0] < 0.05) {
        $templateWord->setValue('rentownosc_przychodow', $rentownosc_przychodow[2]);
        $templateWord->setValue('podsumowanie_rentownosc_przychodow', $podsumowanie_rentownosc_przychodow[2]);
    }

    if ($rentownoscPrzychodowTable[0] < 0.00) {
        $templateWord->setValue('rentownosc_przychodow', $rentownosc_przychodow[3]);
        $templateWord->setValue('podsumowanie_rentownosc_przychodow', $podsumowanie_rentownosc_przychodow[3]);
    }
}

/* Dotyczy: Pokrycia aktywów  */
function insertPokrycieAktywow(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${pokrycie_aktywow}
    $pokrycie_aktywow = array(
        "bardzo bezpiecznym poziomie. Wskazuje na bardzo duże zaangażowanie właścicielskie w proces finansowania składników majątku Spółki.",   //Dla wartości powyżej 1,00
        "bezpiecznym poziomie. Wskazuje na duże zaangażowanie właścicielskie w proces finansowania składników majątku Spółki.",                 //Dla wartości 0,80 - 1,00
        "średnim poziomie. Wskazuje na umiarkowane zaangażowanie właścicielskie w proces finansowania składników majątku Spółki.",              //Dla wartości 0,40 - 0,80
        "niebezpiecznym poziomie. Wskazuje na małe zaangażowanie właścicielskie w proces finansowania składników majątku Spółki."               //Dla wartości poniżej 0,40
    );
    $pokrycieAktywowTable = $wskaznik->getPokrycieAktywow();

    if ($pokrycieAktywowTable[0] > 1.00) {
        $templateWord->setValue('pokrycie_aktywow', $pokrycie_aktywow[0]);
    }

    if ($pokrycieAktywowTable[0] >= 0.80 && $pokrycieAktywowTable[0] <= 1.00) {
        $templateWord->setValue('pokrycie_aktywow', $pokrycie_aktywow[1]);
    }

    if ($pokrycieAktywowTable[0] >= 0.40 && $pokrycieAktywowTable[0] < 0.80) {
        $templateWord->setValue('pokrycie_aktywow', $pokrycie_aktywow[2]);
    }

    if ($pokrycieAktywowTable[0] < 0.40) {
        $templateWord->setValue('pokrycie_aktywow', $pokrycie_aktywow[3]);
    }
}

/* Dotyczy: dynamika pokrycia aktywów  */
function insertDynamikaPokryciaAktywow(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_pokrycia_aktywow}
    $pokrycieAktywowTable = $wskaznik->getPokrycieAktywow();
    $dynamikaPokryciaAktywow = $pokrycieAktywowTable[0]/$pokrycieAktywowTable[1];
    $dynamika_pokrycia_aktywow = array(
        "bardzo korzystnym poziomie, gdyż wskazuje na istotne zwiększenie pokrycia aktywów przez kapitały własne.",    //Dla wartości powyżej 1,20
        "korzystnym poziomie, gdyż wskazuje na zwiększenie pokrycia aktywów przez kapitały własne.",                   //Dla wartości 1,00 - 1,20
        "niekorzystnym poziomie, gdyż wskazuje na zmniejszenie pokrycia aktywów przez kapitały własne.",               //Dla wartosci 0,80 - 1,00
        "bardzo niekorzystnym poziomie, gdyż wskazuje na znaczne zmniejszenie pokrycia aktywów przez kapitały własne." //Dla wartosci poniżej 0,80
    );

    if ($dynamikaPokryciaAktywow > 1.20) {
        $templateWord->setValue('dynamika_pokrycia_aktywow', $dynamika_pokrycia_aktywow[0]);
    }

    if ($dynamikaPokryciaAktywow >= 1.00 && $dynamikaPokryciaAktywow <= 1.20) {
        $templateWord->setValue('dynamika_pokrycia_aktywow', $dynamika_pokrycia_aktywow[1]);
    }

    if ($dynamikaPokryciaAktywow >= 0.80 && $dynamikaPokryciaAktywow < 1.00) {
        $templateWord->setValue('dynamika_pokrycia_aktywow', $dynamika_pokrycia_aktywow[2]);
    }

    if ($dynamikaPokryciaAktywow < 0.80) {
        $templateWord->setValue('dynamika_pokrycia_aktywow', $dynamika_pokrycia_aktywow[3]);
    }
}

/* Dotyczy: zadłużenie ogólne */
function insertZadluzenieOgolne(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${zadluzenie_ogolne}
    $zadluzenie_ogolne = array(
        "bezpiecznym poziomie. Wartość tego wskaźnika pozwala na pozytywne ocenienie",      //Dla wartości poniżej 1,00
        "umiarkowanym poziomie. Wartość tego wskaźnika pozwala na pozytywne ocenienie",     //Dla wartości 1,00 - 1,20
        "niekorzystnym poziomie. Wartość tego wskaźnika nie pozwala na pozytywne ocenienie" //Dla wartości powyżej 1,20
    );
    $zadluzenieOgolneTable = $wskaznik->getZadluzenieOgolne();

    if ($zadluzenieOgolneTable[0] < 1.00) {
        $templateWord->setValue('zadluzenie_ogolne', $zadluzenie_ogolne[0]);
    }

    if ($zadluzenieOgolneTable[0] >= 1.00 && $zadluzenieOgolneTable[0] <= 1.20) {
        $templateWord->setValue('zadluzenie_ogolne', $zadluzenie_ogolne[1]);
    }

    if ($zadluzenieOgolneTable[0] > 1.20) {
        $templateWord->setValue('zadluzenie_ogolne', $zadluzenie_ogolne[2]);
    }
}

/* Dotyczy: dynamika zadłużenia ogólnego */
function insertDynamikaZadluzeniaOgolnego(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_zadluzenia_ogolnego}
    $zadluzenieOgolneTable = $wskaznik->getZadluzenieOgolne();
    $dynamikaZadluzeniaOgolnego = $zadluzenieOgolneTable[0]/$zadluzenieOgolneTable[1];
    $dynamika_zadluzenia_ogolnego = array(
        "dość niekorzystnym poziomie, gdyż wskazuje na znaczne zwiększenie poziomu zadłużenia analizowanego podmiotu.",//Dla wartości powyżej 1,20
        "niekorzystnym poziomie, gdyż wskazuje na zwiększenie poziomu zadłużenia analizowanego podmiotu.",             //Dla wartości 1,00 - 1,20
        "korzystnym poziomie, gdyż wskazuje na zmniejszenie poziomu zadłużenia analizowanego podmiotu.",               //Dla wartosci 0,80 - 1,00
        "bardzo korzystnym poziomie, gdyż wskazuje na znaczne zmniejszenie poziomu zadłużenia analizowanego podmiotu." //Dla wartosci poniżej 0,80
    );

    if ($dynamikaZadluzeniaOgolnego > 1.20) {
        $templateWord->setValue('dynamika_zadluzenia_ogolnego', $dynamika_zadluzenia_ogolnego[0]);
    }

    if ($dynamikaZadluzeniaOgolnego >= 1.00 && $dynamikaZadluzeniaOgolnego <= 1.20) {
        $templateWord->setValue('dynamika_zadluzenia_ogolnego', $dynamika_zadluzenia_ogolnego[1]);
    }

    if ($dynamikaZadluzeniaOgolnego >= 0.80 && $dynamikaZadluzeniaOgolnego < 1.00) {
        $templateWord->setValue('dynamika_zadluzenia_ogolnego', $dynamika_zadluzenia_ogolnego[2]);
    }

    if ($dynamikaZadluzeniaOgolnego < 0.80) {
        $templateWord->setValue('dynamika_zadluzenia_ogolnego', $dynamika_zadluzenia_ogolnego[3]);
    }
}

/* Dotyczy: pokrycie aktywów trwałych */
function insertPokrycieAktywowTrwalych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${pokrycie_aktywow_trwalych}
    //W szablonie - ${pokrycie_aktywow_trwalych_2}
    //W szablonie - ${pokrycie_aktywow_trwalych_3}
    $pokrycie_aktywow_trwalych = array(
        "małym stopniu pochodzi od właściciela. Aktualnie finansowanie zewnętrzne jest znacznie wyższe od finansowania udzielonego przez właściciela. Jest to niekorzystny sygnał i nie pozwala na pozytywne ocenienie badanego podmiotu.", //Dla wartości poniżej 0,80
        "umiarkowanym stopniu pochodzi od właściciela. Aktualnie finansowanie zewnętrzne jest wyższe od finansowania udzielonego przez właściciela. Jest to niekorzystny sygnał i nie pozwala na pozytywne ocenienie badanego podmiotu.",   //Dla wartości 0,80 - 1,00
        "dużym stopniu pochodzi od właściciela. Aktualnie finansowanie zewnętrzne jest niższe od finansowania udzielonego przez właściciela. Jest to korzystny sygnał i pozwala na pozytywne ocenienie badanego podmiotu.",                 //Dla wartości 1,00 - 1,20
        "największym stopniu pochodzi od właściciela. Aktualnie finansowanie zewnętrzne jest zasadniczo niższe od finansowania udzielonego przez właściciela. Jest to korzystny sygnał i pozwala na pozytywne ocenienie badanego podmiotu." //Dla wartości powyżej 1,20
    );
    $procentPokryciaTable = $wskaznik->getAktywaTrwaleProcent();
    $pokrycie_aktywow_trwalych_2 = array(
        "dobre wartości na tym wskaźniku, gdyż wskazują",       //Dla wartości powyżej 1,00
        "całą wartość",                                         //Dla wartości powyżej 1,00
        "słabe wartości na tym wskaźniku, gdyż nie pozwalają",  //Dla wartości poniżej 1,00
        "".number_format($procentPokryciaTable[0],2,',',' ')."% wartości"//Dla wartości poniżej 1,00
    );
    $pokrycieAktywowTrwalychTable = $wskaznik->getPokrycieMajatkuTrwalego();

    if ($pokrycieAktywowTrwalychTable[0] < 0.80) {
        $templateWord->setValue('pokrycie_aktywow_trwalych', $pokrycie_aktywow_trwalych[0]);
    }

    if ($pokrycieAktywowTrwalychTable[0] >= 0.80 && $pokrycieAktywowTrwalychTable[0] <= 1.00) {
        $templateWord->setValue('pokrycie_aktywow_trwalych', $pokrycie_aktywow_trwalych[1]);
    }

    if ($pokrycieAktywowTrwalychTable[0] > 1.00 && $pokrycieAktywowTrwalychTable[0] <= 1.20) {
        $templateWord->setValue('pokrycie_aktywow_trwalych', $pokrycie_aktywow_trwalych[2]);
    }

    if ($pokrycieAktywowTrwalychTable[0] > 1.20) {
        $templateWord->setValue('pokrycie_aktywow_trwalych', $pokrycie_aktywow_trwalych[3]);
    }

    if ($pokrycieAktywowTrwalychTable[0] >= 1.00) {
        $templateWord->setValue('pokrycie_aktywow_trwalych_2', $pokrycie_aktywow_trwalych_2[0]);
        $templateWord->setValue('pokrycie_aktywow_trwalych_3', $pokrycie_aktywow_trwalych_2[1]);
    }

    if ($pokrycieAktywowTrwalychTable[0] < 1.00) {
        $templateWord->setValue('pokrycie_aktywow_trwalych_2', $pokrycie_aktywow_trwalych_2[2]);
        $templateWord->setValue('pokrycie_aktywow_trwalych_3', $pokrycie_aktywow_trwalych_2[3]);
    }
}

/* Dotyczy: dynamika pokrycia aktywów trwałych */
function insertDynamikaPokryciaAktywowTrwalych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego
    //W szablonie - ${dynamika_pokrycia_aktywow_trwalych}
    //W szablonie - ${dynamika_pokrycia_aktywow_trwalych_2}
    $pokrycieAktywowTrwalychTable = $wskaznik->getPokrycieMajatkuTrwalego();
    $dynamikaPokryciaAktywowTrwalych = $pokrycieAktywowTrwalychTable[0]/$pokrycieAktywowTrwalychTable[1];
    $dynamika_pokrycia_aktywow_trwalych = array(
        "zwiększył",    //Dla wartości powyżej 1,00
        "podniosła stopień pokrycia aktywów trwałych kapitałami własnymi. Jest to korzystny sygnał.",    //Dla wartości powyżej 1,00
        "zmniejszył",    //Dla wartości poniżej 1,00
        "obniżyła stopień pokrycia aktywów trwałych kapitałami własnymi. Jest to niekorzystny sygnał."   //Dla wartości poniżej 1,00
    );

    if ($dynamikaPokryciaAktywowTrwalych >= 1.00) {
        $templateWord->setValue('dynamika_pokrycia_aktywow_trwalych', $dynamika_pokrycia_aktywow_trwalych[0]);
        $templateWord->setValue('dynamika_pokrycia_aktywow_trwalych_2', $dynamika_pokrycia_aktywow_trwalych[1]);
    }

    if ($dynamikaPokryciaAktywowTrwalych < 1.00) {
        $templateWord->setValue('dynamika_pokrycia_aktywow_trwalych', $dynamika_pokrycia_aktywow_trwalych[2]);
        $templateWord->setValue('dynamika_pokrycia_aktywow_trwalych_2', $dynamika_pokrycia_aktywow_trwalych[3]);
    }
}

/* Dotyczy: Produktywność aktywów */
function insertProduktywnoscAktywow(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${produktywnosc_aktywow}
    $produktywnosc_aktywow = array(
        "bardzo korzystne wyniki na produktywności. Wyniki tej firmy były na znacznie korzystniejszym poziomie niż w branży.",      //Dla wartości powyżej 1,00
        "niezbyt korzystne wyniki na produktywności. Wyniki tej firmy były na niższym poziomie niż w branży."                       //Dla wartości poniżej 1,00
    );
    $produktywnoscAktywowTable = $wskaznik->getProduktywnoscAktywow();

    if ($produktywnoscAktywowTable[0] >= 1.00) {
        $templateWord->setValue('produktywnosc_aktywow', $produktywnosc_aktywow[0]);
    }

    if ($produktywnoscAktywowTable[0] < 1.00) {
        $templateWord->setValue('produktywnosc_aktywow', $produktywnosc_aktywow[1]);
    }
}

/* ANALIZA SYTUACJI FINANSOWEJ - ANALIZA AKTYWÓW TRWAŁYCH */
function insertAnalizaAktywowTrwalych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    $aktywaTrwaleProcentTable = $wskaznik->getAktywaTrwaleProcent();
    $templateWord->setValue('aktywa_trwale_procent_2', number_format($aktywaTrwaleProcentTable[2],2,',',' '));
    $templateWord->setValue('aktywa_trwale_procent_1', number_format($aktywaTrwaleProcentTable[1],2,',',' '));
    $templateWord->setValue('aktywa_trwale_procent_0', number_format($aktywaTrwaleProcentTable[0],2,',',' '));
    $roznica = $aktywaTrwaleProcentTable[0] - $aktywaTrwaleProcentTable[1];

    if ($roznica > 0) {
        $templateWord->setValue('aktywa_trwale_roznica', 'wzrósł');
    }
    if ($roznica == 0) {
        $templateWord->setValue('aktywa_trwale_roznica', 'nie zmienił się');
    }
    if ($roznica < 0) {
        $templateWord->setValue('aktywa_trwale_roznica', 'spadł');
    }
}

/* ANALIZA SYTUACJI FINANSOWEJ - ANALIZA DYNAMIKI AKTYWÓW TRWAŁYCH */
function insertAnalizaDynamikiAktywowTrwalych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego.
    //Wzór: $dynamikaAktywaTrwale = ((($aktywaTrwale[i] / $aktywaTrwale[i+1])*1) - 1)*100
    //W szablonie - ${analiza_dynamiki_aktywow_trwalych}
    $aktywaTrwaleTable = $wskaznik->getAktywaTrwale();
    $dynamikaAktywaTrwale = ((($aktywaTrwaleTable[0]/$aktywaTrwaleTable[1])*1) - 1)*100;

    if ($dynamikaAktywaTrwale >=0) {
        $templateWord->setValue('analiza_dynamiki_aktywow_trwalych', 'dodatnią');
    } else {
        $templateWord->setValue('analiza_dynamiki_aktywow_trwalych', 'ujemną');
    }
}

/* ANALIZA SYTUACJI FINANSOWEJ - ANALIZA AKTYWÓW OBROTOWYCH */
function insertAnalizaAktywowObrotowych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    $aktywaObrotoweProcentTable = $wskaznik->getAktywaObrotoweProcent();
    $templateWord->setValue('aktywa_obrotowe_procent_2', number_format($aktywaObrotoweProcentTable[2],2,',',' '));
    $templateWord->setValue('aktywa_obrotowe_procent_1', number_format($aktywaObrotoweProcentTable[1],2,',',' '));
    $templateWord->setValue('aktywa_obrotowe_procent_0', number_format($aktywaObrotoweProcentTable[0],2,',',' '));
    $roznica = $aktywaObrotoweProcentTable[0] - $aktywaObrotoweProcentTable[1];

    if ($roznica > 0) {
        $templateWord->setValue('aktywa_obrotowe_roznica', 'wzrósł');
    }
    if ($roznica == 0) {
        $templateWord->setValue('aktywa_obrotowe_roznica', 'nie zmienił się');
    }
    if ($roznica < 0) {
        $templateWord->setValue('aktywa_obrotowe_roznica', 'spadł');
    }
}

/* ANALIZA SYTUACJI FINANSOWEJ - ANALIZA DYNAMIKI AKTYWÓW OBROTOWYCH */
function insertAnalizaDynamikiAktywowObrotowych(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //dynamika to wartość z roku bieżącego podzielić na wartość z poprzedniego.
    //Wzór: $dynamikaAktywaObrotowe = ((($aktywaObrotowe[i] / $aktywaObrotowe[i+1])*1) - 1)*100
    //W szablonie - ${analiza_dynamiki_aktywow_obrotowych}
    $aktywaObrotoweTable = $wskaznik->getAktywaObrotowe();
    $dynamikaAktywaObrotowe = ((($aktywaObrotoweTable[0]/$aktywaObrotoweTable[1])*1) - 1)*100;

    if ($dynamikaAktywaObrotowe >=0) {
        $templateWord->setValue('analiza_dynamiki_aktywow_obrotowych', 'zwiększa');
    } else {
        $templateWord->setValue('analiza_dynamiki_aktywow_obrotowych', 'zmniejsza');
    }
}

/* ANALIZA SYTUACJI FINANSOWEJ - ANALIZA PASYWÓW – KAPITAŁY DŁUGOTERMINOWE */
function insertAnalizaPasywowKapitalyDlugoterminowe(\PhpOffice\PhpWord\TemplateProcessor $templateWord, Wskaznik $wskaznik) {
    //W szablonie - ${analiza_pasywow_1} i ${analiza_pasywow_2}
    $zadluzenieOgolneTable = $wskaznik->getZadluzenieOgolne();

    if ($zadluzenieOgolneTable[0] < 1.00) {
        $templateWord->setValue('analiza_pasywow_1', '');
        $templateWord->setValue('analiza_pasywow_2', 'bardzo duży');
    }
    if ($zadluzenieOgolneTable[0] >= 1.00) {
        $templateWord->setValue('analiza_pasywow_1', 'niezbyt');
        $templateWord->setValue('analiza_pasywow_2', 'mały');
    }

    $kapitalWlasnyTable = $wskaznik->getKapitalWlasny();
    $pasywaRazemTable = $wskaznik->getPasywaRazem();
    $udzialKapitalowWlasnych_0 = ($kapitalWlasnyTable[0]/$pasywaRazemTable[0])*100;
    $udzialKapitalowWlasnych_1 = ($kapitalWlasnyTable[1]/$pasywaRazemTable[1])*100;
    $udzialKapitalowWlasnych_2 = ($kapitalWlasnyTable[2]/$pasywaRazemTable[2])*100;
    $templateWord->setValue('kapital_wlasny_2', number_format($udzialKapitalowWlasnych_2,2,',',' '));
    $templateWord->setValue('kapital_wlasny_1', number_format($udzialKapitalowWlasnych_1,2,',',' '));
    $templateWord->setValue('kapital_wlasny_0', number_format($udzialKapitalowWlasnych_0,2,',',' '));

    if ($udzialKapitalowWlasnych_0 >= $udzialKapitalowWlasnych_1) {
        $templateWord->setValue('kapital_wlasny_wynik_1', 'poprawę');
        $templateWord->setValue('kapital_wlasny_wynik_2', '');
    } else {
        $templateWord->setValue('kapital_wlasny_wynik_1', 'pogorszenie');
        $templateWord->setValue('kapital_wlasny_wynik_2', 'nie');
    }
}