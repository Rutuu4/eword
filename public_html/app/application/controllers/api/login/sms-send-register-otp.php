<?php


$otp=urlencode($otp);
$sms_mobile=$cmobile;

$handle=fopen("https://mobicomm.dove-sms.com//submitsms.jsp?user=Eworldedu&key=ac50c4b89fXX&mobile=+91$sms_mobile&message=Dear%20User%2C%20Your%20OTP%20for%20EWORLD%20Education%20login%20is%20$otp.%20Please%20do%20not%20share%20it%20with%20anyone.%20This%20OTP%20is%20valid%20for%2010%20minutes.%20-%20EWORLD%20Education&senderid=EWORDD&accusage=1&entityid=1701173815182134293&tempid=1707173823342086269","r");






?>