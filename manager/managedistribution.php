<?php
namespace Syskentokyo\AppDistribution;

require_once( '../vendor/autoload.php' );


require_once('../common/Commondefine.php');
require_once('../common/AppDBManager.php');
require_once ('../common/AppInfoJSON.php');
require_once ('../common/AppInfo.php');





//
//
//


$appInfoiOSArray =   AppDBManager::SelectAllFromiOSApp();
$appInfoAndroidArray =   AppDBManager::SelectAllFromAndroidApp();



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
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" style="width: 610px;">
        <a href="<?php echo $appUploadPageURL;  ?>" >Upload Page</a>
        <a href="<?php echo $appManageDistributionPageURL;  ?>" >Manage Distribution Page</a>
        <a href="<?php echo $appCreateDistributionPageURL;  ?>" >Create Distribution Page</a>
    </div>
</nav>
<div  class="mx-auto"  style="width: 610px;">

    <h1 class="m-3">Selec App</h1>

    <form class="mt-5 row g-3"  enctype="multipart/form-data" method="post" action="uploadingapp.php">


        <div class="col-12">
            <label for="selectPlatform" class="form-label">Platform</label>
            <select name='selectPlatform'  id="selectPlatform" class="form-select">
                <option  value="-999" selected>No Select</option>
                <option value="2" >Android</option>
            </select>
        </div>


    </div>



</div>

</body>
</html>







