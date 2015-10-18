<?php
function getSequenceByRand(&$sequence, $start, $finish, $randNum) {
    $offset = $finish - $start + 1;
    $fac = array(1, 1, 2, 6, 24, 120, 720, 5040, 40320, 362880, 3628800, 39916800);
    $modNumber = $randNum % $fac[$offset];
    $staticNumber = array();
    for ($i = 1; $i <= $offset; ++$i) {
        $staticNumber[] = $i;
    }
    for ($i = 1; $i <= $offset; ++$i) {
        $div = intval($modNumber / $fac[$offset - $i]);
        $modNumber = $modNumber % $fac[$offset - $i];
        $sequence[$start + $i + 1] = $staticNumber[$div] - 1 + $start;
        array_splice($staticNumber, $div, 1);
    }
}
