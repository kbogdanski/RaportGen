<?php

/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-03-17
 * Time: 13:27
 */
class DCF {

    //STATIC ATTRIBUTES
    static $wagaWariantZerowy = 0.24;
    static $wagaWariantBranzowy = 0.35;
    static $wagaWariantSredniejDynamiki = 0.41;

    static public function calculateDCFvalue ($wartoscWariantZerowy, $wartoscWarianBranzowy, $wartoscWariantSredniejDynamiki) {
        return ($wartoscWariantZerowy*self::$wagaWariantZerowy
                + $wartoscWarianBranzowy*self::$wagaWariantBranzowy
                + $wartoscWariantSredniejDynamiki*self::$wagaWariantSredniejDynamiki);
    }

    public function __construct() {
        $this->wagaWariantZerowy = 0.24;
        $this->wagaWariantBranzowy = 0.35;
        $this->wagaWariantSredniejDynamiki = 0.41;
    }

}