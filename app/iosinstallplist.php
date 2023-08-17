<?php

namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );



require_once('./common/commonrequireall.php');


use Valitron;

$validatorGet = new Valitron\Validator($_GET);

$validatorGet->rule('required', ['dataid', 'platform']);
$validatorGet->rule('numeric', 'dataid');
$validatorGet->rule('numeric', 'platform');


if($validatorGet->validate()) {

} else {
    exit();
}

$lastDataID = (int)$_GET['dataid'];
$selectPlatform = AppFilePlatform::iOS;

if((int)$_GET['platform'] === AppFilePlatform::iOS->value){
    $selectPlatform = AppFilePlatform::iOS;

}else if((int)$_GET['platform'] === AppFilePlatform::Android->value){
    $selectPlatform = AppFilePlatform::Android;

}else{
    exit();
}


//
//
//
$appInfo = new AppInfo();


if($selectPlatform === AppFilePlatform::iOS){
    $appInfo =   AppDBManager::SelectFromiOSApp($lastDataID);


}else if($selectPlatform ===  AppFilePlatform::Android){
exit();


}else{
    exit();
}


$appBaseURL =  CURRENT_BASE_URL;
$dirArray = explode('/',$_SERVER["REQUEST_URI"]);
for($i=1;$i < (count($dirArray)-2);$i++){
    $appBaseURL= $appBaseURL."/".$dirArray[$i];
}



$iosIpaURL =$appBaseURL ."/".APP_SAVEDIR.$appInfo->savedirname."/".SAVEDIR_APP_IOS_FILE_NAME;
$iosIcon57URL =$appBaseURL ."/".IOS_ICON_57_PATH;
$iosIcon512URL =$appBaseURL ."/".IOS_ICON_512_PATH;
$iosAppID = $appInfo->appInfoJSON->appid;
$iosAppVersion = $appInfo->appInfoJSON->appversion;
$iosAppName = $appInfo->appInfoJSON->bundleName;

$baseXMLPath = "../".APP_IOS_INSTALL_PLIST_BASE_XML_FILEPATH;

$targetReplaceIPA = "28b334495818a749399108cd262f2e52eb35e43_ipa";
$targetReplaceIcon57 = "28b334495818a749399108cd262f2e52eb35e43_icon57";
$targetReplaceIcon512 = "28b334495818a749399108cd262f2e52eb35e43_icon512";
$targetReplaceAppID = "28b334495818a749399108cd262f2e52eb35e43_appid";
$targetReplaceAppVersion = "28b334495818a749399108cd262f2e52eb35e43_appversion";
$targetReplaceAppName = "28b334495818a749399108cd262f2e52eb35e43_appname";



$baseXMLTxt = file_get_contents($baseXMLPath);

$baseXMLTxt = str_replace($targetReplaceIPA, $iosIpaURL, $baseXMLTxt);
$baseXMLTxt = str_replace($targetReplaceIcon57, $iosIcon57URL, $baseXMLTxt);
$baseXMLTxt = str_replace($targetReplaceIcon512, $iosIcon512URL, $baseXMLTxt);
$baseXMLTxt = str_replace($targetReplaceAppID, $iosAppID, $baseXMLTxt);
$baseXMLTxt = str_replace($targetReplaceAppVersion, $iosAppVersion, $baseXMLTxt);
$baseXMLTxt = str_replace($targetReplaceAppName, $iosAppName, $baseXMLTxt);




//
// 出力
//
header( 'Content-Type: application/xml' );
echo $baseXMLTxt;


