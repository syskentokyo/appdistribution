<?php

namespace Syskentokyo\AppDistribution;


require_once('Commondefine.php');
require_once ('AppInfoJSON.php');
require_once ('AppInfo.php');

use PDO;

 class AppDBManager{
    private static function CreateDB():?PDO {
        $dbpath =__DIR__."/". DB_FILE_PATH ;

        $dboptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $appDBPdo = new PDO('sqlite:' . $dbpath, null, null, $dboptions);

            //
            // テーブル作成
            //
            AppDBManager::InitTable($appDBPdo);


            return $appDBPdo;
        }catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    private static function InitTable($appDBPdo){

        $sql = "CREATE TABLE IF NOT EXISTS iOSApp (
                 id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
                 savedirname VARCHAR(60) NOT NULL,
                 appinfo TEXT,
                 memo1 TEXT,
                 createtime TEXT NOT NULL DEFAULT (DATETIME('now', 'localtime'))
             )";

        $appDBPdo->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS androidApp (
                 id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
                 savedirname VARCHAR(60) NOT NULL,
                 appinfo TEXT,
                 memo1 TEXT,
                 createtime TEXT NOT NULL DEFAULT (DATETIME('now', 'localtime'))
             )";

        $appDBPdo->query($sql);

    }

     public static function InsertToiOSApp(AppInfoJSON $appInfoJSON,$saveDirName,$memo1)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         $appinfoTxt = json_encode($appInfoJSON,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);



         //SQL準備
         $stmt = $appDBPdo->prepare("INSERT INTO iOSApp(	savedirname, appinfo, memo1) VALUES (:savedirname, :appinfo,:memo1)");


         $stmt->bindValue( ':appinfo', $appinfoTxt, SQLITE3_TEXT);
         $stmt->bindValue( ':savedirname', $saveDirName, SQLITE3_TEXT);
         $stmt->bindValue( ':memo1', $memo1, SQLITE3_TEXT);

         $res = $stmt->execute();//実行

         $insertDataID=-999;

         if($res === true){
             $insertDataID = (int) $appDBPdo->lastInsertId();
             $appDBPdo = null;
         }else{
             $appDBPdo = null;
         }

         return $insertDataID;

     }



     public static function InsertToAndroidApp(AppInfoJSON $appInfoJSON,$saveDirName,$memo1)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         $appinfoTxt = json_encode($appInfoJSON,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);



         //SQL準備
         $stmt = $appDBPdo->prepare("INSERT INTO androidApp(	savedirname, appinfo, memo1) VALUES (:savedirname, :appinfo,:memo1)");


         $stmt->bindValue( ':appinfo', $appinfoTxt, SQLITE3_TEXT);
         $stmt->bindValue( ':savedirname', $saveDirName, SQLITE3_TEXT);
         $stmt->bindValue( ':memo1', $memo1, SQLITE3_TEXT);

         $res = $stmt->execute();//実行

         $insertDataID=-999;

         if($res === true){
             $insertDataID = (int) $appDBPdo->lastInsertId();
             $appDBPdo = null;
         }else{
             $appDBPdo = null;
         }

         return $insertDataID;

     }


     public static function SelectFromiOSApp($dataID)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM iOSApp WHERE id = :dataID");


         $stmt->bindValue( ':dataID', $dataID, SQLITE3_INTEGER);

         $res = $stmt->execute();



         $appInfo = new AppInfo();

        if( $res ) {
            $data = $stmt->fetch();


            //データ整理
            $appInfo->dataID = $data["id"];
            $appInfo->savedirname = $data["savedirname"];
            $appInfo->memo1 = $data["memo1"];
            $appInfo->createtime = $data["createtime"];

            $appInfoJSONDic = json_decode($data["appinfo"],true);

            $appInfoJSON = new AppInfoJSON();
            $appInfoJSON->appid = $appInfoJSONDic["appid"];
            $appInfoJSON->bundleName = $appInfoJSONDic["bundleName"];
            $appInfoJSON->xcode = $appInfoJSONDic["xcode"];
            $appInfoJSON->sdkBuild = $appInfoJSONDic["sdkBuild"];
            $appInfoJSON->minosverversion = $appInfoJSONDic["minosverversion"];
            $appInfoJSON->appversion = $appInfoJSONDic["appversion"];

            $appInfo->appInfoJSON = $appInfoJSON;
        }

         $appDBPdo = null;


         return $appInfo;

     }


     public static function SelectFromAndroidApp($dataID)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM androidApp WHERE id = :dataID");


         $stmt->bindValue( ':dataID', $dataID, SQLITE3_INTEGER);

         $res = $stmt->execute();



         $appInfo = new AppInfo();

         if( $res ) {
             $data = $stmt->fetch();


             //データ整理
             $appInfo->dataID = $data["id"];
             $appInfo->savedirname = $data["savedirname"];
             $appInfo->memo1 = $data["memo1"];
             $appInfo->createtime = $data["createtime"];

             $appInfoJSONDic = json_decode($data["appinfo"],true);

             $appInfoJSON = new AppInfoJSON();
             $appInfoJSON->appid = $appInfoJSONDic["appid"];
             $appInfoJSON->bundleName = $appInfoJSONDic["bundleName"];
             $appInfoJSON->xcode = $appInfoJSONDic["xcode"];
             $appInfoJSON->androidMinSDK = $appInfoJSONDic["androidMinSDK"];
             $appInfoJSON->androidTargetSDK = $appInfoJSONDic["androidTargetSDK"];
             $appInfoJSON->appversion = $appInfoJSONDic["appversion"];

             $appInfo->appInfoJSON = $appInfoJSON;
         }

         $appDBPdo = null;


         return $appInfo;

     }
}





