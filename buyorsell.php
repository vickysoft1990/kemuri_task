<?php
session_start();
include 'common/inc.common.php';
$error="";
$sName=$_REQUEST['sName'];
$dated=trim($_REQUEST['dated']);
$buyorsell=trim($_REQUEST['buyorsell']);
if($buyorsell=="Buy"){
    $statisticArr = $Cobj->leadSql($dated);

}else if($buyorsell=="Sell"){
    $statisticArr = $Cobj->joinSd($dated,$sName);
    $sizeOf=sizeof($statisticArr);
  if($sizeOf<=0){
  $error="No shares to Sell from ".$sName." !!, Buy New shares";
  }
}
?>
<?php include 'header.php';?>

<body>
<table border="1">
    <p><strong>Only one Transcation allowed for a Day!</strong></p>
    <p class='error'><?php echo$error;?></p>
  <tr>
    <th>Sno</th>
    <th>Company</th>
    <th>Price</th>
    <th>Profit/ Loss</th>
    <th><?php echo $buyorsell." Shares";?></th>
  </tr>
  <?php
  for($t=0;$t<sizeof($statisticArr);$t++){
      $sno=$sno+1;
      $sName=$statisticArr[$t]['sName'];
      $sPrice=$statisticArr[$t]['sPrice'];
      $sDate=$statisticArr[$t]['sDate'];
      $prf=$statisticArr[$t]['profit_or_loss'];
      $bMax=$statisticArr[$t]['bShareCount'];
      echo"<tr>";
      echo"<td>".$sno."</td>";
      echo"<td>".$sName."</td>";
      echo"<td class='amt'>".$sPrice."</td>";
      echo"<td class='amt'>".$prf."</td>";
      if($bMax>0){
      echo"<td><input type='number' value='200' min='1' max='".$bMax."'  id='".$sno."' style=' width: 50px;' ><input type='button' onclick='saveInfo($sno,\"$sName\",\"$sPrice\",\"$prf\",this.value,\"$sDate\")' value='".$buyorsell."'></td>";}
      else{
           echo"<td><input type='number' value='200' min='1'  id='".$sno."' style=' width: 50px;' ><input type='button' onclick='saveInfo($sno,\"$sName\",\"$sPrice\",\"$prf\",this.value,\"$sDate\")' value='".$buyorsell."'></td>";
      }
      echo"</tr>";
  }
  
  ?>
  <tr>
    <td colspan="5">Its a priority from 1,2 .. to get max profit on <?php echo $dated; ?> </td>
    
  </tr>
</table>
</body>
<script>
function saveInfo(sno,sName,sPrice,prf,buyorsell,sDate){
var shareCount=document.getElementById(sno).value;
		var r = confirm("Please Confirm");
	 		if (r == true) {
			$.ajax({
			url: "saveInfo.php", // Url to which the request is send
			type: "POST",        
            data: {shareCount: shareCount,sName:sName,sPrice:sPrice,prf:prf,buyorsell:buyorsell,sDate:sDate },
				 dataType: "json",
			
				  success: function(data){
				      alert(data);
				      parent.location.reload();

				  }
			
		});
		
}
}

</script>
</html>

