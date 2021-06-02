<?php
session_start();
include 'common/inc.common.php';
$dataArr = $Cobj->findProfit();
$dataArrBuy = $Cobj->getCustomData('buyorsell',"*",'order by bName,bDate desc');

?>
<?php include 'header.php'; ?>

   <body>
       <div id='loader'><img src="loading.gif"/></div>
      <div class="container-fluid">
         <div class="row content">
            <!-- Start of Upload CSV and side menu file-->
            <div class="col-sm-3 sidenav ">
               <h2>JOE TRADING</h2>
               <ul class="nav nav-pills nav-stacked">
                  <li class="active"><a href="#section1">Upload File</a></li>
                  <br>
                  <li>
                     <div class="custom-file mb-3">
                        <form id="add_studentcsv" action="uploadcsv.php" method="POST" enctype="multipart/form-data">
                           <pre>
      Choose CSV File to Upload
	  <input type="file"  accept=".csv" id="file" name="csvFile" required>
	  <a href="sampledata.csv" download> Download sample </a>

	 <input type="text" name="fileSubmit" hidden>
	  <input type="submit" id="submit" name="Import" value="Upload" style="float: left;">
	   </pre>
                        </form>
                     </div>
                  </li>
                  <li class="active"><a>Company (Mean) / Standard Deviation </a></li>
                  <ul class="list-group">
                     <?php
                        $cmp='';
                           foreach($dataArr as $val){
                               $sName=strtoupper($val['sName']);
                               $sPrice=number_format($val['avgPrice'],2,'.','');
                               $sd=number_format($val['sd'],2,'.','');

                               $cmp.="['$sName', $sPrice],";
                                echo"  <li class='list-group-item'><strong>".$sName."</strong>   (".$sPrice.")/ <strong>SD </strong>( ".$sd.")</li>";
                        	}
                        	?>
                  </ul>
               </ul>
               <br>
            </div>
            <br>
            <!-- End of Upload CSV file-->
            <div class="col-sm-9">
               <div class="well">
                  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                     <div class="form-inline" >
                        From date<input type="date" id="fromDate" name="fromDate" >
                        To date<input type="date" id="toDate" name="toDate" >
                        Company Name <input list="cities"  id="compList" name="compList" placeholder="Company Name" autocomplete="off" required>
                        <datalist id="cities" class="dropdown-menu">
                           <?php
                              foreach($dataArr as $comVal){
                                 $sn=$sn+1;
                                 echo " <option value='".$comVal['sName']."'>";
                              }
                              ?>
                        </datalist>
                        <button class="btn btn-success"  onclick="callone()">Search</button>
                        </li>
                        </ul>
                     </div>
                  </nav>
               </div>
               <div class="row">
                  <div class="col-sm-3">
                     <div class="well">
                        <h4>Shares Purchased / Sold</h4>

                        <?php
                        $flag="";
                        foreach($dataArrBuy as $value){
                            $bType=$value['bType'];
                            $bShareCount=$value['bShareCount'];
                            $bAmount=$value['bAmount'];
                            $bUpdate=$value['bUpdate'];
                            if( date("Y-m-d",strtotime($bUpdate))==date("Y-m-d")){
                                $flag=$bType;
                            }
                            if($bType=='Buy'){
                                $buyTot=$buyTot+$bShareCount;
                                $amtTot=$amtTot+($bAmount*$bShareCount);
                            }
                            else{
                                $sellTot=$sellTot+$bShareCount;
                                $soldTot=$soldTot+($bAmount*$bShareCount);

                            }
                        }

                        ?>
                 <p><?php echo"Bought : ". $buyTot; ?> </p>
                 <p><?php echo"Sold : ". $sellTot; ?> </p>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="well">
                        <h4>Amount Spent</h4>
                 <p><?php echo"Spent : ". $amtTot; ?> </p>
                 <p><?php echo"Sold : ". $soldTot; ?> </p>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="well">
                        <h4>No of Companies </h4>
                        <p><?php echo $sn; ?> </p>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="well">
                        <h4>Status</h4>
                        <p><?php 
                        if($flag==""){
                            echo"No transcation made Today";
                        }else{
                           echo$flag." transcation done Today";
  
                        }
                        ?></p>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-5" id="chart1"style = "width: 350px; height: 250px; margin: 0 auto" >
                     <div class="well">
                     </div>
                  </div>
                  <div class="col-sm-7">
                     <div class="well">
                        <div >
                           <ul class="list-group">
                              <?php
                                 foreach($dataArr as $sdVal){
                                 echo "<li>Over all Profit from the given Data for  ".$sdVal['sName']." is ".$sdVal['profit']."</li>";
                                 }
                                 ?>			        
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade bottom" id="statisticsModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-full-height modal-bottom   " role="document">
            <div class="modal-content" id="modCon">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"> Statistics</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <iframe width="100%" height="100%"></iframe>
               </div>
            </div>
         </div>
      </div>
      </div>
      <input type='text' hidden readonly id="flag" name="flag"   value="<?php echo$flag;?>"               
     </body>
      <script language = "JavaScript">
         function drawChart() {
                   // Define the chart to be drawn.
                   var data = new google.visualization.DataTable();
                   data.addColumn('string', 'Company');
                   data.addColumn('number', 'Mean Price');
                   data.addRows([
                      <?php echo rtrim($cmp,',');?>
                      ]);
                      
                   // Set chart options
                   var options = {
                      'title':'Average market share price of each Company',
                       width: '100%',
                       height: '500px'
                       };
         
                   // Instantiate and draw the chart.
                   var chart = new google.visualization.PieChart(document.getElementById('chart1'));
                   chart.draw(data, options);
                }
                google.charts.setOnLoadCallback(drawChart);
             
             
      </script>
      <script src="js/mainjsfunction.js"></script>
   
</html>