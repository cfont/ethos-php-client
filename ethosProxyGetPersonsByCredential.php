<?php
header('Content-Type: application/json');

$apikey="ey-your-api-key-here";


// Don't change these URLs
$ethosurl='https://integrateapi.elluciancloud.com';
$authurlpath='/auth';
$apiurlpath='/api/';
$ethosDataModel="persons";
$credentialID="some-banner-id-here";
$credentialType="bannerId";
//TODO: provide options and documentation for credentialType
//$publishurl='https://integrateapi.elluciancloud.com/publish';
//$checkurl='https://integrateapi.elluciancloud.com/consume';
//$consumeurl='https://integrateapi.elluciancloud.com/consume?lastProcessedID=0&max=1';

// Header options for authentication request
$authOpts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"Authorization: Bearer ".$apikey."\r\n" .
              "Content-Type: application/json\r\n" .
              "charset: UTF-8"
  )
);

$authContext = stream_context_create($authOpts);

try {
  $authtoken = file_get_contents($ethosurl.$authurlpath, false, $authContext);
  if ($authtoken === false){
    echo "\r\nWe must have done something wrong because our attempt returned false\r\n";
  } else {
    //echo "The authorization token is: ".$authtoken;
  }
} catch (Exception $e) {
  echo "We caught an exeption: " . $e . "\r\n\r\n";
}

// Header options for proxy GET request
$proxyGetOpts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$authtoken."\r\n" .
              "Content-Type: application/vnd.hedtech.s.v2.json\r\n" .
              "Accept: application/json",
    'timeout' => 60
  )
);

$proxyGetContext = stream_context_create($proxyGetOpts);

// Lets use the authtoken to get some data

try {
  $getData = file_get_contents($ethosurl.$apiurlpath.$ethosDataModel."?credential.value=".$credentialID."&credential.type=".$credentialType, false, $proxyGetContext);
  if ($getData === false){
    echo "\r\nWe must have done something wrong because our attempt returned false\r\n";
  } else {
    echo json_encode(json_decode($getData), JSON_PRETTY_PRINT);
  }
} catch (Exception $e) {
  echo "We caught an exeption: " . $e . "\r\n";
}

?>
