<?php
 	include "config.php";
	  $response=array();
      $complete=array();
      $cancel=array();
      $active=array();
      $avaitingforapproval=array();
      $completecounter=0;
      $cancelcounter=0;
      $activecounter=0;
      $pendingcounter=0;


	if($_REQUEST['id'] != null){
	
     $poid=$_REQUEST['id']; 



        $sql=sqlsrv_query($connection," SELECT  POD.PO_NUMBER as 'ponumber',cu_orders.VEHICLE_NUMBER as 'vehiclenumber',driver.id as 'driverid',vendor.user_email as 'vendoremail',
		cu_orders.challan_no1,cu_orders.NET1,cu_orders.withinvoice,cu_orders.withinvoice1,
        driver.user_fullname as 'drivername',driver.user_image as'images',
        driver.user_mobile as 'drivermobile',vendor.user_mobile as 'vendormobile',
        cu_orders.MOISTURE_PER as 'moisture',cu_orders.MRN_NO as 'mrnno',
        cu_orders.RDC_GROSS as 'rdcgross',cu_orders.RDC_TARE as 'rdctare',cu_orders.RDC_NET as 'rdcnet',
        cu_orders.TARE as 'tareweight',cu_orders.GROSS as 'grossweight',cu_orders.id as 'orderid',Table_1.status,Table_1.remarks,Table_1.name,Table_1.date,Table_1.approved_by,
        POD.ITEM_NAME as 'itemname',Table_1.remarks,Table_1.qty,Table_1.amount,Table_1.sapstatus,
        cu_orders.RECIEPT_IMAGE as 'reciept',POD.UOM as 'uom',Table_1.id as 'invoiceid',
        ostatus.placed as 'placed',Table_1.invoicenumber,Table_1.invoicedate,
        cu_orders.created_at as 'crzeationtime',cu_orders.NET as 'netweight',
        ostatus.actiontime as 'lastaction',
        cu_orders.gross_time as 'grosstime',cu_orders.tare_time as 'taretime',
        cu_orders.CHALAN_NO as 'challanno',cu_orders.ROYALTI_PASS as 'royaltipassno',
        vendor.user_fullname as 'vendorname',POD.SITENAME as 'sitename' ,ostatus.pick as 'active',ostatus.denied as'cancel' ,ostatus.complate as'complete',ostatus.actiontime,ostatus.placed as 'placed'
        ,cu_orders.ACCEPT_BY as'acceptby' from cu_orders inner 
        JOIN cu_users as vendor on vendor.id=cu_orders.vendor_id 
        left join po_details as POD on POD.ID=cu_orders.PO_ID 
        left join cu_users as driver on driver.id=cu_orders.driver_id 
        left join cu_statuses as ostatus on ostatus.order_id=cu_orders.id
		left join Table_1 on Table_1.order_id=cu_orders.id
        left join LocationMaster on LocationMaster.sitename=POD.SITENAME
        where Table_1.name is not null AND ostatus.actiontime IS NOT NULL and LocationMaster.id='$poid'  order by cu_orders.id desc", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql);
      
        if($num >0)
        {
           

			while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                // $img = file_get_contents(
                //     "images/".$data['image']);
                //     $dt = base64_encode($img);
                        
                $datevalneedby=$data['lastaction'];
                $date_actiontime = date_format($datevalneedby, 'd/m/Y H:i:s');
                $actiontime=$data['crzeationtime'];
                $date_creationtime = date_format($actiontime, 'd/m/Y H:i:s');


                 if($data['sapstatus'] == "In Progress" || $data['sapstatus'] == "Pending"){

                  array_push($avaitingforapproval,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],"invoiceid"=>$data['invoiceid'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],"actiontime"=>$date_creationtime,
                    "rdcgross"=>(int)$data['rdcgross'],"rdctare"=>(int)$data['rdctare'],"rdcnet"=>(int)$data['rdcnet'],
                    "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight'],"mrnno"=>$data['mrnno']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername']
                    ,"moisture"=>$data['moisture'],"reciept"=>$data['reciept'],"uom"=>$data['uom'],"acceptvy"=>$data['acceptby'],
                    "drivermobile"=>$data['drivermobile'],"invoicename"=>$data['name'],"invoiceremarks"=>$data['remarks'],"invoicedate"=>$data['date'],
                    "action_by"=>$data['approved_by'],"status"=>$data['status'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1'],"remarks"=>$data['remarks'],"qty"=>$data['qty'],"amount"=>$data['amount'],"invoicenumber"=>$data['invoicenumber'],"invoicedate"=>$data['invoicedate']));
                    $activecounter++;
                }
                // elseif($data['approved_by'] == '2'){
                
                //     array_push($complete,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],"invoiceid"=>$data['invoiceid'],
                //     "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],"actiontime"=>$date_creationtime,
                //     "rdcgross"=>(int)$data['rdcgross'],"rdctare"=>(int)$data['rdctare'],"rdcnet"=>(int)$data['rdcnet'],
                //     "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight'],"mrnno"=>$data['mrnno']
                //     ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                //     ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername']
                //     ,"moisture"=>$data['moisture'],"reciept"=>$data['reciept'],"uom"=>$data['uom'],"acceptvy"=>$data['acceptby'],
                //     "drivermobile"=>$data['drivermobile'],"invoicename"=>$data['name'],"invoiceremarks"=>$data['remarks'],"invoicedate"=>$data['date'],
                //     "action_by"=>$data['approved_by'],"status"=>$data['status'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1'],"remarks"=>$data['remarks'],"qty"=>$data['qty'],"amount"=>$data['amount'],"invoicenumber"=>$data['invoicenumber'],"invoicedate"=>$data['invoicedate']));
                //     $completecounter++;
                // }
                elseif($data['sapstatus'] == 'CANCELLED'){
                
                    array_push($cancel,array("orderid"=>$data['orderid'],"ponumber"=>$data['ponumber'],"vendorname"=>$data['vendorname'],"invoiceid"=>$data['invoiceid'],
                    "grossweight"=>(int)$data['grossweight'],"tareweight"=>(int)$data['tareweight'],"actiontime"=>$date_creationtime,
                    "rdcgross"=>(int)$data['rdcgross'],"rdctare"=>(int)$data['rdctare'],"rdcnet"=>(int)$data['rdcnet'],
                    "itemname"=>$data['itemname'],"netweight"=>(int)$data['netweight'],"mrnno"=>$data['mrnno']
                    ,"challanno"=>$data['challanno'],"sitename"=>$data['sitename'],"vehiclenumber"=>$data['vehiclenumber']
                    ,"lastaction"=>$date_actiontime ,"drivername"=>$data['drivername']
                    ,"moisture"=>$data['moisture'],"reciept"=>$data['reciept'],"uom"=>$data['uom'],"acceptvy"=>$data['acceptby'],
                    "drivermobile"=>$data['drivermobile'],"invoicename"=>$data['name'],"invoiceremarks"=>$data['remarks'],"invoicedate"=>$data['date'],
                    "action_by"=>$data['approved_by'],"status"=>$data['status'],"challan_no1"=>$data['challan_no1'],"NET1"=>$data['NET1'],"remarks"=>$data['remarks'],"qty"=>$data['qty'],"amount"=>$data['amount'],"invoicenumber"=>$data['invoicenumber'],"invoicedate"=>$data['invoicedate']));
                    $cancelcounter++;
                }
          
			
            //  $response['images']=$dt;
            $response['status']=1;
            $response['message']="you have transacttions";
             $response['completecounter']=$completecounter;
             $response['rejectcounter']=$cancelcounter;
             $response['waitingcounter']=$activecounter;
            //  $response['pendingcounter']=$pendingcounter;
            //  $response['cancelcounter']=$cancelcounter;
      
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="You Dont have Any request";
			
        }
        $response['complete']=$complete;
        // $response['cancel']=$cancel;

        $response['reject']=$cancel;
        $response['avaitingforapproval']=$avaitingforapproval;
        // $response['pending']=$pending;
       echo json_encode($response);
	}
	
	sqlsrv_close($connection);
	
	?>
