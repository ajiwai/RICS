<?php
class MSiteDao extends AbstractDao {

    public function __construct($dbh) {
        $this->tableName = 'M_SITE';
        parent::__construct($dbh);
        $this->columnList = self::getTableInfo();
    }

    public function getAllData() {
        try{

            $stmt = $this->dbh->prepare('SELECT * FROM ' . $this->tableName . ' WHERE DELETE_FLG = FALSE;');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $stmt->execute();
         
            $dataList= array();
            while ($row = $stmt->fetch()) {
                $data = self::getRowData($row);
                $dataList[] = $data;
            }
         
            return $dataList;
         
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function getSiteNameList() {
        try{

            $stmt = $this->dbh->prepare('SELECT SITE_NM FROM ' . $this->tableName . ' WHERE DELETE_FLG = FALSE GROUP BY SITE_NM ORDER BY SITE_NM;');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $stmt->execute();
         
            $dataList= array();
            while ($row = $stmt->fetch()) {
                $data = self::getRowData($row);
                $dataList[] = $data;
            }
            return $dataList;
         
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function getSiteCateList() {
        try{

            $stmt = $this->dbh->prepare("SELECT ID,CATEGORY_ID,CONCAT(ID,'_',CATEGORY_ID) AS SITE_CATE_ID,CONCAT(SITE_NM,' - ',SITE_INFO) AS SITE_CATE_NM,CONCAT(SITE_SNM,'_',CATEGORY_ID) AS SITE_NM_CATE_ID FROM " . $this->tableName . ' WHERE DELETE_FLG = FALSE ORDER BY ID;');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $stmt->execute();
         
            $dataList= array();
            while ($row = $stmt->fetch()) {
                $data = self::getRowData($row);
                $dataList[] = $data;
            }
         
            return $dataList;
         
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    protected function getTableInfo() {

        try{
            $columnList = parent::getTableInfo();
            $dataList = array();
            $dataList += array('Field'=> 'SITE_CATE_ID');
            array_push($columnList,$dataList);
            $dataList = array();
            $dataList += array('Field'=> 'SITE_CATE_NM');
            array_push($columnList,$dataList);
            $dataList = array();
            $dataList += array('Field'=> 'SITE_NM_CATE_ID');
            array_push($columnList,$dataList);
        } catch(Exception $e){
            echo $e->getMessage();
        }
        return $columnList;
    }
         

}
