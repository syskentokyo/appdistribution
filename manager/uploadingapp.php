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
use ApkParser\Parser;

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

if((int)$validatedPlatform === AppFilePlatform::iOS->value){
    $selectPlatform = AppFilePlatform::iOS;

}else if((int)$validatedPlatform === AppFilePlatform::Android->value){
    $selectPlatform = AppFilePlatform::Android;

}else{
    exit();
}

//
// 　アップロードしたファイルが意図したファイルか軽くチェック
//
if( $selectPlatform == AppFilePlatform::iOS){
    if(!preg_match("/^[^.]+.ipa$/",basename($uploadtedAppFile['name']))){
        //ipaファイル以外の場合
        exit();
    }

}else if( $selectPlatform == AppFilePlatform::Android){
    if(!preg_match("/^[^.]+.apk$/",basename($uploadtedAppFile['name']))){
        //ipaファイル以外の場合
        exit();
    }

}else{

}


//
// 保存先作成
//
$saveTimeTxt = date("YmdHis");
$saveDirName ='app'. $saveTimeTxt;
$saveDirPath = APP_SAVEDIR_PATH . $saveDirName;

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

//
// DBへ保存
//
    $insertLastID = AppDBManager::InsertToiOSApp($appinfoJson,$saveDirName,$validatedMemo1);


}else if($selectPlatform === AppFilePlatform::Android){



    $apk = new \ApkParser\Parser($saveAppFilePath,['manifest_only' => false]);
    $manifest = $apk->getManifest();
    $labelResourceId = $apk->getManifest()->getApplication()->getLabel();
    $appName = $apk->getResources($labelResourceId)[0];


    $appinfoJson->bundleName =  $appName;
    $appinfoJson->appid =  $manifest->getPackageName() ;
    $appinfoJson->xcode = "No iOS Build";
    $appinfoJson->androidTargetSDK = $manifest->getTargetSdkLevel();
    $appinfoJson->androidMinSDK = $manifest->getMinSdkLevel();
    $appinfoJson->appversion = $manifest->getVersionName();


    //
    // DBへ保存
    //
    $insertLastID = AppDBManager::InsertToAndroidApp($appinfoJson,$saveDirName,$validatedMemo1);

}








//
// 完了後のページへ遷移
//
header("Location:./uploaddoneapp.php?dataid=".$insertLastID."&platform=".$selectPlatform->value);



