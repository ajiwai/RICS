<?php

echo date('Y/m/d H:i:s').' START '.$_SERVER['PHP_SELF'].'\n';

require_once APP_ROOT_DIR . '/init.php';
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';

//SELECTリスト生成
$conn = new DbConnection();
$dbh = $conn->getConnection();

$wJobOfferDao = new CommonDao($dbh, 'W_JOB_OFFER');

//サイトリスト読み込み
$outputPath = ROOT_DIR . DS . OUTPUT_DIR;
$fp = fopen($outputPath . DS . 'counter.tsv', 'r');
$i = 0;
while ($line = fgets($fp)) {
    $wJobOffereList[$i] = $line;
    $i++;
}
fclose($fp);
$count = $i;

//件数の更新
$successCnt = 0;
$failedCnt = 0;
$skipCnt = 0;
for($i = 0; $i < count($wJobOffereList); $i++){
    $isSkip = false;
    $data = explode('\t', $wJobOffereList[$i]);
    $site = $data[0];
    $date = $data[1];
    if(isset($data[6])){
        $count = str_replace('\n', '' ,$data[6]);
    }else{
        $isSkip = true;
    }
    if(isset($data[5])){
        $cateNm = str_replace('\n', '' ,$data[5]);
    }else{
        $isSkip = true;
    }
    $id1 = str_replace('\n', '' ,$data[3]);
    if($id1 == '' && ($site == 'rikunabi' || $site == 'en-japan' || $site == 'mynavi')){
        $isSkip = true;
    }
    if($date < $fromDate){
        $isSkip = true;
    }
//echo $data[6].'/'.$count.'/'.$id1.'/';
    if(!$isSkip){
        if($data[0] == 'type' && $cateNm == ''){
            $cateNm = str_replace('\n', '' ,$data[6]);
            $count = str_replace('\n', '' ,$data[7]);
        }
        $dataList = array();
        $dataList += array('DATE'=> $date);
        $dataList += array('SITE_NM'=>$data[0]);
        $dataList += array('CATEGORY_ID'=>$data[2]);
        $dataList += array('ID1'=>$data[3]);
        $dataList += array('ID2'=>$data[4]);
        $dataList += array('CATEGORY_NM'=>$cateNm);
        $dataList += array('COUNT'=>$count);
        if($wJobOfferDao->insert($dataList)){
            $successCnt++;
        }else{
            $failedCnt++;
        }
    }else{
        $skipCnt++;
    }
}
echo 'InsertedCount:'.$successCnt.' FailedCount:'.$failedCnt.' SkipCount:'.$skipCnt.'\n';
echo date('Y/m/d H:i:s').' END '.$_SERVER['PHP_SELF'].'\n';

