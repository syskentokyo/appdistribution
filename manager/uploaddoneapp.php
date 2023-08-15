<?php
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../common/Commondefine.php');
require_once('../common/AppDBManager.php');
require_once ('../common/AppInfoJSON.php');
require_once ('../common/AppInfo.php');

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

    $appInfo =   AppDBManager::SelectFromAndroidApp($lastDataID);

}else{
    exit();
}


$appBaseURL =  CURRENT_BASE_URL;
$dirArray = explode('/',$_SERVER["REQUEST_URI"]);
for($i=1;$i < (count($dirArray)-2);$i++){
    $appBaseURL= $appBaseURL."/".$dirArray[$i];
}

//アプリ詳細ページのURL
$appDetaiPageURL = $appBaseURL ."/".DETAIL_APP_DIR;

$appUploadPageURL = $appBaseURL ."/".UPLOAD_APP_DIR;

$appManageDistributionPageURL = $appBaseURL ."/".MANAGE_DISTRIBUTION_DIR;


?>
<!doctype html>
<html lang="ja">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Done Upload </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</head>
<body>

<div  class="mx-auto"  style="width: 610px;">

    <h1 class="mt-5">Done Upload</h1>

    <div class="mt-5 row g-3">
    <a href="<?php echo $appUploadPageURL;  ?>" target="_blank">Upload Page</a>
    <a href="<?php echo $appManageDistributionPageURL;  ?>" target="_blank">Manage Distribution Page</a>
    </div>


    <div class="mt-5 row g-3">

        <h3>Detail App</h3>

        <div class="col-12">
            <label>Data ID</label>
            <div class="input-group">
              <?php echo $appInfo->dataID;  ?>
            </div>
        </div>

        <div class="col-12">
            <label>Memo1</label>
            <div class="input-group">
                <?php echo $appInfo->memo1;  ?>
            </div>
        </div>



        <div class="col-12">
            <label>App ID</label>
            <div class="input-group">
                <?php echo $appInfo->appInfoJSON->appid;  ?>
            </div>
        </div>


        <div class="col-12">
            <label>App Version</label>
            <div class="input-group">
                <?php echo $appInfo->appInfoJSON->appversion;  ?>
            </div>
        </div>

        <div class="col-12">
            <label>App Name</label>
            <div class="input-group">
                <?php echo $appInfo->appInfoJSON->bundleName;  ?>
            </div>
        </div>



        <div class="col-12">
            <label>Create Date</label>
            <div class="input-group">
                <?php echo $appInfo->createtime;  ?>
            </div>
        </div>

        <?php
        if($selectPlatform === AppFilePlatform::iOS){

            ?>

            <div class="col-12">
                <label>App File URL</label>
                <div class="input-group">

                    <a href="<?php echo $appDetaiPageURL."?dataid=".$lastDataID . "&platform=".$selectPlatform->value;  ?>" target="_blank">Detail App</a>
                </div>
            </div>


            <div class="col-12">
                <label>Xcode</label>
                <div class="input-group">
                    <?php echo $appInfo->appInfoJSON->xcode;  ?>
                </div>
            </div>

            <div class="col-12">
                <label>SDK Build</label>
                <div class="input-group">
                    <?php echo $appInfo->appInfoJSON->sdkBuild;  ?>
                </div>
            </div>

            <div class="col-12">
                <label>Min OS Version</label>
                <div class="input-group">
                    <?php echo $appInfo->appInfoJSON->minosverversion;  ?>
                </div>
            </div>

        <?php
        }else if($selectPlatform ===  AppFilePlatform::Android){

        ?>

            <div class="col-12">
                <label>App File URL</label>
                <div class="input-group">

                    <a href="<?php echo $appDetaiPageURL."?dataid=".$lastDataID . "&platform=".$selectPlatform->value;  ?>" target="_blank">Detail App</a>
                </div>
            </div>

            <div class="col-12">
                <label>Min SDK Version</label>
                <div class="input-group">
                    <?php echo $appInfo->appInfoJSON->androidMinSDK;  ?>
                </div>
            </div>

            <div class="col-12">
                <label>Target SDK Version</label>
                <div class="input-group">
                    <?php echo $appInfo->appInfoJSON->androidTargetSDK;  ?>
                </div>
            </div>



       <?php
        }
        ?>


    </div>



</div>

</body>
</html>







