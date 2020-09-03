<?php
$payload = "93F9gAFwAG8AAJ0DQANaCQAAmAe\/BL0ExAToA+gD6APo\/+j\/6P++BLwEAAAAAA8=";
$binaryData = base64_decode($payload);
$unpackString = 'vCRC/CmessageID/CmessageVersion/CdeviceType/vHW/vFW/Cstatus/Vserial';
$header = unpack($unpackString, $binaryData);

echo bin2hex($binaryData). PHP_EOL;
print_r($header);
?>