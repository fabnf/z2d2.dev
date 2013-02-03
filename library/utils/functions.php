<?php


/**
 * Creates output of the subject passed, by default halting further execution
 * 
 * @param mixed $subject Element that you need insight into
 * 
 * @param bool  $halt    Flag (optional) if TRUE execution halts after dump
 * 
 * @return void
 */
function trap($subject, $halt=true)
{
    $cli = (isset($_SERVER['TERM'])) ? true : false;
    
    try {
        throw new Exception('DUMMIE');
    } catch (Exception $xc) {
    }
    
    ob_start();
    var_dump($subject);
    $dump = ob_get_clean();
    
    $details = array(
        'Details'     => print_r($subject, true),
        'Stack trace' => print_r($xc->getTrace(), true),
        'Dump'        => $dump
    );
    
    echo ($cli!==true) ? "<pre>" : chr(27)."[H".chr(27)."[2J"."\n";
    
    foreach ($details as $descr => $data) {
        
        if (true===$cli) {
            echo "\n\n------------- {$descr} -----------------\n\n{$data}\n";
        } else {
            echo "<h3>{$descr}</h3><br>{$data}<br>";
        }
    }
    
    echo ($cli!==true) ? "<pre>" : "\n";
    
    if ($halt===true) {
        exit();
    }
}