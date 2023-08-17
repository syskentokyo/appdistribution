<?php
//
// アプリアップロード処理を行うページ
//
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../app/common/commonrequireall.php');

use Valitron;

//
// 1. パラメータチェック
//


$validatorGet = new Valitron\Validator($_POST);

$validatorGet->rule('required', ['dataid', 'setactive']);
$validatorGet->rule('numeric', 'dataid');
$validatorGet->rule('numeric', 'setactive');


if($validatorGet->validate()) {

} else {
    exit();
}




$validatedDataID = htmlspecialchars($_POST['dataid'], ENT_QUOTES, 'UTF-8');
$validatedSetActive = htmlspecialchars($_POST['setactive'], ENT_QUOTES, 'UTF-8');







//
// DBを更新
//
$insertLastID = AppDBManager::UpdateDistributionActive($validatedDataID,$validatedSetActive);



//
// 完了後のページへ遷移
//
header("Location:./index.php");



