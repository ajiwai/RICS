<?php
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/AbstractDao.php';
require APP_ROOT_DIR . '/dao/MSiteDao.php';

class SiteListController {

      public function __construct() {
          $this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
      }

    public function view($postData, $functionId){

        //リスト用データ取得
		$conn = new DbConnection();
		$dbh = $conn->getConnection();
		$mSiteDao = new MSiteDao($dbh);
        $mSiteList = $mSiteDao->getAllData();

        //画面表示
        require APP_ROOT_DIR . DS . 'view' . DS . $this->className . '.tmpl';
        break;

    }
}


