<?php
namespace Syskentokyo\AppDistribution;
require_once('../app/common/commonrequireall.php');
?>


<nav class="navbar  navbar-light navbar-expand-lg " style="background-color: #e3f2fd;">
    <div class="container-fluid" style="width: 610px;">
        <a class="navbar-brand" href="<?php echo "../".MANAGE_DISTRIBUTION_DIR."";  ?>" >Top</a>

            <ul class="navbar-nav"  style="width: 55%;">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo "../".CREATE_DISTRIBUTION_DIR."";  ?>" >Create Distribution</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo "../".UPLOAD_APP_DIR."";  ?>" >Upload App File</a>
                </li>
            </ul>

        <div class=""  style="width: 140px;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-outline-success me-2" href="<?php echo "../".APP_LIST_DIR."";  ?>" target="_blank">User Page</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php ?>
