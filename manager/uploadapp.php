<?php
//
// アプリファイルのアップロードページ
//
namespace Syskentokyo\AppDistribution;

?>
<!doctype html>
<html lang="ja">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Upload App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</head>
<body>
<?php
require_once('./commonheader.php');
?>
<div  class="mx-auto"  style="width: 610px;">

<h1 class="m-1 mt-5">Upload App File</h1>


<form class="mt-5 row g-3"  enctype="multipart/form-data" method="post" action="uploadingapp.php">

    <div class="col-12">
        <label for="inputFile" class="form-label">App File(ipa or apk)</label>
        <div class="input-group">
            <input type="file" class="form-control" id="inputFile" name="appfile">
        </div>
    </div>

    <div class="col-12">
        <label for="selectPlatform" class="form-label">Platform</label>
        <select name='selectPlatform'  id="selectPlatform" class="form-select">
            <option  value="1" selected>iOS</option>
            <option value="2" >Android</option>
        </select>
    </div>

    <div class="col-12">
        <label for="inputMemo1" class="form-label">Memo</label>
        <textarea name='inputMemo1' class="form-control" id="inputMemo1" placeholder="Bugfix..."></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Upload </button>
    </div>
</form>



</div>

</body>
</html>




