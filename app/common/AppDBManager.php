<?php

namespace Syskentokyo\AppDistribution;


require_once('Commondefine.php');
require_once('AppInfoJSON.php');
require_once('AppInfo.php');
require_once('DistributionInfo.php');

use PDO;

 class AppDBManager{
    private static function CreateDB():?PDO {
        $dbpath = __DIR__ . "AppDBManager.php/" . DB_FILE_PATH ;

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


        $sql = "CREATE TABLE IF NOT EXISTS distributionlist (
                 id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
                 iosappid INTEGER NOT NULL,
                 androidappid INTEGER NOT NULL,
                 detailmemo TEXT,
                 isactive INTEGER NOT NULL,
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






     public static function SelectAllFromiOSApp()
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM iOSApp ORDER BY id DESC");

         $res = $stmt->execute();





         $validatedDataArray = array();

         if( $res ) {
             $dataArray = $stmt->fetchAll();



             foreach ($dataArray as $data) {
                 //データ整理
                 $appInfo = new AppInfo();


                 $appInfo->dataID = $data["id"];
                 $appInfo->savedirname = $data["savedirname"];
                 $appInfo->memo1 = $data["memo1"];
                 $appInfo->createtime = $data["createtime"];

                 $appInfoJSONDic = json_decode($data["appinfo"], true);

                 $appInfoJSON = new AppInfoJSON();
                 $appInfoJSON->appid = $appInfoJSONDic["appid"];
                 $appInfoJSON->bundleName = $appInfoJSONDic["bundleName"];
                 $appInfoJSON->xcode = $appInfoJSONDic["xcode"];
                 $appInfoJSON->sdkBuild = $appInfoJSONDic["sdkBuild"];
                 $appInfoJSON->minosverversion = $appInfoJSONDic["minosverversion"];
                 $appInfoJSON->appversion = $appInfoJSONDic["appversion"];

                 $appInfo->appInfoJSON = $appInfoJSON;



                 //配列へ追加
                 $validatedDataArray[]=$appInfo;
             }
         }

         $appDBPdo = null;


         return $validatedDataArray;

     }




     public static function SelectAllFromAndroidApp()
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM androidApp ORDER BY id DESC");


         $res = $stmt->execute();



         $validatedDataArray = array();

         if( $res ) {
             $dataArray = $stmt->fetchAll();


             foreach ($dataArray as $data) {

                 $appInfo = new AppInfo();
                 //データ整理
                 $appInfo->dataID = $data["id"];
                 $appInfo->savedirname = $data["savedirname"];
                 $appInfo->memo1 = $data["memo1"];
                 $appInfo->createtime = $data["createtime"];

                 $appInfoJSONDic = json_decode($data["appinfo"], true);

                 $appInfoJSON = new AppInfoJSON();
                 $appInfoJSON->appid = $appInfoJSONDic["appid"];
                 $appInfoJSON->bundleName = $appInfoJSONDic["bundleName"];
                 $appInfoJSON->xcode = $appInfoJSONDic["xcode"];
                 $appInfoJSON->androidMinSDK = $appInfoJSONDic["androidMinSDK"];
                 $appInfoJSON->androidTargetSDK = $appInfoJSONDic["androidTargetSDK"];
                 $appInfoJSON->appversion = $appInfoJSONDic["appversion"];

                 $appInfo->appInfoJSON = $appInfoJSON;


                 //配列へ追加
                 $validatedDataArray[]=$appInfo;
             }
         }
         $appDBPdo = null;


         return $validatedDataArray;

     }


     public static function InsertToDistribution(DistributionInfo $distributionInfo)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //



         //SQL準備
         $stmt = $appDBPdo->prepare("INSERT INTO distributionlist(	iosappid, androidappid, detailmemo,isactive) VALUES (:iosappid, :androidappid,:detailmemo,:isactive)");


         $stmt->bindValue( ':iosappid', $distributionInfo->iosappid, SQLITE3_INTEGER);
         $stmt->bindValue( ':androidappid', $distributionInfo->androidappid, SQLITE3_INTEGER);
         $stmt->bindValue( ':detailmemo', $distributionInfo->detailmemo, SQLITE3_TEXT);
         $stmt->bindValue( ':isactive', $distributionInfo->isActive, SQLITE3_INTEGER);

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

     public static function SelectFromDistribution($dataID)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM distributionlist WHERE id = :dataID");


         $stmt->bindValue( ':dataID', $dataID, SQLITE3_INTEGER);

         $res = $stmt->execute();



         $distributionInfo = new DistributionInfo();

         if( $res ) {
             $data = $stmt->fetch();


             //データ整理
             $distributionInfo->dataid = $data["id"];
             $distributionInfo->iosappid = $data["iosappid"];
             $distributionInfo->androidappid = $data["androidappid"];
             $distributionInfo->detailmemo = $data["detailmemo"];
             $distributionInfo->isActive = $data["isactive"];
             $distributionInfo->createtime = $data["createtime"];

         }

         $appDBPdo = null;


         return $distributionInfo;

     }


     public static function SelectAllFromDitribution()
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM distributionlist ORDER BY id DESC");


         $res = $stmt->execute();



         $validatedDataArray = array();

         if( $res ) {
             $dataArray = $stmt->fetchAll();


             foreach ($dataArray as $data) {

                 $distributionInfo = new DistributionInfo();

                 //データ整理
                 $distributionInfo->dataid = $data["id"];
                 $distributionInfo->iosappid = $data["iosappid"];
                 $distributionInfo->androidappid = $data["androidappid"];
                 $distributionInfo->detailmemo = $data["detailmemo"];
                 $distributionInfo->isActive = $data["isactive"];
                 $distributionInfo->createtime = $data["createtime"];



                 //配列へ追加
                 $validatedDataArray[]=$distributionInfo;
             }
         }
         $appDBPdo = null;


         return $validatedDataArray;

     }

     public static function SelectActiveOnlyFromDitribution()
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("SELECT * FROM distributionlist WHERE isactive=1 ORDER BY id DESC");


         $res = $stmt->execute();



         $validatedDataArray = array();

         if( $res ) {
             $dataArray = $stmt->fetchAll();


             foreach ($dataArray as $data) {

                 $distributionInfo = new DistributionInfo();

                 //データ整理
                 $distributionInfo->dataid = $data["id"];
                 $distributionInfo->iosappid = $data["iosappid"];
                 $distributionInfo->androidappid = $data["androidappid"];
                 $distributionInfo->detailmemo = $data["detailmemo"];
                 $distributionInfo->isActive = $data["isactive"];
                 $distributionInfo->createtime = $data["createtime"];



                 //配列へ追加
                 $validatedDataArray[]=$distributionInfo;
             }
         }
         $appDBPdo = null;


         return $validatedDataArray;

     }


     public static function UpdateDistributionActive($dataID,$setActive)
     {
         $appDBPdo = self::CreateDB();
         if($appDBPdo ==null){
             return;
         }

         //
         //
         //

         //SQL準備
         $stmt = $appDBPdo->prepare("UPDATE  distributionlist SET isactive = :isactive WHERE id = :dataID");

         $stmt->bindValue( ':isactive', $setActive, SQLITE3_INTEGER);
         $stmt->bindValue( ':dataID', $dataID, SQLITE3_INTEGER);

         $res = $stmt->execute();


         $appDBPdo = null;


         return;

     }



 }





