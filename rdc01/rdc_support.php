<?php 

include "config.php";
	  $response=array();
      $rdcdetails=array();
      $moisturecomplete=array();
      $moisturepending=array();
   
$loction='';
      if($_REQUEST['mobile'] != null){
        
        $mobile=$_REQUEST['mobile'];      
        $sql=sqlsrv_query($connection,"	 
        
	   select rdcsupport.user_fullname as 'name',rdcsupport.id as 'userid',rdcsupport.user_email as 'useremail',rdcsupport.user_mobile as 'usermonbile',
	   lmc.id as 'locationid',lmc.Sitename as'sitename',cu_userroles.role_name as'role'
	   from cu_users as rdcsupport  inner join LocationMaster as lmc on lmc.id=rdcsupport.location_id
	   left join cu_userroles on cu_userroles.id=rdcsupport.role_id where
	   role_name != 'DRIVER' AND role_name != 'VENDOR' and  role_name != 'DRIVER' 
		and  role_name != 'USER' and  role_name != 'COLLECTOR' AND rdcsupport.user_mobile='$mobile'" , array(), array( "Scrollable" => 'static' ));

		$num=sqlsrv_num_rows($sql);
        if($num >0){
            
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
             
        $response=$data;
        $response["status"]=1;
        $loction=$data['sitename'];
        }
      
      }
      else{
        $response["status"]=0;
    }

      $sql11=sqlsrv_query($connection,"	 SELECT  TOP(25) pod.PO_NUMBER as 'ponumber',pod.ITEM_NAME as 'itemname',vendor.user_mobile 'vendormobile',driver.user_mobile as 'drivercontact' ,orders.MOISTURE_CHECK as 'moisturestatus', orders.CHALAN_NO as 'challanno',orders.created_at as  'createdtime',orders.id as 'orderid',
      orders.NET as 'netweight',orders.GROSS as 'grossweight',orders.TARE as 'tareweight',
      orders.VEHICLE_NUMBER as 'vehicleno',orders.PO_ID as 'poid',driver.user_fullname as 'drivername',
      driver.id as 'driverid',vendor.user_email as 'vendoremail',vendor.user_fullname as 'vendorname',pod.CONTACT_PERSON,
      vendor.user_mobile as 'vendormobile',orders.MOISTURE_PER as 'mper',odstatus.reach_site as reachsite
      from cu_orders as orders inner join po_details as pod on pod.ID=orders.PO_ID 
      left join cu_users as driver on driver.id=orders.driver_id
      left join cu_users as vendor on vendor.id=orders.vendor_id
      left join cu_statuses as odstatus on odstatus.order_id=orders.id
      where  pod.SITENAME='$loction' AND odstatus.denied!=1 AND odstatus.pick=1 and pod.status='Open' order by orders.id desc" , array(), array( "Scrollable" => 'static' ));

   	$num11=sqlsrv_num_rows($sql11);
       if($num11 >0){
        while($data= sqlsrv_fetch_array($sql11,SQLSRV_FETCH_ASSOC)){
            $actiontime=$data['createdtime'];
            $date_actiontime = date_format($actiontime, 'd/m/Y H:i:s');
            if($data['moisturestatus']=="0" && $data['reachsite']=="1") {
                array_push($moisturepending,array("ponumber"=>$data['ponumber'],"challanno"=>$data['challanno'],"createdtime"=>$date_actiontime,"orderid"=>$data['orderid'],"itemname"=>$data['itemname'],
                "netweight"=>$data['netweight'],"grossweight"=>$data['grossweight'],"tareweight"=>$data['tareweight'],"vehicleno"=>$data['vehicleno'],"vendormobile"=>$data['vendormobile'],"drivercontact"=>$data['drivercontact'],
               "vendorname"=>$data['vendorname'] ,"drivername"=>$data['drivername'] ,"driverid"=>$data['driverid'],"vendoremail"=>$data['vendoremail'],"sitename"=>$loction,"contact"=>$data['CONTACT_PERSON']
            ));
            }
            else{
                array_push($moisturecomplete,array("ponumber"=>$data['ponumber'],"challanno"=>$data['challanno'],"createdtime"=>$date_actiontime,"orderid"=>$data['orderid'],"itemname"=>$data['itemname'],
                "netweight"=>$data['netweight'],"grossweight"=>$data['grossweight'],"tareweight"=>$data['tareweight'],"vehicleno"=>$data['vehicleno'],"vendormobile"=>$data['vendormobile'],"drivercontact"=>$data['drivercontact'],
                "vendorname"=>$data['vendorname'] ,"drivername"=>$data['drivername'] ,"driverid"=>$data['driverid'],"vendoremail"=>$data['vendoremail'],"sitename"=>$loction,"mper"=>$data['mper'],"contact"=>$data['CONTACT_PERSON']
            ));
            }   
        }
       }

       $response['completemoisture']=$moisturecomplete;
       $response['pendingmoisture']=$moisturepending;
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>