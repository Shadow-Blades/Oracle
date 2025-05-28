<?php
/**
 * Short description for class
 *
 * Long description for class (if any)...+
 *-+-
 * @author     Original Author <@nik... #never_lose_smile>
 */
include "include/dbcon.php";
$date=date("m/d/Y");
$country = $_POST['country'];
$mainquery="SELECT TOP(250) Th.CreationTime,Th.Status,Th.TicketID,Th.VehicleID,Th.TransactionMode,Th.ReceiptTicketID,
 TD.TransactionDetailID,TD.GrossTime,TD.GrossWeight,TD.WeightUnit,TD.TareWeight,TD.TareTime,TD.NetWeight,
 v.VehicleNumber,p.ProductName
 FROM TransactionData Th
JOIN TransactionDetail TD ON Th.ReceiptTicketID = TD.ReceiptTicketID
inner join Vehicle v on v.VehicleID = Th.VehicleID
left join Product p on p.ProductID = TD.ProductID  where Th.TicketID IS NOT NULL AND ";
$main=explode("?",$country);
 if($main[0] !="Choose..." && $main[1] !="Choose..." && $main[2] !=$date." - ".$date && $main[3] !="" && $main[4] !=""){
     modeandstatus($main[0],$main[1],$main[2],$main[3],$main[4],$mainquery);
 }
 else if($main[0] !="Choose..." && $main[1] !="Choose..." && $main[2] !=$date." - ".$date && $main[3] !=""){
   modedtmin($main[0],$main[1],$main[2],$main[3],$mainquery);
}
else if($main[0] !="Choose..." && $main[1] !="Choose..." && $main[2] !=$date." - ".$date && $main[4] !=""){
   modedtmax($main[0],$main[1],$main[2],$main[4],$mainquery);
}
 else if($main[0] !="Choose..." && $main[1] !="Choose..." && $main[2] !=$date." - ".$date){
   stmddate($main[0],$main[1],$main[2],$mainquery);
}
else if($main[0] !="Choose..." && $main[1] !="Choose..." && $main[3]!= ""){
   stmodemin($main[0],$main[1],$main[3],$mainquery);
}
else if($main[0] !="Choose..." && $main[1] !="Choose..."){
   stmode($main[0],$main[1],$mainquery);
}
else if($main[0] !="Choose..."  && $main[2] !=$date." - ".$date){
   datemode($main[0],$main[2],$mainquery);
}
else if($main[1] !="Choose..."  && $main[2] !=$date." - ".$date){
   statudate($main[1],$main[2],$mainquery);
}
 elseif($main[2] !=$date." - ".$date && $main[3] !=null ){
  dateandmin($main[2],$main[3],$mainquery);
 }
 elseif($main[3] !="" && $main[4] !=""){
   maxmin($main[3],$main[4],$mainquery);
}
 elseif($main[2] !=$date." - ".$date){
    dateonly($main[2],$mainquery);
}
elseif($main[0] !="Choose..."){
    modeonly($main[0],$mainquery);
    
 }
 elseif($main[1] !="Choose..."){
    statusonly($main[1],$mainquery);
 }
 elseif($main[3] !=""){
    idonly($main[3],$mainquery);
 }
 elseif($main[4] !=""){
    maxonly($main[4],$mainquery);
 }
 else{
    finalone();
 }
 function stmodemin($mode,$status,$min,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status' AND TicketID=$min";
   makequery($myq);
    
   
}
function stmode($mode,$status,$mainquery){
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status'";
   makequery($myq);
   
}
function modeandstatus($mode,$status,$date,$min,$max,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59' AND TicketID between $min AND $max";
   makequery($myq);
    
}
function modedtmax($mode,$status,$date,$max,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59' AND TicketID=$max";
   makequery($myq);
   
}
function modedtmin($mode,$status,$date,$min,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59' AND TicketID=$min";
   makequery($myq);
   
}
function stmddate($mode,$status,$date,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND Status='$status' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59'";
   
   makequery($myq);
   
}
function dateandmin($date,$min,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59' AND TicketID=$min";
   
   makequery($myq);
}
function dateonly($date,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59'";
   
   makequery($myq);
}
function modeonly($mode,$mainquery){
  $myq=$mainquery." TransactionMode='$mode'";
   makequery($myq);
   
//   $myquery=$mainquery."TransactionMode = '$mode'";
//   makequery($mainquery);
}
function statusonly($status,$mainquery){
   $myq=$mainquery." Status='$status'";
   makequery($myq);
}
function idonly($min,$mainquery){
   $myq=$mainquery." TicketID=$min";
   makequery($myq);
}
function maxonly($max,$mainquery){
   $myq=$mainquery." TicketID=$max";
   makequery($myq);
}
function maxmin($min,$max,$mainquery){
   $myq=$mainquery." TicketID between $min AND $max";
   makequery($myq);
}
function datemode($mode,$date,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." TransactionMode='$mode' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59'";
   makequery($myq);
}
function statudate($status,$date,$mainquery){
   $change=explode('-',$date);
  
   $var=$change[1];
   $newDate2 = date("Y-m-d", strtotime($var));  
   $newDate1 = date("Y-m-d", strtotime($change[0])); 
   $myq=$mainquery." Status='$status' AND CreationTime between '$newDate1"." 00:00:00' and '$newDate2"." 23:59:59'";
   makequery($myq);
}
function finalone(){
   $myq=" SELECT TOP(250)
   Th.CreationTime,Th.Status,Th.TicketID,Th.VehicleID,Th.TransactionMode,
    TD.TransactionDetailID,TD.GrossTime,TD.GrossWeight,TD.WeightUnit,TD.TareWeight,TD.TareTime,TD.NetWeight,
    v.VehicleNumber,p.ProductName
    FROM TransactionData Th
   JOIN TransactionDetail TD ON Th.ReceiptTicketID = TD.ReceiptTicketID
   inner join Vehicle v on v.VehicleID = Th.VehicleID
   left join Product p on p.ProductID = TD.ProductID where TD.NetWeight IS NOT NULL  AND WeightUnit='kg'";
   makequery($myq);
}
function makequery($query){
   $new=$query." ORDER BY Th.TicketID DESC";
   include "include/dbcon.php";
    $res=sqlsrv_query($con,$new);
   $counter=1;
   $cid=""; 
 while( $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC) ) {
    
   $dateval=$row['CreationTime'];
   $date_string = date_format($dateval, 'd/m/Y H:i:s');
   $ticket=(int)$row['TicketID'];
   $tmode=$row['TransactionMode'];
   $tstatus=$row['Status'];
    if($tstatus == "Active" &&   $ticket != NULL){
       echo" <tr>
      <td>".$counter."</td>
      <td>".$row['TicketID']."</td>
      <td><span class='badge bg-warning'>".$row['VehicleNumber']."</span></td>
      <td>".$date_string."</td>
      <td>".$row['TransactionMode']."</td>
      <td>".$row['Status']."</td>
      <td>".(int)$row['GrossWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".(int)$row['TareWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".(int)$row['NetWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".$row['ProductName']."</td>
      <td><a href='print.php?id=".$row['ReceiptTicketID']."'><i class='fa fa-print'></i></a></td>
    </tr>";
    }
   // if($cid == $myid) {  
     elseif($tmode = 'Multi' && $cid != $ticket) {  
     
       $q1="     SELECT
       Th.CreationTime,
       Th.ReceiptTicketID,
       Th.Status,
       Th.TicketID,
       Th.VehicleID,
       Th.TransactionMode,
       TD.TransactionDetailID,
       TD.GrossTime,
       TD.GrossWeight,
       TD.WeightUnit,
       TD.TareWeight,
       TD.TareTime,
       TD.NetWeight,
       v.VehicleNumber,
       p.ProductName
       
     
     FROM TransactionData Th
     
     JOIN TransactionDetail TD ON Th.ReceiptTicketID = TD.ReceiptTicketID
     inner join Vehicle v on v.VehicleID = Th.VehicleID
     left join Product p on p.ProductID = TD.ProductID  where TD.NetWeight IS NOT NULL AND TicketID=$ticket  AND  GrossWeight in (
           select max(GrossWeight) from TransactionDetail group by ReceiptTicketID
       )";
      $res1=sqlsrv_query($con,$q1);
     $row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC);
     $dateval1=$row1['CreationTime'];
   $date_string1 = date_format($dateval1, 'd/m/Y H:i:s');
  echo" <tr>
  <td>".$counter."</td>
  <td>".$row1['TicketID']."</td>
  <td><span class='badge bg-warning'>".$row1['VehicleNumber']."</span></td>
  <td>".$date_string1."</td>
  <td>".$row1['TransactionMode']."</td>
  <td>".$row1['Status']."</td>
  <td>".(int)$row1['GrossWeight']."<br><span class='badge bg-danger'>".$row1['WeightUnit']."</span></td>
  <td>".(int)$row1['TareWeight']."<br><span class='badge bg-danger'>".$row1['WeightUnit']."</span></td>
  <td>".(int)$row1['NetWeight']."<br><span class='badge bg-danger'>".$row1['WeightUnit']."</span></td>
  <td>".$row1['ProductName']."</td>
  <td><a href='print.php?id=".$row1['ReceiptTicketID']."'><i class='fa fa-print'></i></a></td>
</tr>";
}
elseif($cid=$ticket){
    $arry=$row1;
    continue;
}
else{
   echo "
   <tr>
      <td>".$counter."</td>
      <td>".$row['TicketID']."</td>
      <td><span class='badge bg-warning'>".$row['VehicleNumber']."</span></td>
      <td>".$date_string."</td>
      <td>".$row['TransactionMode']."</td>
      <td>".$row['Status']."</td>
      <td>".(int)$row['GrossWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".(int)$row['TareWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".(int)$row['NetWeight']."<br><span class='badge bg-danger'>".$row['WeightUnit']."</span></td>
      <td>".$row['ProductName']."</td>
      <td><a href='print.php?id=".$row['ReceiptTicketID']."'><i class='fa fa-print'></i></a></td>
   </tr>";
}
$cid=(int)$row['TicketID'];
$counter++; }
}
sqlsrv_close($con);
?>