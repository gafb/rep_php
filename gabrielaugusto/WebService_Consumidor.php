<?php

    $xml = simplexml_load_file("http://sistemas.unasus.gov.br/ws_cbo/cbo.php");
    
    $service = array_fill(0,sizeof($xml->cbo_response), "");
    $i =0; 
    foreach ($xml->cbo_response as $res)
    {   
        $service[$i] =$res->cbo." ".$res->descricao;
        $i = $i +1;
    }
    //print_r($service);
?>
