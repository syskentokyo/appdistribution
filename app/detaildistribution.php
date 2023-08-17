<?php
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );



require_once('./common/commonrequireall.php');



use Valitron;

$validatorGet = new Valitron\Validator($_GET);

$validatorGet->rule('required', ['dataid']);
$validatorGet->rule('numeric', 'dataid');


if($validatorGet->validate()) {

} else {
    exit();
}

$lastDataID = (int)$_GET['dataid'];


//
//
//
$distributionInfo = AppDBManager::SelectFromDistribution($lastDataID);
$iosAppInfo = null;
$androidAppInfo =null;
if($distributionInfo->iosappid > 0){
    $iosAppInfo = AppDBManager::SelectFromiOSApp($distributionInfo->iosappid);
}

if($distributionInfo->androidappid > 0){
    $androidAppInfo = AppDBManager::SelectFromAndroidApp($distributionInfo->androidappid);
}




//
// URL処理
//
$appBaseURL =  CURRENT_BASE_URL;
$dirArray = explode('/',$_SERVER["REQUEST_URI"]);
for($i=1;$i < (count($dirArray)-2);$i++){
    $appBaseURL= $appBaseURL."/".$dirArray[$i];
}

//アプリ詳細ページのURL
$appDetaiPageURL = $appBaseURL ."/".DETAIL_APP_DIR;
$appListPageURL = $appBaseURL ."/".APP_LIST_DIR;
$iosInstallPlistURL =$appBaseURL ."/".APP_IOS_INSTALL_PLIST_FILEPATH;
//$androidAPKURL =$appBaseURL ."/".APP_SAVEDIR.$appInfo->savedirname."/".SAVEDIR_APP_ANDROID_FILE_NAME;


$appUploadPageURL = $appBaseURL ."/".UPLOAD_APP_DIR;

$appManageDistributionPageURL = $appBaseURL ."/".MANAGE_DISTRIBUTION_DIR;
$appCreateDistributionPageURL = $appBaseURL ."/".CREATE_DISTRIBUTION_DIR;

?>
<!doctype html>
<html lang="ja">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Distribution details </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</head>
<body>
<?php
require_once('./commonheader.php');
?>

<div  class="mx-auto"  style="width: 610px;">

    <h1 class="mt-5">Distribution details</h1>



    <div class="mt-5 row g-3 border">

        <h3>#<?php echo $distributionInfo->dataid;  ?></h3>


        <div class="col-12">
            <label>Memo</label>
            <div class="input-group">
                <?php echo str_replace("\n","</br>",$distributionInfo->detailmemo);  ?>
            </div>
        </div>


        <h4 class="mt-5">iOS</h4>
        <?php
        if($iosAppInfo != null){
            ?>
            <div class="col-12">
                <label>iOS App ID</label>
                <div class="input-group">
                    <a href="<?php echo $appDetaiPageURL."?dataid=".$iosAppInfo->dataID . "&platform=".AppFilePlatform::iOS->value;  ?>" target="_blank"><?php echo "#".$iosAppInfo->dataID;  ?></a>
                </div>
            </div>

            <div class="col-12">
                <label>iOS App Create Time</label>
                <div class="input-group">
                    <?php echo "".$iosAppInfo->createtime;  ?>
                </div>
            </div>


            <div class="col-12">
                <label>Install</label>
                <div class="input-group">
                    <?php

                    echo "<a class=\"btn btn-primary col-6\" href=\"itms-services://?action=app-manifest&url=".$iosInstallPlistURL ."?dataid=".$iosAppInfo->dataID . "&platform=".AppFilePlatform::iOS->value."\">Install</a>";

                    ?>
                </div>
            </div>

            <div class="col-12">
                <label>Other</label>
                <div class="input-group">
                    <?php echo "</br>";  ?>
                </div>
            </div>

            <?php

        }else{
            ?>
            <div class="col-12">
                <label>iOS</label>
                <div class="input-group">
                    <?php echo "No Select";  ?>
                </div>
            </div>

            <?php
        }

        ?>

        <h4 class="mt-5">Android</h4>

        <?php
        if($androidAppInfo != null){
            ?>
            <div class="col-12">
                <label>Android App ID</label>
                <div class="input-group">
                    <a href="<?php echo $appDetaiPageURL."?dataid=".$iosAppInfo->dataID . "&platform=".AppFilePlatform::Android->value;  ?>" target="_blank"><?php echo "#".$androidAppInfo->dataID;  ?></a>
                </div>
            </div>

            <div class="col-12">
                <label>Android App Create Time</label>
                <div class="input-group">
                    <?php echo "".$androidAppInfo->createtime;  ?>
                </div>
            </div>


            <div class="col-12">
                <label>Install</label>
                <div class="input-group">
                    <?php
                    $androidAPKURL =$appBaseURL ."/".APP_SAVEDIR.$androidAppInfo->savedirname."/".SAVEDIR_APP_ANDROID_FILE_NAME;
                    echo "<a class=\"btn btn-primary col-6\" href=\"".$androidAPKURL."\">Install</a>";


                    ?>
                </div>
            </div>


            <div class="col-12">
                <label>Other</label>
                <div class="input-group">
                    <?php echo "</br>";  ?>
                </div>
            </div>

            <?php


        }else{
            ?>
            <div class="col-12">
                <label>Android</label>
                <div class="input-group">
                    <?php echo "No Select";  ?>
                </div>
            </div>
            <?php
        }

        ?>



    </div>


    <div class="col-12">
        <label></label>
        <div class="input-group">
            <?php echo "";  ?>
        </div>
    </div>



</div>

</body>
</html>







