<?php

echo date('Y/m/d H:i:s').' START '.$_SERVER['PHP_SELF'].'\n';

require_once APP_ROOT_DIR . '/init.php';
require APP_ROOT_DIR . '/helper/CrawlerHelper.php';
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';

$helper = new CurationHelper();
//サイトリスト読み込み
$conn = new DbConnection();
$dbh = $conn->getConnection();

$mSiteDao = new CommonDao($dbh, 'M_SITE');
$mSiteList = $mSiteDao->getAllData();

$errmsg = '';
$htmlName = date('Ymd').'.html';
$outputPath = ROOT_DIR . DS . OUTPUT_DIR;
for($i = 0; $i < count($mSiteList); $i++){
    $siteData = $mSiteList[$i];
    $siteName = $siteData['SITE_SNM'];
    $encode = $siteData['ENCODE'];
    $lang = $siteData['LANGUAGE'];
    $cate = sprintf('%02d', $siteData['CATEGORY_ID']);
    $url = $siteData['URL'];

    //サイト情報（HTML）取得
    $fileData = $helper::fileCgetContents($url);

    //ディレクトリ作成
    if(!file_exists($outputPath . DS . $siteName)){
        if(!mkdir($outputPath . DS . $siteName, 0666)){
            $errmsg .= date('Y/m/d H:i:s').' FAILED DIRECTORY MAKE '.$outputPath . DS . $siteName.'\n';
        }
    }
    if(!file_exists($outputPath . DS . $siteName . DS . $cate)){
        if ($cate != '00'){
            if(!mkdir($outputPath . DS . $siteName . DS . $cate, 0666)){
                $errmsg .= date('Y/m/d H:i:s').' FAILED DIRECTORY MAKE '.$outputPath . DS . $siteName . DS . $cate.'\n';
            }
        }
    }

    //サイト情報（HTML）保存
    if ($cate == '00'){
        $fp = fopen($outputPath . DS . $siteName . DS . $htmlName, 'w');
    }else{
        $fp = fopen($outputPath . DS . $siteName . DS . $cate . DS . $htmlName, 'w');
    }
    //fwrite($fp, mb_convert_encoding($fileData, 'UTF-8'));
    fwrite($fp, $fileData );
    fclose($fp);

}

if ( $errmsg != '' ){
    echo $errmsg;
}

echo date('Y/m/d H:i:s').' END '.$_SERVER['PHP_SELF'].'\n';


