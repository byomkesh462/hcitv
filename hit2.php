<?php
$auth = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiI4MDMzNjJiMC1kNjkyLTExZTctOWQ3Ny00ZjMwMTI3OTAwZTMiLCJzaXRlIjoiaG9pY2hvaXR2Iiwic2l0ZUlkIjoiN2ZhMGVhOWEtOTc5OS00NDE3LTk5ZjUtY2JiNTM0M2M1NTFkIiwiZW1haWwiOiJzcml0YW1hNTA4QGdtYWlsLmNvbSIsImlwYWRkcmVzc2VzIjoiNDkuMzcuMzkuMSwgMTAuMTIwLjQwLjI1LCA1Mi41NS4yMDguMjQzLCAxMzAuMTc2Ljk4LjE2MCIsImNvdW50cnlDb2RlIjoiSU4iLCJwb3N0YWxjb2RlIjoiNzAwMDE5IiwicHJvdmlkZXIiOiJ2aWV3bGlmdCIsImRldmljZUlkIjoiYnJvd3Nlci03NjY4MmEzMS04YzA5LTRkMzMtZTNlMS02NGJhMTlkYTNiOTAiLCJpZCI6IjgwMzM2MmIwLWQ2OTItMTFlNy05ZDc3LTRmMzAxMjc5MDBlMyIsImlhdCI6MTY0NTg2OTA1MCwiZXhwIjoxNjQ2NDczODUwfQ.GxoNAIMCJm6Kk7RkxnorJtVCrhcfQ-OXK2ikPZmhTMY";

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
curl_close($xurl);
#-----------------------------# Found Video Details
$hoichoi =json_decode($result, true);

$title = $hoichoi['video']['gist']['title'];
#$year = $hoichoi['video']['gist']['year'];
if(is_null($hoichoi['video']['gist']['year'])) {
    $year = 2022;
}
$plink = $hoichoi['video']['gist']['permalink'];
$des = $hoichoi['video']['gist']['description'];
$lang = $hoichoi['video']['gist']['languageCode'];
$category = $hoichoi['video']['gist']['primaryCategory']['title'];
#$posterImage = $hoichoi['video']['gist']['posterImageUrl']; //poster Image
if(is_null($hoichoi['video']['gist']['posterImageUrl'])) {
    $posterImage = "https://www.hoichoi.tv/";
}
$videoImage = $hoichoi['video']['gist']['videoImageUrl']; // Video Thumbnail
$drm = $hoichoi['video']['gist']['drmEnabled']; // DRM checking
if(is_null($hoichoi['video']['gist']['metadata'][2]['value'])) {
    $imdb = "not defined";
}
#$imdb = $hoichoi['video']['gist']['metadata'][2]['value']; // imdb id
$srt = $hoichoi['video']['contentDetails']['closedCaptions'][0]['url']; //srt subtitle
$hls = $hoichoi['video']['streamingInfo']['videoAssets']['hls']; // auto all qualities included
$h270 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 270p
$h360 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 360p
$h720 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 720p


 $apii = array("created_by" => "Avishkar Patil", "customized_by" => "Ayusman Bieb", "id" => $id, "lang" => $lang, "category" => $category, "title" => $title, "year" => $year, "permalink" => $plink, "description" => $des, "posterImage" => $posterImage, "videoImage" => $videoImage, "drmEnabled" => $drm, "imdb_id" => $imdb, "hls" => $hls, "270p" => $h270, "360p" => $h360, "720p" => $h720, "subtitle" => $srt);

 $api =json_encode($apii, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

header("X-UA-Compatible: IE=edge");
header("Content-Type: application/json");
echo $api;
}
else{
  $ex= array("error" => "Something went wrong, Check URL and Parameters !", "created_by" => "Avishkar Patil", "customized_by" => "Ayusman Bieb" );
  $error =json_encode($ex);

  echo $error;
}
?>
