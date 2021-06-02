<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set('memory_limit', '512M');// to increase the memory to process large amount of data
ini_set('max_execution_time', 300);// five min max execution time
include 'common/inc.common.php';
//file_put_contents('test.txt', file_get_contents('php://input'));

if (isset($_POST["fileSubmit"])) {
    
    // Get file extension
    $file_extension = pathinfo($_FILES["csvFile"]["name"], PATHINFO_EXTENSION);
    // Validate file input to check if is not empty
    if (! file_exists($_FILES["csvFile"]["tmp_name"])) {
        $response = array(
            "type" => "error",
            "message" => "File input should not be empty."
        );
             $msg="File input should not be empty.";

    } // Validate file input to check if is with valid extension
    else if ($file_extension != "csv") {
            $response = array(
                "type" => "error",
                "message" => "Invalid CSV: File must have .csv extension."
            );
                 $msg="Invalid CSV:  File must have .csv extension.";

            //echo $result;
        } // Validate file size
    else if (($_FILES["csvFile"]["size"] > 2000000)) {
            $response = array(
                "type" => "error",
                "message" => "Invalid CSV: File size is too large."
            );
                $msg="Invalid CSV: File size is too large.";

        } // Validate if all the records have same number of fields
    else {
        $lengthArray = array();
        
        $row = 1;
        if (($fp = fopen($_FILES["csvFile"]["tmp_name"], "r")) !== FALSE) {
            while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
                if(count($data)<2){
                     $response = array(
                "type" => "error",
                "message" => "File should not be Empty"
            );
                $msg="File should not be Empty ";

                    break;
                }
			$lengthArray[] = count($data);
			$sDate=$data[1];
			$Date_format = trim($data[1]);
	         if (!empty($Date_format)) {
              $inputArray['sDate'] = date('Y-m-d', strtotime($Date_format));
              }
                   $sPrice = (float) $data[3];

if(ctype_alnum($data[2]) and abs($sPrice)>0){            
    $inputArray['sName']=strtoupper($data[2]);
    $inputArray['sPrice']=$sPrice;
    $res = $Cobj->addNewData("shareprice", $inputArray, ""); 
}
            $row ++;
            }
            fclose($fp);
        }
        $lengthArray = array_unique($lengthArray);
      
        if (count($lengthArray) == 1) {
            $response = array(
                "type" => "success",
                "message" => "File Validation Success."
            );
            $msg="File Uploaded Successfully! ";

        } else {
            $response = array(
                "type" => "error",
                "message" => "Invalid CSV: Data Not Found."
            );
            $msg="Error:Invalid CSV: Data Not Found. ";
        }
    }

}
else{
$response = array(
                "type" => "Error",
                "message" => "Unauthorised Access"
            );
          $msg="Unauthorised Access ";

	}
//echo json_encode($msg);
echo $msg;
?>

