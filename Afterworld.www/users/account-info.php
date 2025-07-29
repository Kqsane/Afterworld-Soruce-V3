<?php
header("Content-Type: application/json");
http_response_code(401);
?>
{"errors":[{"code":401,"message":"Unauthorized"}]}