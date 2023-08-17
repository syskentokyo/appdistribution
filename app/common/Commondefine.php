<?php

namespace Syskentokyo\AppDistribution;


enum AppFilePlatform:int
{
    case iOS=1;
    case Android=2;
}

define ("CURRENT_URL",(empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
define ("CURRENT_BASE_URL",(empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] );

const MANAGER_DIR_NAME = "manager";

const USER_DIR_NAME = "app";

const SAVEDATA_DIR_NAME = "savedata";

const DETAIL_APP_DIR = USER_DIR_NAME."/detailapp.php";

const APP_LIST_DIR = USER_DIR_NAME."/index.php";

const DETAIL_DISTRIBUTION_DIR = USER_DIR_NAME."/detaildistribution.php";

const APP_IOS_INSTALL_PLIST_FILEPATH = USER_DIR_NAME."/iosinstallplist.php";

const APP_IOS_INSTALL_PLIST_BASE_XML_FILEPATH = USER_DIR_NAME."/baseplist.xml";

const IOS_ICON_57_PATH = SAVEDATA_DIR_NAME."/icon/icon57.png";

const IOS_ICON_512_PATH = SAVEDATA_DIR_NAME."/icon/icon512.png";

const UPLOAD_APP_DIR = MANAGER_DIR_NAME."/uploadapp.php";

const MANAGE_DISTRIBUTION_DIR = MANAGER_DIR_NAME."/index.php";

const CREATE_DISTRIBUTION_DIR = MANAGER_DIR_NAME."/createdistribution.php";

const APP_SAVEDIR = USER_DIR_NAME."/".SAVEDATA_DIR_NAME."/app/";
const APP_SAVEDIR_PATH = "../".APP_SAVEDIR;
const DB_FILE_PATH = "../".SAVEDATA_DIR_NAME."/masterdb/appmaster.sqlite3";


const SAVEDIR_APP_IOS_FILE_NAME = "main.ipa";
const SAVEDIR_APP_ANDROID_FILE_NAME = "main.apk";
