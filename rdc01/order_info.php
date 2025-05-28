<?php
 	include "config.php";
	  $response=array();
      $complete=array();
      $cancel=array();
      $active=array();
      $pending=array();
      $completecounter=0;
      $cancelcounter=0;
      $activecounter=0;
      $pendingcounter=0;


	if($_REQUEST['mobile'] != null){

	

     $mobile=$_REQUEST['mobile']; 


        $sql=sqlsrv_query($connection,"SELECT TOP(5) role.id as roleid, role.role_name as rolename,POD.PO_NUMBER as 'ponumber',cu_orders.VEHICLE_NUMBER as 'vehiclenumber',driver.id as 'driverid',vendor.user_email as 'vendoremail',
        driver.user_fullname as 'drivername',driver.user_image as'images',
        driver.user_mobile as 'drivermobile',vendor.user_mobile as 'vendormobile',
        cu_orders.TARE as 'tareweight',cu_orders.GROSS as 'grossweight',cu_orders.id as 'orderid',
        POD.ITEM_NAME as 'itemname',cu_orders.challan_no1,cu_orders.NET1,
        ostatus.placed as 'placed',
        cu_orders.created_at as 'creationtime',cu_orders.NET as 'netweight',
        cu_orders.gross_time as 'grosstime',cu_orders.tare_time as 'taretime',cu_orders.RDC_NET as 'rdcnet',
        cu_orders.CHALAN_NO as 'challanno',cu_orders.ROYALTI_PASS as 'royaltipassno',
        vendor.user_fullname as 'vendorname',POD.SITENAME as 'sitename' ,ostatus.pick as 'active',ostatus.denied as'cancel' ,ostatus.complate as'complete',ostatus.actiontime,ostatus.placed as 'placed'
        from cu_orders inner join cu_users as driver on driver.id=cu_orders.driver_id
        LEFT JOIN cu_userroles as role on role.id=driver.role_id
         LEFT JOIN po_details as POD on POD.ID=cu_orders.PO_ID 
         LEFT JOIN cu_users as vendor on vendor.ID=cu_orders.vendor_id 
         LEFT JOIN cu_statuses as ostatus on ostatus.order_id=cu_orders.id
         where driver.user_mobile='$mobile'  and (role.id=4 or role.id=5) and driver.IsActive=1  order by cu_orders.id desc", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql); 
      
        if($num >0)
        {
           

			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                // $img = file_get_contents(
                //     "images/".$data['image']);
                //     $dt = base64_encode($img);
                $datevalneedby=$data['creationtime'];
                $date_creationtime = date_format($datevalneedby, 'd/m/Y H:i:s');
                $actiontime=$data['actiontime'];
                $date_actiontime = date_format($actiontime, 'd/m/Y H:i:s');

                if($data['complete'] == '1' ){
                   
                    array_push($complete,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],"actiontime"=>$date_actiontime,
                    "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"creationtime"=>$date_creationtime ,"vendoremail"=>$data['vendoremail'],"rdcnetweight"=>$data['rdcnet'],"challan_no1"=>$data['challan_no1'],"challan_no1"=>$data['NET1']));
                    $completecounter++;
                 
                }
                elseif($data['placed'] == '1' && $data['active'] == '0' &&  $data['cancel'] == '0'){
                    array_push($pending,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],
                    "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight'],"actiontime"=>$date_actiontime
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"creationtime"=>$date_creationtime ,"vendoremail"=>$data['vendoremail'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $pendingcounter++;
                }
                elseif($data['active'] == '0' && $data['cancel']== '1'){
                    array_push($cancel,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],
                    "itemname"=>$data['itemname'],"actiontime"=>$date_actiontime
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"creationtime"=>$date_creationtime ,"vendoremail"=>$data['vendoremail'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $cancelcounter++;
                }
                   elseif($data['active'] == '1' && $data['cancel']== '0'){
                    array_push($active,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],
                    "itemname"=>$data['itemname'],"actiontime"=>$date_actiontime
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"creationtime"=>$date_creationtime ,"vendoremail"=>$data['vendoremail'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $cancelcounter++;
                }
			
            //  $response['images']=$dt;
             $response['status']=1;
             $response['rolename']=$data['rolename'];
             $response['drivername']=$data['drivername'];
             $response['driverid']=$data['driverid'];
             $response['completecounter']=$completecounter;
             $response['activecounter']=$activecounter;
             $response['pendingcounter']=$pendingcounter;
             $response['cancelcounter']=$cancelcounter;
      
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="You Dont have Any request";
			
        }
        $response['complete']=$complete;
        $response['cancel']=$cancel;
        $response['active']=$active;
        $response['pending']=$pending;
       echo json_encode($response);
	}
	
sqlsrv_close($connection);
	
	?>
