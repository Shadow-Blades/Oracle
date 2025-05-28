<?php
 	include "config.php";
	  $response=array();
      $polist=array();


	if($_REQUEST['id'] != null){
	
     $id=$_REQUEST['id']; 


        $sql=sqlsrv_query($connection,"SELECT POD.ID as 'ponumberid',vendor.id as 'venodrid',POD.PO_NUMBER as 'ponumber',
        POD.LINE_ID as 'lineid',POD.ITEM_NAME as 'itemname' ,POD.unit_price,
        POD.CONTACT_PERSON as 'contactpersonname',POD.SHIP_TO as 'shipto',
        POD.BILL_TO as 'billto',POD.PO_QTY as 'isuueqty',POD.AVAILABLE_QTY as 'availableqty'
        ,POD.CREATION_DATE as 'pocreationtime' ,POD.NEED_BY as 'needbydt'
        ,POD.SITENAME as 'sitename',POD.CREATED_BY as 'createdid',
        vendor.user_mobile as 'vendormobile',
        vendor.user_fullname as 'vendorname',vendor.user_email as 'vendoremail',
        vendor.user_join_date from po_details as POD 
        inner JOIN cu_users as vendor on vendor.id=POD.V_ID 
        where vendor.id=$id AND MONTH(POD.NEED_BY)= MONTH(GETDATE()) and format(POD.NEED_BY,'yyyy-MM-dd') >= format(getdate(),'yyyy-MM-dd')  AND Status='Open' order by POD.ID desc", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql);
      
        if($num >0)
        {
           

			while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                // $img = file_get_contents(
                //     "images/".$data['image']);
                //     $dt = base64_encode($img);

                $datevalneedby=$data['needbydt'];
                $date_creationtime = date_format($datevalneedby, 'd/m/Y H:i:s');
                $actiontime=$data['pocreationtime'];
                $date_actiontime = date_format($actiontime, 'd/m/Y H:i:s');

              
                    array_push($polist,array("ponumberid"=>$data['ponumberid'],"ponumber"=>$data['ponumber'],"lineid"=>$data['lineid'],
                    "itemname"=>$data['itemname'],"shipto"=>$data['shipto'],"billto"=>$data['billto'],
                    "isuueqty"=>$data['isuueqty'],"availableqty"=>$data['availableqty']
                    ,"sitename"=>$data['sitename'],"vemail"=>$data['vendoremail'],"createdid"=>$data['createdid'],"needbydt"=>$date_creationtime
                    ,"pocreationtime"=>$date_actiontime,"unit_price"=>$data['unit_price']));
                
                 
              
            //  $response['images']=$dt;
             $response['status']=1;
             $response['vendorname']=$data['vendorname'];
             $response['vendorid']=$data['venodrid'];
             $response['vendoremail']=$data['vendoremail'];
             $response['vendorlocation']=$data['sitename'];
             $response['contactperson']=$data['contactpersonname'];
             $response['vendormobile']=$data['vendormobile'];
      
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="Your Mobile Number Is not Registered";
			
        }
      
        $response['po']=$polist;
       echo json_encode($response);
	}
	
	sqlsrv_close($connection);
	
	?>
