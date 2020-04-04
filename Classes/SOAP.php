<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2020-02-16
 * Time: 19:07
 */
class SOAP {

    private $charset = "UTF-8";
    private $login = "kaMiLIVRap20191029";
    private $haslo = "Zh56f7gMvUhm2r9ws8Ca5zcW";
    private $namespace = 'https://www.infoveriti.pl/WebAPI';
    private $client;
    private $sess;
    public $wsdl;



    // określenie ścieżki do pliku WSDL
    private function setWsdl() {
        $this->wsdl = rtrim($this->namespace,"/").'/raports.wsdl?tmp='.microtime(true);
    }

    public function setSeesion() {
        header("Content-Type: text/html; charset=".$this->charset);
        ini_set("soap.wsdl_cache_enabled","0");
        $this->setWsdl();

        try {
            // Stworzenie klienta SOAP
            libxml_disable_entity_loader(false);
            $this->client = new SoapClient(
                $this->wsdl,
                array(
                    'encoding'  =>$this->charset,
                    'login'     =>$this->login,
                    'password'  =>$this->haslo
                )
            );

            // czytanie zapisanego identyfikatora
            if (isset($_SESSION['RaportsSession'])) {
                $this->sess = "".$_SESSION['RaportsSession'];
            } else {
                $this->sess = "";
            }

            // pobieranie sessji jeśli nie było zapisu identyfikatora
            if (strlen($this->sess) <= 0) {
                $this->sess = $this->client->pobierzSesje();
            }  else {
                // sprawdzenie czy sessja jest aktualna, jeśli nie pobranie nowego identyfikatora sesji
                if (!$this->client->sprawdzSesje($this->sess)) {
                    $this->sess = $this->client->pobierzSesje();
                }
            }
            // zapis identyfikatora sessji
            $_SESSION['RaportsSession'] = $this->sess;
            return $result = ['sess' => $this->sess, 'status' => true];
        } catch (SoapFault $fault) {
            // zwraca błąd
            return $result = ['faultcode' => $fault->faultcode, 'faultstring' => $fault->faultstring, 'status' => false];
        }
    }

    public function getRaportFirmaXML($krs) {
        $xml = $this->client->RaportFirmaXML(
            $this->sess,
            $krs,
            true,
            true,
            true,
            true,
            true,
            true,
            true);

        // otwarcie pliku do zapisu
        $fp = fopen("plik.xml", "w");

        // zapisanie danych
        fputs($fp, $xml);

        // zamknięcie pliku
        fclose($fp);

        return $xml;
    }

}