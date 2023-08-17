<?php
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../app/common/commonrequireall.php');




//
//
//


$appInfoiOSArray =   AppDBManager::SelectAllFromiOSApp();
$appInfoAndroidArray =   AppDBManager::SelectAllFromAndroidApp();


//
// URL処理
//
$appBaseURL =  CURRENT_BASE_URL;
$dirArray = explode('/',$_SERVER["REQUEST_URI"]);
for($i=1;$i < (count($dirArray)-2);$i++){
    $appBaseURL= $appBaseURL."/".$dirArray[$i];
}

//アプリ詳細ページのURL
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


    <title>Install App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</head>
<body>
<?php
require_once('./commonheader.php');
?>

<div  class="mx-auto"  style="width: 610px;">

    <h1 class="m-5">Create Distribution Page</h1>

    <form class="mt-5 row g-3"  enctype="multipart/form-data" method="post" action="creatingdistribution.php">


        <div class="col-12">
            <label for="selectIOS" class="form-label">iOS</label>
            <select name='selectIOS'  id="selectIOS" class="form-select">
                <option  value="-999" selected>No Select</option>

                <?php
                    foreach ($appInfoiOSArray as $appinfoiOS){
                        $appinfo = $appinfoiOS;

                        echo "<option value=\"".$appinfo->dataID."\" >"."#".$appinfo->dataID."  ::::  ".$appinfo->createtime ."</option>";
                    }

                ?>

            </select>
        </div>

        <div class="mt-5 col-12">
            <label for="selectAndroid" class="form-label">Android</label>
            <select name='selectAndroid'  id="selectAndroid" class="form-select">
                <option  value="-999" selected>No Select</option>

                <?php
                foreach ($appInfoAndroidArray as $appinfoAndroid){
                    $appinfo = $appinfoAndroid;

                    echo "<option value=\"".$appinfo->dataID."\" >"."#".$appinfo->dataID."  ::::  ".$appinfo->createtime ."</option>";
                }

                ?>

            </select>
        </div>

        <div class="mt-5 col-12">
            <label for="inputDetailMemo" class="form-label">Detail</label>
            <textarea name='inputDetailMemo' class="form-control" id="inputDetailMemo" placeholder=""></textarea>
        </div>


        <div class="mt-5 col-12">
            <button type="submit" class="btn btn-primary">Create </button>
        </div>
    </form>

</div>



</div>

</body>
</html>







