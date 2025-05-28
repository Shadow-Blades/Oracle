<?php
 
	  $response=array();
      $complete=array();
      $cancel=array();
      $active=array();
		$connection = mysqli_connect("182.50.133.79","weighing_cargo","Endel@D1g1tal","weighing_cargo") or die("Error " . mysqli_error($connection));

	if($_REQUEST['id'] != null && $_REQUEST['o_id'] != null){
	
     $c_id=$_REQUEST['id']; 
     $o_id=$_REQUEST['o_id']; 

        $sql=mysqli_query($connection,"select POD.PO_NUMBER as 'ponumber',cu_orders.VEHICLE_NUMBER as 'vehiclenumber',
        driver.user_fullname as 'drivername',driver.user_image as'images',
        driver.user_mobile as 'drivermobile',vendor.user_mobile as 'vendormobile',
        cu_orders.TARE as 'tareweight',cu_orders.GROSS as 'grossweight',
        POD.ITEM_NAME as 'itemname',
        cu_orders.created_at as 'creationtime',cu_orders.NET as 'netweight',
        cu_orders.gross_time as 'grosstime',cu_orders.tare_time as 'taretime',
        cu_orders.CHALAN_NO as 'challanno',cu_orders.ROYALTI_PASS as 'royaltipassno',
        vendor.user_fullname as 'vendorname',POD.SITENAME as 'sitename' ,ostatus.pick as 'active',ostatus.denied as'cancel' ,ostatus.complate as'complete',ostatus.actiontime
        
        from cu_orders inner join cu_users as driver on driver.id=cu_orders.driver_id
         LEFT JOIN po_details as POD on POD.ID=cu_orders.PO_ID 
         LEFT JOIN cu_users as vendor on vendor.ID=cu_orders.vendor_id 
         LEFT JOIN cu_statuses as ostatus on ostatus.order_id=cu_orders.id
         where driver.user_mobile='8238409858'");
		$num=mysqli_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql);
      
        if($num >0)
        {
           

			while($data= mysqli_fetch_assoc($sql)){
                $img = file_get_contents(
                    "images/".$data['image']);
                    $dt = base64_encode($img);


			array_push($complete,array("ponumber"=>$data['ponumber'],"vehiclenumber"=>$data['vehiclenumber'],
            "drivername"=>$data['drivername'],"creationtime"=>$data['creationtime'],"netweight"=>$data['netweight'],
            "tareweight"=>$data['tareweight'],"grossweight"=>$data['grossweight'],"image_byte"=>$dt));
		  $response['drivername']=$data['drivername'];
          $response['no_of_pkg']=$data['no_of_pkg'];
        $response['total_amount']=$data['grand_total'];
        $response['party_name']=$data['to_name'];
        $response['party_mobile']=$data['to_mobile'];
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="You Dont have Any request";
			
        }
        $response['orders']=$order;
       echo json_encode($response);
	}
	
	mysqli_close($connection);
	
	?>
