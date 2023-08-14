<?php
//
// アプリアップロード処理を行うページ
//
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../common/Commondefine.php');
require_once('../common/AppDBManager.php');
require_once ('../common/AppInfoJSON.php');

use CFPropertyList\CFPropertyList;
use ZipArchive;

//
// 1. パラメータチェック
//

if (!isset($_FILES['appfile']['error']) || !is_int($_FILES['appfile']['error'])) {
    exit();
}

if (!isset($_POST['inputMemo1'])) {
    exit();
}

if (!isset($_POST['selectPlatform'])) {
    exit();
}


$validatedMemo1 = htmlspecialchars($_POST['inputMemo1'], ENT_QUOTES, 'UTF-8');
$validatedPlatform = htmlspecialchars($_POST['selectPlatform'], ENT_QUOTES, 'UTF-8');
$uploadtedAppFile = $_FILES['appfile'];


$selectPlatform = AppFilePlatform::iOS;

if($validatedPlatform === "1"){
    $selectPlatform = AppFilePlatform::iOS;

}else if($validatedPlatform === "2"){
    $selectPlatform = AppFilePlatform::Android;

}else{
    exit();
}



//
// 保存先作成
//
$saveTimeTxt = date("YmdHis");
$saveDirName ='app'. $saveTimeTxt;
$saveDirPath = SAVEDIR_BASEPATH . $saveDirName;

mkdir($saveDirPath, 0766);



//
// アプリファイル保存
//
$saveAppFileBaseFilePath= $saveDirPath."/";
$saveAppFilePath="";
if($selectPlatform === AppFilePlatform::iOS){

    $saveAppFilePath = $saveAppFileBaseFilePath .SAVEDIR_APP_IOS_FILE_NAME;

}else if($selectPlatform === AppFilePlatform::Android){
    $saveAppFilePath = $saveAppFileBaseFilePath .SAVEDIR_APP_ANDROID_FILE_NAME;
}

if($saveAppFilePath!=="") {
    copy($uploadtedAppFile['tmp_name'], $saveAppFilePath);
}else{
    exit();
}




//
// アプリパラメータ解析
//
$appinfoJson = new AppInfoJSON();

$zipTempDir = sys_get_temp_dir() . '/' . uniqid();
mkdir($zipTempDir);

if($selectPlatform === AppFilePlatform::iOS){
    $zip = new ZipArchive();
    if ($zip->open($saveAppFilePath) !== TRUE) {
        return FALSE;
    }

    $zip->extractTo($zipTempDir);//解凍
    $zip->close();


     $infoPlistPathArray= glob($zipTempDir.'/Payload/*.app/Info.plist');

     if(count($infoPlistPathArray) !== 1){
        exit();
     }

     $infoPlistPath = $infoPlistPathArray[0];

    //
    // ここでライブラリをつかって、info.plistを読み込んでいる
    //
    $infoPlistXML = new CFPropertyList( $infoPlistPath, CFPropertyList::FORMAT_BINARY );

    $infoPlistDataArray =  $infoPlistXML->toArray();

//    echo '<pre>';
//    var_dump( $infoPlistXML->toArray());
//    echo '</pre>';

    $appinfoJson->bundleName = $infoPlistDataArray["CFBundleName"];
    $appinfoJson->appid = $infoPlistDataArray["CFBundleIdentifier"];
    $appinfoJson->xcode = $infoPlistDataArray["DTXcodeBuild"];
    $appinfoJson->sdkBuild = $infoPlistDataArray["DTSDKBuild"];
    $appinfoJson->minosverversion = $infoPlistDataArray["MinimumOSVersion"];
    $appinfoJson->appversion = $infoPlistDataArray["CFBundleShortVersionString"];




}else if($selectPlatform === AppFilePlatform::Android){


}





//
// DBへ保存
//
$dbInstance = AppDBManager::InsertToiOSApp($appinfoJson,$saveDirName,$validatedMemo1);



