<?php
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/AbstractDao.php';
require APP_ROOT_DIR . '/dao/MSiteDao.php';

class FileListController {

      public function __construct() {
          $this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
      }

    public function view($postData, $functionId){

        //検索条件の取得
        $searchDate = (isset($postData['search_date'])) ? $postData['search_date'] : date('Ymd');
        $today = date('Ymd');
        $searchSelSiteId = (isset($postData['search_sel_site_id'])) ? $postData['search_sel_site_id'] : 0;
        $chkDisplay = (isset($postData['chk_display'])) ? 'checked' : '';
        $chkDisplay2 = (isset($postData['chk_display2'])) ? 'checked' : '';

        //プルダウンリスト用データ取得
        $conn = new DbConnection();
        $dbh = $conn->getConnection();
        $mSiteDao = new MSiteDao($dbh, 'M_SITE');
        $mSiteNameList = $mSiteDao->getSiteNameList();
        $mSiteList = $mSiteDao->getAllData();

        //画面表示
        require APP_ROOT_DIR . DS . 'view' . DS . $this->className . '.tmpl';
        break;

    }
}


