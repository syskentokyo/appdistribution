<?php
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../app/common/commonrequireall.php');

use Valitron;

$validatorGet = new Valitron\Validator($_GET);




//
//
//
$distributionInfoArray = AppDBManager::SelectAllFromDitribution();

$distributionCollectionInfoArray = Array();

foreach ($distributionInfoArray as  $distributionInfo) {

    $distributionCollectionInfo = new DistributionCollectionInfo();
    $distributionCollectionInfo->dataID = $distributionInfo->dataid;
    $distributionCollectionInfo->detailmemo = $distributionInfo->detailmemo;
    $distributionCollectionInfo->isActive = $distributionInfo->isActive;
    $distributionCollectionInfo->createtime = $distributionInfo->createtime;

    $iosAppInfo = null;
    $androidAppInfo = null;

    if ($distributionInfo->iosappid > 0) {
        $iosAppInfo = AppDBManager::SelectFromiOSApp($distributionInfo->iosappid);
    }

    if ($distributionInfo->androidappid > 0) {
        $androidAppInfo = AppDBManager::SelectFromAndroidApp($distributionInfo->androidappid);
    }

    $distributionCollectionInfo->iosAppInfo =$iosAppInfo;
    $distributionCollectionInfo->androidAppInfo = $androidAppInfo;


    $distributionCollectionInfoArray[]= $distributionCollectionInfo;
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


    <title>Manage Distribution List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</head>
<body>
<?php
require_once('./commonheader.php');
?>
<div  class="mx-auto"  style="width: 610px;">

    <h1 class="mt-5">Manage Distribution List</h1>




    <div class="mt-5 row g-3">

        <h3>List</h3>

        <?php
            foreach ($distributionCollectionInfoArray as $distributionCollectionInfo){
        ?>
        <div class="row g-3 border">
            <h4>#<?php echo $distributionCollectionInfo->dataID;  ?></h4>

            <div class="col-12">
                <label>Distribution</label>
                <div class="input-group">
                    <?php
                    if($distributionCollectionInfo->isActive===1){
                    ?>
                    <form class="col-6"  enctype="multipart/form-data" method="post" action="changingdistributionactive.php">
                        <input type="hidden" name="dataid" value=<?php echo "\"".$distributionCollectionInfo->dataID."\""; ?>>
                        <input type="hidden" name="setactive" value=<?php echo "\""."0"."\""; ?>>
                        <button type="submit" class="btn btn-success ">On</button>
                    </form>
                    <?php
                    }else{
                    ?>
                        <form class="col-6"  enctype="multipart/form-data" method="post" action="changingdistributionactive.php">
                            <input type="hidden" name="dataid" value=<?php echo "\"".$distributionCollectionInfo->dataID."\""; ?>>
                            <input type="hidden" name="setactive" value=<?php echo "\""."1"."\""; ?>>
                        <button type="submit" class="btn btn-outline-secondary ">Off</button>
                        </form>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="col-12">
            <label>Memo</label>
            <div class="input-group">
                <?php echo str_replace("\n","</br>",$distributionCollectionInfo->detailmemo)."<br>";  ?>
            </div>
            </div>

            <div class="col-12">
            <label>Create Time</label>
            <div class="input-group">
                <?php echo $distributionCollectionInfo->createtime;  ?>
            </div>
            </div>

            <div class="col-md-6 mt-3 mb-3  border">
                <h3>iOS</h3>
                <?php
                    if($distributionCollectionInfo->iosAppInfo !=null){
                ?>
                <div class="col-6">
                    <label><?php echo "#".$distributionCollectionInfo->iosAppInfo->dataID;?></label>
                    <div class="input-group">
                        <a href="<?php echo $appDetaiPageURL."?dataid=".$distributionCollectionInfo->iosAppInfo->dataID . "&platform=".AppFilePlatform::iOS->value;  ?>" target="_blank">Detail App</a>
                    </div>
                </div>

                        <div class="col-12">
                            <label>Create Time</label>
                            <div class="input-group">
                                <?php echo "".$distributionCollectionInfo->iosAppInfo->createtime;?>
                            </div>
                        </div>

                <?php
                    }else{
                ?>
                <div class="col-3">
                        <label></label>
                        <div class="input-group">
                           None
                        </div>
                </div>

                <?php
                }
                ?>

            </div>

            <div class="col-md-6 mt-3 mb-3  border">
                <h3>Android</h3>
                <?php
                if($distributionCollectionInfo->androidAppInfo !=null){
                    ?>
                    <div class="col-6">
                        <label><?php echo "#".$distributionCollectionInfo->androidAppInfo->dataID;?></label>
                        <div class="input-group">
                            <a href="<?php echo $appDetaiPageURL."?dataid=".$distributionCollectionInfo->androidAppInfo->dataID . "&platform=".AppFilePlatform::Android->value;  ?>" target="_blank">Detail App</a>
                        </div>
                    </div>

                    <div class="col-12">
                        <label>Create Time</label>
                        <div class="input-group">
                            <?php echo "".$distributionCollectionInfo->androidAppInfo->createtime;?>
                        </div>
                    </div>

                    <?php
                }else{
                    ?>
                    <div class="col-3">
                        <label></label>
                        <div class="input-group">
                            None
                        </div>
                    </div>

                    <?php
                }
                ?>

            </div>
        </div>




        <?php
            }
        ?>

    </div>



</div>

</body>
</html>







