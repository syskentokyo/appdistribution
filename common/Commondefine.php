<?php

namespace Syskentokyo\AppDistribution;


enum AppFilePlatform
{
    case iOS;
    case Android;
}


const SAVEDIR_BASEPATH = "../savedata/app/";
const DB_FILE_PATH = "../savedata/masterdb/appmaster.sqlite3";


const SAVEDIR_APP_IOS_FILE_NAME = "main.ipa";
const SAVEDIR_APP_ANDROID_FILE_NAME = "main.apk";
