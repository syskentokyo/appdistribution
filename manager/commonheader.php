<?php
namespace Syskentokyo\AppDistribution;
require_once('../app/common/commonrequireall.php');
?>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" style="width: 610px;">
        <a href="<?php echo "../".APP_LIST_DIR."";  ?>" target="_blank">User Page</a>
        <a href="<?php echo "../".UPLOAD_APP_DIR."";  ?>" >Upload Page</a>
        <a href="<?php echo "../".MANAGE_DISTRIBUTION_DIR."";  ?>" >Manage Distribution Page</a>
        <a href="<?php echo "../".CREATE_DISTRIBUTION_DIR."";  ?>" >Create Distribution Page</a>
    </div>
</nav>


<?php ?>
