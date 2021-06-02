<?php
class common
{
    private $mdbhost = "localhost";
    private $mdbname = "";
    private $mdbuser = "root";
    private $mdbpass = "";

    public function __construct($dbcon)
    {
        $this->dbCon = $dbcon;
        try {
            $this->dbCon = new PDO(
                'mysql:host=' . $this->mdbhost . ';dbname=' . $this->mdbname,
                $this->mdbuser,
                $this->mdbpass
            );
            $this->dbCon->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_OBJ
            );
            $this->dbCon->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->dbCon->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getCustomData($tableName, $fields, $conditions = "")
    {
        try {
            $stmt = "";
            $sql = "";
            $sql = "SELECT $fields FROM $tableName  $conditions ";
            $stmt = $this->dbCon->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            //$this->throwError(API_DOST_NOT_EXIST, "API does not exist.");
            return false;
        }
    }

    public function runSql($sql)
    {
        try {
            $stmt = "";
            //$sql = "";
            $stmt = $this->dbCon->query($sql);
            //print_r($stmt);exit();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function findProfit()
    {
        try {
            $stmt = "";
            $sql = "select sum(profit_or_loss) 'profit',sDate,sName,
   		        FORMAT( std( sPrice ) , 2 ) 'sd',AVG( sPrice ) 'avgPrice' from 
   		        (SELECT sName, sDate,sPrice,
   		        LEAD(sprice,1) OVER ( PARTITION BY sName ORDER BY sDate )-sprice profit_or_loss 
   		        FROM shareprice group by sName,sDate)u  group by sName";
            $stmt = $this->dbCon->query($sql);
            //print_r($stmt);exit();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getDataById($tableName, $refid)
    {
        $sql = "SELECT * FROM $tableName where refid='$refid' ";
        $stmt = $this->dbCon->query($sql);
        $stmt->bindParam(':refid', $refid);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataObj($tableName, $cond = "")
    {
        $sql = "SELECT * FROM $tableName $cond ";
        $stmt = $this->dbCon->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $keypairArr = $this->chgKeyPairVal($result);
        return $keypairArr;
    }

    public function getDataRawObj($tableName, $cond = "")
    {
        $sql = "SELECT * FROM $tableName $cond ";
        $stmt = $this->dbCon->query($sql);
        //print_r($stmt);exit();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function addNewData(
        $tableName,
        $inputArray,
        $schoolId = '',
        $memcache = false
    ) {
        $fields = '';
        $valset = '';
        if (count($inputArray) > 0) {
            while (list($key, $val) = each($inputArray)) {
                if (!empty($val)) {
                    $fields .= $key . ',';
                    $valset .= "'" . $val . "',";
                }
            }
        }

        $fields = trim($fields, ',');
        $valset = trim($valset, ',');
        $sql = "INSERT INTO $tableName ($fields) VALUES ($valset)";

        /*echo "<pre>";
   		print_r($sql);
   		exit(); */

        $stmt = $this->dbCon->prepare($sql);
        $stmt->execute($inputArray);
        $id = $this->dbCon->lastInsertId();
        return $id;
    }
    public function joinSd($dated, $sName)
    {
        try {
            $sql = "select distinct sName,sDate,sPrice,b.bShareCount from shareprice a 
   	           left join buyorsell b on b.bName=a.sName 
   	           where a.sDate='$dated' and b.bName='$sName' ";
            $stmt = $this->dbCon->query($sql);
            $stmt->bindParam(':sDate', $dated);
            $stmt->bindParam(':bName', $sName);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            $sql->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function leadSql($dated)
    {
        try {
            $sql = "select * from 
   	           (SELECT distinct sName, sDate,sPrice,
   	           LEAD(sprice,1) OVER ( PARTITION BY sName ORDER BY sDate )-sPrice profit_or_loss
   	           FROM (select distinct sName,sPrice,sDate from shareprice)k)q 
   	           where sDate='$dated' order by profit_or_loss DESC,sPrice asc";
            $stmt = $this->dbCon->query($sql);
            $stmt->bindParam(':sDate', $dated);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            $sql->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findSd($compList, $orderby, $secOrd)
    {
        try {
            $stmt = "";
            $sql = "select * from 
   		        (SELECT distinct sName, sDate,sPrice,
   		        LEAD(sprice,1) OVER ( PARTITION BY sName ORDER BY sDate )-sPrice profit_or_loss
   		        FROM (select distinct sName,sPrice,sDate from shareprice)k";
            $sql .= " where sName='$compList' ";
            $sql .= " " . $orderby;
            $sql .= " " . $secOrd;
            $stmt = $this->dbCon->query($sql);
            $stmt->bindParam(':sName', $compList);

            //print_r($stmt);exit();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateMulti($table, $refid, $description2)
    {
        $i = 0;
        $set_details =
            "UPDATE repair SET description2 = :description2 WHERE refid = :refid  ";
        $STH = $this->dbCon->prepare($set_details);
        while ($i < count($refid)) {
            $STH->bindParam(':description2', $description2[$i]);
            // $STH->bindParam(':description', $description[$i]);
            $STH->bindParam(':refid', $refid[$i]);
            $STH->execute();
            $i++;
        }
    }
    public function chgKeyPairVal($datObj)
    {
        $kaypairArr = [];
        if (count($datObj) > 0) {
            for ($kpLoop = 0; $kpLoop < count($datObj); $kpLoop++) {
                $kaypairArr[$datObj[$kpLoop]['refid']] = $datObj[$kpLoop];
            }
        }
        return $kaypairArr;
    }

    public function throwError($code, $message)
    {
        $errorMsg = json_encode([
            'error' => ['status' => $code, 'message' => $message],
        ]);
        echo $errorMsg;
        exit();
    }
   public function deviation($arr)
   {
      $no_element = count($arr);
      $var = 0.0;
      $avg = array_sum($arr)/$no_element;
      foreach($arr as $r)
      {
         $var += pow(($r - $avg), 2);
      }
      return (float)sqrt($var/$no_element);
   }
   
}

?>
