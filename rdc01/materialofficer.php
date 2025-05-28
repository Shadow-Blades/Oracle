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


	if($_REQUEST['sitename'] != null){
	
     $id=$_REQUEST['sitename']; 


        $sql=sqlsrv_query($connection,"SELECT TOP(25) POD.PO_NUMBER as 'ponumber',withinvoice as 'firstinvoice', withinvoice1 as 'secondinvoice' ,cu_orders.VEHICLE_NUMBER as 'vehiclenumber',driver.id as 'driverid',vendor.user_email as 'vendoremail',
        driver.user_fullname as 'drivername',driver.user_image as'images',
        driver.user_mobile as 'drivermobile',vendor.user_mobile as 'vendormobile',cu_orders.challan_no1,cu_orders.NET1,
        cu_orders.MOISTURE_PER as 'moisture',cu_orders.MRN_NO as 'mrnno',cu_orders.NET as 'vendornet',
        cu_orders.RDC_GROSS as 'rdcgross',cu_orders.RDC_TARE as 'rdctare',cu_orders.RDC_NET as 'rdcnet',
        cu_orders.TARE as 'tareweight',cu_orders.GROSS as 'grossweight',cu_orders.id as 'orderid',
        POD.ITEM_NAME as 'itemname',
        cu_orders.RECIEPT_IMAGE as 'reciept',POD.UOM as 'uom',POD.CONTACT_PERSON,
        ostatus.placed as 'placed',        cu_orders.created_at as 'crzeationtime',cu_orders.NET as 'netweight',

        ostatus.actiontime as 'lastaction',
        cu_orders.gross_time as 'grosstime',cu_orders.tare_time as 'taretime',
        cu_orders.CHALAN_NO as 'challanno',cu_orders.ROYALTI_PASS as 'royaltipassno',
        vendor.user_fullname as 'vendorname',POD.SITENAME as 'sitename' ,ostatus.pick as 'active',ostatus.denied as'cancel' ,ostatus.complate as'complete',ostatus.actiontime,ostatus.placed as 'placed'
        ,cu_orders.ACCEPT_BY as'acceptby' from cu_orders inner 
        JOIN cu_users as vendor on vendor.id=cu_orders.vendor_id 
		
        left join po_details as POD on POD.ID=cu_orders.PO_ID 

        left join cu_users as driver on driver.id=cu_orders.driver_id 
        left join cu_statuses as ostatus on ostatus.order_id=cu_orders.id
        where POD.SITENAME='$id'  and ostatus.actiontime >getdate() - 30
 order by ostatus.complate, cu_orders.id desc", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql);
      
        if($num >0)
        {
           

			while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                // $img = file_get_contents(
                //     "images/".$data['image']);
                //     $dt = base64_encode($img);
                        
                $datevalneedby=$data['lastaction'];
                if($datevalneedby != null){
                    $date_actiontime = date_format($datevalneedby, 'd/m/Y H:i:s');
                }
                else{
                    $date_actiontime="";
                }
            
                $actiontime=$data['crzeationtime'];
                $date_creationtime = date_format($actiontime, 'd/m/Y H:i:s');


                if($data['complete'] == '1' ){
                    array_push($complete,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],"actiontime"=>$date_creationtime,
                    "rdcgross"=>(int)$data['rdcgross'],"rdctare"=>(int)$data['rdctare'],"rdcnet"=>(int)$data['rdcnet'],
                    "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight'],"mrnno"=>$data['mrnno']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername']
                    ,"moisture"=>$data['moisture'],"reciept"=>$data['reciept'],"uom"=>$data['uom'],"acceptvy"=>$data['acceptby'],
                    "drivermobile"=>$data['drivermobile'],"contact"=>$data['CONTACT_PERSON'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $completecounter++;
                 
                }
                elseif($data['placed'] == '1' && $data['active'] == '0' &&  $data['cancel'] == '0'){
                
                    array_push($active,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>$data['grossweight'],"tareweight"=>$data['tareweight'],
                    "itemname"=>$data['itemname'],"actiontime"=>$date_creationtime,"netweight"=>$data['netweight'],"firstinvoice"=>$data['firstinvoice']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber'],"filename"=>"hii"
                    ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername'] ,"moisture"=>$data['moisture']
                    ,"reciept"=>$data['reciept'],"uom"=>$data['uom'],"drivermobile"=>$data['drivermobile'],"orderstatus"=>"pending","contact"=>$data['CONTACT_PERSON'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $activecounter++;
                }
                elseif($data['cancel'] == '1'){
                
                    array_push($cancel,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                    "grossweight"=>$data['grossweight'],"tareweight"=>$data['tareweight'],
                    "itemname"=>$data['itemname'],"actiontime"=>$date_creationtime,"netweight"=>$data['netweight']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"lastaction"=>$date_actiontime,"drivername"=>$data['drivername'] ,"moisture"=>$data['moisture']
                    ,"reciept"=>$data['reciept'],"uom"=>$data['uom'],"drivermobile"=>$data['drivermobile'],"orderstatus"=>"cancel","contact"=>$data['CONTACT_PERSON'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                    $cancelcounter++;
                }
                elseif($data['active'] == '1' && $data['cancel']== '0'){
                    // elseif($data['complete'] != '1' ){

                        array_push($active,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],
                        "grossweight"=>$data['grossweight'],"tareweight"=>$data['tareweight'],
                        "itemname"=>$data['itemname'],"actiontime"=>$date_creationtime,"netweight"=>$data['netweight'],"firstinvoice"=>$data['firstinvoice']
                        ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber'],"filename"=>"hii"
                        ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername'] ,"moisture"=>$data['moisture']
                        ,"reciept"=>$data['reciept'],"uom"=>$data['uom'],"drivermobile"=>$data['drivermobile'],"orderstatus"=>"pending","contact"=>$data['CONTACT_PERSON'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1']));
                        $activecounter++;
                }
			
            //  $response['images']=$dt;
            $response['status']=1;
            $response['message']="you have transacttions";
             $response['completecounter']=$completecounter;
             $response['activecounter']=$activecounter;
            //  $response['pendingcounter']=$pendingcounter;
              $response['cancelcounter']=$cancelcounter;
      
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="You Dont have Any request";
			
        }
     
        $response['com0letemo']=$complete;
        // $response['cancel']=$cancel;
        $response['activemo']=$active;
        $response['canceledmo']=$cancel;
        // $response['pending']=$pending;
       echo json_encode($response);
	}
	
	sqlsrv_close($connection);
	
	?>
