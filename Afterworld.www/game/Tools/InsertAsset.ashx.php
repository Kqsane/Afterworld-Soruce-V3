<?php

header("content-type: application/xml");

set_error_handler("customError");
$errors=array();

function customError($errno, $errstr) {
    global $errors;
    $errors[]=array('number'=>$errno,'message'=>$errstr);
}

$base_url = "https://sets.pizzaboxer.xyz/Game/Tools/InsertAsset.ashx";
$params = array_filter($_GET);

if(!empty($params)) {
    $query = http_build_query($params);
    $base_url .= "?" . $query;
}
       
if(!empty($base_url)) {
    $content = @file_get_contents($base_url);
    if ($content === false) {
        trigger_error("Cannot get content from URL: $base_url.", E_USER_WARNING);
    }
    else
    {
      $bom = pack('H*','EFBBBF');
      $content = preg_replace("/^$bom/", '', $content);
    }
}
restore_error_handler();

if (count($errors)>0) {
    echo '<Errors>';
    foreach($errors as $error) {
        echo '<Error>
                <Number>'.$error['number'].'</Number>
                <Message>'.$error['message'].'</Message>
              </Error>';
    }
    echo '</Errors>';
}
elseif (isset($content))
{
    echo trim($content);
}
?>