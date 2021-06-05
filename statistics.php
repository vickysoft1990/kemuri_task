<?php
   session_start();
   include 'common/inc.common.php';
   $flag=trim($_REQUEST['flag']);
   $compList=trim($_REQUEST['compList']);
   $toDate=trim($_REQUEST['toDate']);
   $fromDate=trim($_REQUEST['fromDate']);
   $statisticArr = $Cobj->findSd($compList," and (sDate BETWEEN '$fromDate' AND '$toDate') order by sDate",")l order by l.sDate");
   if(sizeof($statisticArr)<=0){
       echo"<p class='error'>No data present for the given date range, So the last 5 records are displayed!!</p>";
       $statisticArr = $Cobj->findSd($compList," order by sDate desc limit 0,5 ",")l order by l.sDate");
       if(sizeof($statisticArr)<=0){
           echo "Company ".$compList." Not present inside the Database ".$compList="Error";
       }
   }
if($compList!='Error'){
   $whr='';
   foreach($statisticArr as $val){
   $sDate=$val['sDate'];
   $sPrice=$val['sPrice'];
   $sName=$val['sName'];
   $whr.="['$sDate',  $sPrice],";
   }
   $trimedVal=rtrim($whr,',');
   $dynamicChart="data.addRows([
                  $trimedVal
                   ]); ";
               
    function calAnalysis($date1,$date2){
        $earlier = new DateTime($date1);
        $later = new DateTime($date2);
        $diff = $later->diff($earlier)->format("%a");
        return $diff;
    }           
    ?>
