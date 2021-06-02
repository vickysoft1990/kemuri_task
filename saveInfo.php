<?php
session_start();
include 'common/inc.common.php';
$buyorsell = $_REQUEST['buyorsell'];
$sName = $_REQUEST['sName'];
$sPrice = $_REQUEST['sPrice'];
$prf = $_REQUEST['prf'];
$shareCount = $_REQUEST['shareCount'];
$sDate = $_POST['sDate'];
/* Can be used for say when he should sell the share
if($buyorsell=='Buy'){
$sql="select * from (SELECT distinct sName, sDate,sPrice,LEAD(sprice,1) OVER ( PARTITION BY sName ORDER BY sDate )-sPrice as profit_or_loss FROM (select distinct sName,sPrice,sDate from shareprice)k)q where sName='$sName' and sDate>'$sDate' and q.profit_or_loss<1 LIMIT 0,1";
$dataArr = $Cobj->runSql($sql);
$InputArray['bSellDate'] =  $dataArr[0]['sDate'];
}
*/
$InputArray['bName'] = $_POST['sName'];
$InputArray['bType'] = $_POST['buyorsell'];
$InputArray['bDate'] = $_POST['sDate'];
$InputArray['bAmount'] = $_POST['sPrice'];
$InputArray['bShareCount'] = $_POST['shareCount'];
$result = $Cobj->addNewData("buyorsell", $InputArray, "");
if ($result !== false) {
    //added success
    if($buyorsell=='Buy'){
            $result = $_POST['sName']." Shares Bought Successfully!!";
    }else{
            $result = $_POST['sName']." Shares Sold Successfully!!";

    }
} else {
    $result = 'Error Found: Unable to save.';
}
echo json_encode($result);
?>