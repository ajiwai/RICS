<?php
class WJobOfferDao extends AbstractDao {

    public function __construct($dbh) {
        $this->tableName = 'W_JOB_OFFER';
        parent::__construct($dbh);
        $this->columnList = self::getTableInfo();
    }

    public function getMonthlyDataByCond($searchSelSiteGroupName, $searchSelSiteCateId, $searchFreeWord) {

        try{
            $searchSelSiteCateIds = explode('_', $searchSelSiteCateId);
            $searchSiteNm = $searchSelSiteCateIds[0];
            $searchCateId = sprintf('%02d', $searchSelSiteCateIds[1]);
            $searchFreeWords = explode(' ', $searchFreeWord);
            if(count($searchFreeWords) == 1){
                $searchFreeWords = explode('　', $searchFreeWord);
            }
            $where = '';
            $i = 0;
            $param = array();

            if ($searchSelSiteGroupName != '') {
                if ($searchSelSiteGroupName == 'JapanSite') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM IN (?,?,?,?) ' : ' AND SITE_NM IN (?,?,?,?) ';
                    $param[$i] =  array('Value'=>'rikunabi', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'en-japan', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'mynavi', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'type', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }else if ($searchSelSiteGroupName == 'en-japan(Area)') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM IN (?) AND CATEGORY_ID = ? ' : ' AND SITE_NM IN (?) AND CATEGORY_ID = ? ';
                    $param[$i] =  array('Value'=>'en-japan', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'12', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }else if ($searchSelSiteGroupName == 'JobStreet(Area)') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM LIKE ? AND CATEGORY_ID = ? ' : ' AND SITE_NM LIKE ? AND CATEGORY_ID = ? ';
                    $param[$i] =  array('Value'=>'%JobStreet%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'03', 'Type'=>PDO::PARAM_STR );
                    $i++;
                } else {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM LIKE ? ' : ' AND SITE_NM = ? ';
                    $param[$i] =  array('Value'=>'%'.$searchSelSiteGroupName.'%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }
            }
            if ($searchSelSiteCateId != '') {
                $where .= ($where == '' ) ? ' WHERE SITE_NM = ? ' : ' AND SITE_NM = ? ';
                $param[$i] =  array('Value'=>$searchSiteNm, 'Type'=>PDO::PARAM_STR );
                $i++;
                $where .= ($where == '' ) ? ' WHERE CATEGORY_ID = ? ' : ' AND CATEGORY_ID = ? ';
                $param[$i] =  array('Value'=>$searchCateId, 'Type'=>PDO::PARAM_STR );
                $i++;
            }
            if ( $searchFreeWord != '') {
                for ($j = 0; $j < count($searchFreeWords) ; $j++ ) {
                    if($j == 0 ){
                        $where .= ($where == '' ) ? ' WHERE ((SITE_NM LIKE ? OR CATEGORY_NM LIKE ?) ' : ' AND ((SITE_NM LIKE ? OR CATEGORY_NM LIKE ?) ';
                    }else{
                        $where .= ' OR (SITE_NM LIKE ? OR CATEGORY_NM LIKE ?) ';
                    }
                    $param[$i] =  array('Value'=>'%'.$searchFreeWords[$j].'%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'%'.$searchFreeWords[$j].'%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }
                if(count($searchFreeWords) > 0){
                    $where .= ') ';
                }
            }
            $stmt = $this->dbh->prepare("SELECT SITE_NM,CATEGORY_NM,DATE_FORMAT(DATE, '%Y%m') AS YYYYMM,COUNT(*) AS DAYS,FORMAT(AVG(COUNT),0) AS DISP_COUNT, AVG(COUNT) AS AVG FROM " . $this->tableName . $where . " GROUP BY SITE_NM,CATEGORY_NM,DATE_FORMAT(DATE, '%Y%m') ORDER BY SITE_NM,CATEGORY_NM,DATE_FORMAT(DATE, '%Y%m')");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return parent::getDataByCondLast($stmt, $param);
         
        } catch(PDOException $e){
            echo 'Select failed: ' . $e->getMessage();
        } catch(Exception $e){
            echo 'Other failed: ' . $e->getMessage();
        }
    }

    public function getDailyDataByCond($searchSelSiteGroupName, $searchDate) {

        try{
            $where = '';
            $i = 0;
            $param = array();

            if ($searchSelSiteGroupName != '') {
                if ($searchSelSiteGroupName == 'JapanSite') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM IN (?,?,?,?) ' : ' AND SITE_NM IN (?,?,?,?) ';
                    $param[$i] =  array('Value'=>'rikunabi', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'en-japan', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'mynavi', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'type', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }else if ($searchSelSiteGroupName == 'en-japan(Area)') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM IN (?) AND CATEGORY_ID = ? ' : ' AND SITE_NM IN (?) AND CATEGORY_ID = ? ';
                    $param[$i] =  array('Value'=>'en-japan', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'12', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }else if ($searchSelSiteGroupName == 'JobStreet(Area)') {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM LIKE ? AND CATEGORY_ID = ? ' : ' AND SITE_NM LIKE ? AND CATEGORY_ID = ? ';
                    $param[$i] =  array('Value'=>'%JobStreet%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                    $param[$i] =  array('Value'=>'03', 'Type'=>PDO::PARAM_STR );
                    $i++;
                } else {
                    $where .= ($where == '' ) ? ' WHERE SITE_NM LIKE ? ' : ' AND SITE_NM = ? ';
                    $param[$i] =  array('Value'=>'%'.$searchSelSiteGroupName.'%', 'Type'=>PDO::PARAM_STR );
                    $i++;
                }
            }
            if ($searchDate != '') {
                $where .= ($where == '' ) ? ' WHERE DATE = ? ' : ' AND DATE = ? ';
                $param[$i] =  array('Value'=>$searchDate, 'Type'=>PDO::PARAM_STR );
                $i++;
            }
            $stmt = $this->dbh->prepare('SELECT SITE_NM,CATEGORY_ID,CATEGORY_NM,DATE,COUNT FROM ' . $this->tableName . $where . ' ORDER BY SITE_NM,CATEGORY_ID,DATE');
            //$sql = 'SELECT J.SITE_NM,J.CATEGORY_ID,J.CATEGORY_NM,J.DATE,J.COUNT,S.URL FROM ' . $this->tableName . ' J,M_SITE S';
            //$sql .= '$where . ' AND S.SITE_SNM = J.SITE_NM ORDER BY J.SITE_NM,J.CATEGORY_ID,DATE';
            //$stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return parent::getDataByCondLast($stmt, $param);
         
        } catch(PDOException $e){
            echo 'Select failed: ' . $e->getMessage();
        } catch(Exception $e){
            echo 'Other failed: ' . $e->getMessage();
        }
    }

    public function getMonthList() {
        try{

            $where = '';
            $i = 0;
            $param = array();
            $stmt = $this->dbh->prepare("SELECT DATE_FORMAT(DATE, '%Y%m') AS YYYYMM FROM " . $this->tableName . " GROUP BY DATE_FORMAT(DATE, '%Y%m') ORDER BY DATE_FORMAT(DATE, '%Y%m')");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
         
            return parent::getDataByCondLast($stmt, $param);
         
        } catch(PDOException $e){
            echo 'Select failed: ' . $e->getMessage();
        } catch(Exception $e){
            echo 'Other failed: ' . $e->getMessage();
        }
    }

    protected function getTableInfo() {

        try{
            $columnList = parent::getTableInfo();
            $dataList = array();
            $dataList += array('Field'=> 'YYYYMM');
            array_push($columnList,$dataList);
            $dataList = array();
            $dataList += array('Field'=> 'COUNT');
            array_push($columnList,$dataList);
            $dataList = array();
            $dataList += array('Field'=> 'AVG');
            array_push($columnList,$dataList);
        } catch(Exception $e){
            echo $e->getMessage();
        }
        return $columnList;
    }

}
