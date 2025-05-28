<?php 

include "config.php";
	  $response=array();
      $roles=array();
   

      if($_REQUEST['vehicleno'] != null && $_REQUEST['oid'] != null &&  $_REQUEST['loction'] != null){


        $location=$_REQUEST['loction'] ;
        $vehicleno=$_REQUEST['vehicleno'];
        $oid=$_REQUEST['oid'];
        // $sql=sqlsrv_query($connection,"SELECT ReceiptTicketID FROM TransactionData WHERE VehicleNumber='$vehicleno' and Status='Active' and Cu_orderID=$oid" , array(), array( "Scrollable" => 'static' ));
        // $num=sqlsrv_num_rows($sql);
 
        //     if($num >0){
        //       $gross="";
        //         while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        //           $sql121=sqlsrv_query($connection,"SELECT TD.GrossWeight,TD.GrossTime,Td.ReceiptTicketID from TransactionDetail TD inner join TransactionData TDx on TDx.ReceiptTicketID=TD.ReceiptTicketID
        //           and TD.SequenceNo = (select max(SequenceNo) from TransactionDetail where ReceiptTicketID = TD.ReceiptTicketID)
        //           Where GrossWeight is not null and (TDx.Final_Netweight is null or TDx.Final_Netweight = '0') and TareWeight is null  and TDx.VehicleNumber='$vehicleno' and Status='Active'" , array(), array( "Scrollable" => 'static' ));

        //       $num121=sqlsrv_num_rows($sql121);
 
        //         if($num121 >0){
                


        //           while($data1= sqlsrv_fetch_array($sql121,SQLSRV_FETCH_ASSOC)){
        //             $x1=$data1['ReceiptTicketID'];
        //            // $sql123456=sqlsrv_query($connection,"DELETE  from TransactionCauptureWeight where ReceiptTicketID='$x1'" , array(), array( "Scrollable" => 'static' ));
        //             $gross=$data1['GrossWeight']; 
        //             $datevalneedby=$data1['GrossTime'];
        //             $date_needby = date_format($datevalneedby, 'd/m/Y H:i:s');
        //             $response['status']=$gross;
        //             $response['message']=$data1['ReceiptTicketID'];
        //             $response['createdid']=$date_needby;
        //           }
        //         }
        //         else{
                  $response['status']="1";
                  $response['message']="abc";
        //         }
              

        //          //$sql1=sqlsrv_query($connection,"UPDATE  WeightBridge SET Weight='00.00' WHERE LocationName='$location'" , array(), array( "Scrollable" => 'static' ));
        //       }
        //       }
        //     else{
        //         $response['status']="0";
        //         $response['message']="No Any";
        //     }
      echo json_encode($response);                              
    sqlsrv_close($connection);
      }

?>



<?php 

// include "config.php";
// 	  $response=array();
//       $roles=array();
   

//       if($_REQUEST['vehicleno'] != null && $_REQUEST['oid'] != null &&  $_REQUEST['loction'] != null){


//         $location=$_REQUEST['loction'] ;
//         $vehicleno=$_REQUEST['vehicleno'];
//         $oid=$_REQUEST['oid'];
//         $sql=sqlsrv_query($connection,"SELECT ReceiptTicketID, FROM TransactionData WHERE VehicleNumber='$vehicleno' and Status='Active'" , array(), array( "Scrollable" => 'static' ));
// 		$num=sqlsrv_num_rows($sql);
 
//             if($num >0){
//                 while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                       
//                  $response['status']="1";
//                  $response['message']=$data['ReceiptTicketID'];

//                  //$sql1=sqlsrv_query($connection,"UPDATE  WeightBridge SET Weight='00.00' WHERE LocationName='$location'" , array(), array( "Scrollable" => 'static' ));
//               }
//               }
//             else{
//                 $response['status']="0";
//                 $response['message']="No Any";
//             }
//       echo json_encode($response);                              
//     sqlsrv_close($connection);
//       }

?>