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
   }
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
               ]);
               ";
               
               
   
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
                     title: 'Stock Price'
                  },   
                  'width':550,
                  'height':550	  
               };
      var chart = new google.visualization.LineChart(document.getElementById('lineChart'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart);
   	
</script>
</html>