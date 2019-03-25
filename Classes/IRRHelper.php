<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2019-02-28
 * Time: 22:33
 */

class IRRHelper{

    //Adapted from Javascript version here: https://gist.github.com/ghalimi/4591338
    //
    // Copyright (c) 2012 Sutoiku, Inc. (MIT License)
    //
    // Some algorithms have been ported from Apache OpenOffice:
    //
    /**************************************************************
     *
     * Licensed to the Apache Software Foundation (ASF) under one
     * or more contributor license agreements.  See the NOTICE file
     * distributed with this work for additional information
     * regarding copyright ownership.  The ASF licenses this file
     * to you under the Apache License, Version 2.0 (the
     * "License"); you may not use this file except in compliance
     * with the License.  You may obtain a copy of the License at
     *
     *   http://www.apache.org/licenses/LICENSE-2.0
     *
     * Unless required by applicable law or agreed to in writing,
     * software distributed under the License is distributed on an
     * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
     * KIND, either express or implied.  See the License for the
     * specific language governing permissions and limitations
     * under the License.
     *
     *************************************************************/

    public static function IRR($values, $guess=0.000001) {
        // Credits: algorithm inspired by Apache OpenOffice

        // Initialize dates and check that values contains at least one positive value and one negative value
        $dates = array();
        $positive = false;
        $negative = false;
        foreach($values as $index=>$value){
            $dates[] = ($index===0) ? 0 : $dates[$index-1] + 365;
            if($values[$index] > 0) $positive = true;
            if($values[$index] < 0) $negative = true;
        }

        // Return error if values does not contain at least one positive value and one negative value
        if(!$positive || !$negative) return null;

        // Initialize guess and resultRate
        $resultRate = $guess;

        // Set maximum epsilon for end of iteration
        $epsMax = 0.0000000001;

        // Set maximum number of iterations
        $iterMax = 50;

        // Implement Newton's method
        $newRate;
        $epsRate;
        $resultValue;
        $iteration = 0;
        $contLoop = true;
        while($contLoop && (++$iteration < $iterMax)){
            $resultValue = self::irrResult($values, $dates, $resultRate);
            $newRate = $resultRate - $resultValue / self::irrResultDeriv($values, $dates, $resultRate);
            $epsRate = abs($newRate - $resultRate);
            $resultRate = $newRate;
            $contLoop = ($epsRate > $epsMax) && (abs($resultValue) > $epsMax);
        }

        if($contLoop) return null;

        // Return internal rate of return
        return $resultRate;
    }

    // Calculates the resulting amount
    public static function irrResult($values, $dates, $rate){
        $r = $rate + 1;
        $result = $values[0];
        for($i=1;$i<count($values);$i++){
            $result += $values[$i] / pow($r, ($dates[$i] - $dates[0]) / 365);
        }
        return $result;
    }

    // Calculates the first derivation
    public static function irrResultDeriv($values, $dates, $rate){
        $r = $rate + 1;
        $result = 0;
        for($i=1;$i<count($values);$i++){
            $frac = ($dates[$i] - $dates[0]) / 365;
            $result -= $frac * $values[$i] / pow($r, $frac + 1);
        }
        return $result;
    }

}