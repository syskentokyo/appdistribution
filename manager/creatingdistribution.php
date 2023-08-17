<?php
//
// アプリアップロード処理を行うページ
//
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../app/common/Commondefine.php');
require_once('../app/common/AppDBManager.php');
require_once('../app/common/AppInfoJSON.php');
require_once('../app/common/DistributionInfo.php');

use CFPropertyList\CFPropertyList;
use ZipArchive;
use ApkParser\Parser;

//
// 1. パラメータチェック
//

if (!isset($_POST['selectIOS'])) {
    exit();
}

if (!isset($_POST['selectAndroid'])) {
    exit();
}

if (!isset($_POST['inputDetailMemo'])) {
    exit();
}


$validatedDetailMemo = htmlspecialchars($_POST['inputDetailMemo'], ENT_QUOTES, 'UTF-8');
$validatedSelectIOSID = htmlspecialchars($_POST['selectIOS'], ENT_QUOTES, 'UTF-8');
$validatedSelectAndroidID = htmlspecialchars($_POST['selectAndroid'], ENT_QUOTES, 'UTF-8');



$distribtionInfo = new DistributionInfo();

$distribtionInfo->iosappid = $validatedSelectIOSID;
$distribtionInfo->androidappid = $validatedSelectAndroidID;
$distribtionInfo->detailmemo = $validatedDetailMemo;
$distribtionInfo->isActive = 1;




//
// DBへ保存
//
$insertLastID = AppDBManager::InsertToDistribution($distribtionInfo);



//
// 完了後のページへ遷移
//
header("Location:./createdonedistribution.php?dataid=".$insertLastID);



