<?php
//require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';
require APP_ROOT_DIR . '/dao/MSiteDao.php';
require APP_ROOT_DIR . '/dao/WJobOfferDao.php';
require APP_ROOT_DIR . '/controller/AbstractController.php';

class CountListController extends AbstractController {

    // protected $className = '';

       public function __construct() {
           $this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
       }

    public function view($postData){

        //検索条件の取得
        $searchDate = (isset($postData['search_date'])) ? $postData['search_date'] : date('Ymd');
        $today = date('Ymd');
        $searchSelSiteGroupName = (isset($postData['search_sel_site_group'])) ? $postData['search_sel_site_group'] : '';
        //$searchSelSiteCateId = (isset($postData['search_sel_site_cate'])) ? $postData['search_sel_site_cate'] : '';
        $searchSelSiteCateId = (isset($postData['search_sel_site_cate'])) ? $postData['search_sel_site_cate'] : '';
        $searchFreeWord = (isset($postData['search_word'])) ? $postData['search_word'] : 'システム エンジニア プログラマ';
        $chkDisplay = (isset($postData['chk_display'])) ? 'checked' : '';
        $chkDisplay2 = (isset($postData['chk_display2'])) ? 'checked' : '';
        $chkSort = (isset($postData['chk_sort'])) ? 'checked' : '';

        //プルダウンリスト用データ取得
        $conn = new DbConnection();
        $dbh = $conn->getConnection();
        $mSiteDao = new MSiteDao($dbh);
        $mSiteList = $mSiteDao->getSiteCateList();
        $mSiteGroupDao = new CommonDao($dbh, 'M_SITE_GROUP');
        $mSiteGroupList = $mSiteGroupDao->getAllData();
        //結果TABLE用データ取得
        $wJobOfferDao = new WJobOfferDao($dbh);
        $tCountList = $wJobOfferDao->getMonthlyDataByCond($searchSelSiteGroupName, $searchSelSiteCateId, $searchFreeWord);
        $monthList = $wJobOfferDao->getMonthList();
        //画面表示
        require APP_ROOT_DIR . DS . 'view' . DS . $this->className . '.tmpl';

    }
}