<?php include 'header.php';?>
<body>
   <div class="container1"     style="padding-right: 15px; padding-left: 15px; margin-right: auto; "
      >
       <div class="row">
         <div class="col-sm-5" style = "margin: 70px auto">
            <table class="table table-bordered">
               <thead>
                  <p>
                     <?php 
                        if($flag!=""){ 
                        echo "<p class='error'>Already one transcation made, Thats all for today!!</p>";
                        
                        } 
                        ?>
                  </p>
                  <tr>
                     <th>Company Name : <?php echo $compList;?></th>
                     <th>Between</th>
                     <th><?php echo $fromDate;?></th>
                     <th><?php echo $toDate;?></th>
                  </tr>
                  <tr>
                     <th>Type</th>
                     <th>Date(Y-M-D)</th>
                     <th>Days Count</th>
                     <th>Profit/Share</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  $startTimeStamp="";
                  $mode="Buy";
                 $size=sizeof($statisticArr);
                 for($i=0;$i<$size;$i++){
                         $dataPoints[$i]['y']=$statisticArr[$i]['sPrice'];
                         $dataPoints[$i]['label']=$statisticArr[$i]['sDate'];
                         $pro_loss=$statisticArr[$i]['profit_or_loss'];
                        if($pro_loss<0 && $mode!='Buy'){
                        $date2=$statisticArr[$i]['sDate'];
                        $days=calAnalysis($date1,$date2);
                        echo "<tr><td>Sell on :(Evening-Trading Close)</td><td>".$statisticArr[$i]['sDate']."</td><td>".$days." </td><td align='right'> ".$totPoss."</td></tr>";
                         $mode="Buy";
                        $totDays=$totDays+$days;
                        }
                        elseif($pro_loss>0 && $mode!='Sell'){
                        $date1=$statisticArr[$i]['sDate'];
                        echo "<tr><td>Buy on :(Morning-Trading starts)</td><td>".$statisticArr[$i]['sDate']."</td><td></td><td></td></tr>";
                         $mode="Sell";
                        }
                        if($pro_loss>0){
                     $totPoss=$totPoss+$pro_loss;
                     $gt=$gt+$totPoss;
                        }else{
                            $totPoss=0;
                        }
                        
                        if($size-1==$i && $mode=='Sell'){
                         echo $mode."Profit Will be :".$totPoss;
                     }
                     }
                                      echo"<tr><td></td><td>Total :</td><td>".$totDays."</td><td align='right'>".$gt."</td></tr>";

                   /*  $i=0;
                     foreach($statisticArr as $dataVal){
                         $dataPoints[$i]['y']=$dataVal['sPrice'];
                         $dataPoints[$i]['label']=$date1=$dataVal['sDate'];
                        echo $date2=next($dataVal['sDate']);
                         echo calDate($date1,$date2);
                        $i++;
                     }
                     */
                     ?>
                  </tbody>
            </table>
         </div>
         <div class="col-sm-4" id="chartContainer" style = "width: 550px; height: 400px; margin: 0 auto">
            <div class="well">
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-5" style = "margin: 70px auto">
            <table class="table table-bordered">
               <thead>
                  <p>
                     <?php 
                        if($flag!=""){ 
                        echo "<p class='error'>Already one transcation made, Thats all for today!!</p>";
                        // $flag=1;
                        } 
                        ?>
                  </p>
                  <tr>
                     <th>Date</th>
                     <th>Price</th>
                     <th>Profit/Loss</th>
                     <th>Best Buy</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     for($i=0;$i<sizeof($statisticArr);$i++){
                     $arrval[]=$statisticArr[$i]['sPrice'];
                     echo"<tr>";
                     echo" <td>".$statisticArr[$i]['sDate']."</td>";
                     echo" <td>".$statisticArr[$i]['sPrice']."</td>";
                     echo" <td>".$statisticArr[$i]['profit_or_loss']."</td>";
                     
                     if($statisticArr[$i]['profit_or_loss']>=0){
                     echo" <td><button data-id='".$statisticArr[$i]['sDate']."' data-buy='Buy' class='userinfo' " .(($flag!='')?'disabled="true"':"false")."'>BestBuy</button></td>";
                     }else{
                     echo" <td><button data-id='".$statisticArr[$i]['sDate']."' data-buy='Sell' class='userinfo' " .(($flag!='')?'disabled="true"':"false")."'>Sell</button></td>";
                     }
                     echo"<tr>";
                     
                     }
                     
                     ?>
                  <tr>
                     <th>Mean : </th>
                     <th><?php echo number_format(array_sum($arrval)/sizeof($statisticArr),3); ?> </th>
                     <th colspan="1">Standard Deviation : </th>
                     <th><?php echo number_format($Cobj->deviation($arrval),3); ?> </th>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="col-sm-4" id="lineChart" style = "width: 550px; height: 400px; margin: 0 auto">
            <div class="well">
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="buyModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title">Buy or sell</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <div class="modal-body">
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</body>
<script>
   $(document).ready(function(){
    var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Price Analysis"
	},
	axisY: {
		title: "<?php echo $compList." PRICE"  ; ?>"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## Rupees",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
    });
    chart.render();
 
    $('.userinfo').click(function(){
      var dated = $(this).data('id');
      var buyorsell = $(this).data('buy');
      var sName='<?php echo $compList; ?>';
      // AJAX request
      $.ajax({
       url: 'buyorsell.php',
       type: 'post',
       data: {dated: dated, buyorsell:buyorsell,sName:sName},
       success: function(response){ 
         // Add response in Modal body
         $('.modal-body').html(response);
            // Display Modal
         $('#buyModal').modal('show'); 
       }
     });
    });
   });
   
   function drawChart() {
     var data = new google.visualization.DataTable();
     data.addColumn('string', '');
     data.addColumn('number', '<?php echo $sName; ?>');
      <?php echo $dynamicChart; ?>
     var options = {'title' : 'Money flow',
                  hAxis: {
                     title: 'Dates'
                  },
                  vAxis: {
                     title: '<?php echo $compList." PRICE"  ; ?>'
                  },   
                  'width':550,
                  'height':550	  
               };
      var chart = new google.visualization.LineChart(document.getElementById('lineChart'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart);
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</html>
<?php
}
else{
echo "Data Not found!! Go back and find new search!!";
}
?>
