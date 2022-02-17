<?php
// © Avishkar Patil | customized by @Ayusman-Bieb
// ishitdas1985@gmail.com:ISHIT1985
$email = "avishek.t@gmail.com"; // Put Your hoichoi Email
$password = "kolkata@1"; // Put Your hoichoi Password

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://prod-api.viewlift.com/identity/signin?site=hoichoitv&deviceId=hoichoi-unofficial-api",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\"email\":\"$email\",\"password\":\"$password\"}",
  CURLOPT_HTTPHEADER => array(
        "authority: prod-api.viewlift.com",
        "accept: application/json, text/plain, */*",
        "sec-ch-ua-mobile: ?0",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36",
        "x-api-key: dtGKRIAd7y3mwmuXGk63u3MI3Azl1iYX8w9kaeg3",
        "content-type: application/json;charset=UTF-8",
        "origin: https://www.hoichoi.tv/",
        "sec-fetch-site: cross-site",
        "sec-fetch-mode: cors",
        "sec-fetch-dest: empty",
        "referer: https://www.hoichoi.tv/",
        "accept-language: en-US,en-IN;q=0.9,en;q=0.8"
  ),
));

$result = curl_exec($curl);
curl_close($curl);

$hcauth =json_decode($result, true);
$auth = $hcauth['authorizationToken'];

//echo "{authorizationToken: "."$auth"."}</br>";

#--------------------------# Authorization Completed
$url = $_GET["url"];
#$url = "https://www.hoichoi.tv/shows/watch-mandaar-online-season-1-episode-2";
if($url !=""){
$pid = str_replace('https://www.hoichoi.tv/', '/', $url); 
$hlink ="https://prod-api-cached-2.viewlift.com/content/pages?path=$pid&site=hoichoitv&includeContent=true&moduleOffset=0&moduleLimit=4&languageCode=en&countryCode=IN";

$curl2 = curl_init();
curl_setopt_array($curl2, array(
  CURLOPT_URL => $hlink,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: $auth",
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl2);
curl_close($curl2);

$idFinder =json_decode($response, true);
$id = $idFinder['modules'][1]['contentData'][0]['gist']['id'];
//echo $id;
#-----------------------# Found Video ID

$hclink ="https://prod-api.viewlift.com/entitlement/video/status?id=$id&deviceType=web_browser&contentConsumption=web";

$xurl = curl_init();
curl_setopt_array($xurl, array(
  CURLOPT_URL => $hclink,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json, text/plain, */*",
    "Authorization: $auth",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    "Origin: https://www.hoichoi.tv",
    "Host: prod-api.viewlift.com",
    "Referer: https://www.hoichoi.tv/",
    "Accept-Language: en-US,en;q=0.9",
    "Connection: keep-alive"
  ),
));
$result = curl_exec($xurl);
echo $result;
curl_close($xurl);
#-----------------------------# Found Video Details

}
else{
  $ex= array("error" => "Something went wrong, Check URL and Parameters !", "created_by" => "Avishkar Patil", "customized_by" => "Ayusman Bieb" );
  $error =json_encode($ex);

  echo $error;
}
?>
