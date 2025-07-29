<?php
require $_SERVER["DOCUMENT_ROOT"] . "/config/main.php";
require $_SERVER["DOCUMENT_ROOT"] . "/config/SOAP.php";
$apikey = "c2a2ab26b9010a0b163c156256bbcca25bc";
$checkget = urldecode($_GET["apikey"]);
if ($checkget == $apikey) {
    $JobId = filter_var(
        $_GET["JobId"],
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        FILTER_FLAG_NO_ENCODE_QUOTES
    );
    $jobsquery = $con->prepare(
        "SELECT * FROM `jobs` WHERE `id` = :id"
    );
    $jobsquery->execute(["id" => $JobId]);
    $jobs = $jobsquery->fetch();
    $serviceport = $jobs["serviceport"];
    $sql =
        "DELETE FROM `jobs` WHERE `id` = :jobid";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        ":jobid" => $JobId,
    ]);
	placeStop($JobId, $serviceport);
    // Seems to cause issues.
    /*
if($timediff > 60){
if($_GET['Type'] == 2018){
echo placeStop($JobId,$serviceport);
}else{
echo placeStop($JobId,$serviceport);
}
}
*/
} else {
    header("HTTP/1.0 401 Unauthorized", true, 401);
    http_response_code(401);
    exit();
}
?>
