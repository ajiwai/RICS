<?php
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';
require APP_ROOT_DIR . '/dao/WJobOfferDao.php';

class DailyCountListController {

      public function __construct() {
          $this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
      }

    public function view($postData, $functionId){

        //検索条件の取得
        $searchDate = (isset($postData['search_date'])) ? $postData['search_date'] : date('Ymd');
        $today = date('Ymd');
        $searchSelSiteGroupName = (isset($postData['search_sel_site_group'])) ? $postData['search_sel_site_group'] : '';
        $chkDisplay = (isset($postData['chk_display'])) ? 'checked' : '';

        //プルダウンリスト用データ取得
        $conn = new DbConnection();
        $dbh = $conn->getConnection();
        $mSiteGroupDao = new CommonDao($dbh, 'M_SITE_GROUP');
        $mSiteGroupList = $mSiteGroupDao->getAllData();
        //結果TABLE用データ取得
        $wJobOfferDao = new WJobOfferDao($dbh);
        $tCountList = $wJobOfferDao->getDailyDataByCond($searchSelSiteGroupName, $searchDate);
        //画面表示
        require APP_ROOT_DIR . DS . 'view' . DS . $this->className . '.tmpl';
        break;

    }
}


