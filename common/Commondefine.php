<?php

namespace Syskentokyo\AppDistribution;


enum AppFilePlatform:int
{
    case iOS=1;
    case Android=2;
}

define ("CURRENT_URL",(empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
define ("CURRENT_BASE_URL",(empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] );

const DETAIL_APP_DIR = "download/detailapp.php";

const UPLOAD_APP_DIR = "manager/uploadapp.php";
const MANAGE_DISTRIBUTION_DIR = "manager/managedistribution.php";

const APP_SAVEDIR = "savedata/app/";
const APP_SAVEDIR_PATH = "../".APP_SAVEDIR;
const DB_FILE_PATH = "../savedata/masterdb/appmaster.sqlite3";


const SAVEDIR_APP_IOS_FILE_NAME = "main.ipa";
const SAVEDIR_APP_ANDROID_FILE_NAME = "main.apk";
