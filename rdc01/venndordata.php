<?php
 	include "config.php";
	  $response=array();
      $polist=array();


	if($_REQUEST['mobile'] != null && $_REQUEST['vsapid'] != null && $_REQUEST['password'] != "noany"){
	
     $mobile=$_REQUEST['mobile']; 
     $vsapid=$_REQUEST['vsapid'];
     $password=$_REQUEST['password'];


        $sql=sqlsrv_query($connection,"	select TOP(1) POD.ID as 'ponumberid',vendor.id as 'venodrid',POD.PO_NUMBER as 'ponumber',vendor.user_image,
        POD.LINE_ID as 'lineid',POD.ITEM_NAME as 'itemname' ,
        POD.CONTACT_PERSON as 'contactpersonname',POD.SHIP_TO as 'shipto',
        POD.BILL_TO as 'billto',POD.PO_QTY as 'isuueqty',POD.AVAILABLE_QTY as 'availableqty'
        ,POD.CREATION_DATE as 'pocreationtime' ,POD.NEED_BY as 'needbydt'
        ,POD.SITENAME as 'sitename',POD.CREATED_BY as 'createdid',
        vendor.user_mobile as 'vendormobile',vendor.location_id as 'locid',
        vendor.user_fullname as 'vendorname',vendor.user_email as 'vendoremail',
        vendor.user_join_date from po_details as POD 
        inner JOIN cu_users as vendor on vendor.id=POD.V_ID 
        where vendor.user_password='$password' and  vendor.user_mobile='$mobile' AND POD.AP_SEGMENT1='$vsapid'  order by POD.ID desc", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        //$data1= mysqli_fetch_assoc($sql);
      
        if($num >0)
        {
           

			while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                // $img = file_get_contents(
                //     "images/".$data['image']);
                //     $dt = base64_encode($img);

                        
                $datevalneedby=$data['needbydt'];
                $date_needby = date_format($datevalneedby, 'd/m/Y H:i:s');
                $actiontime=$data['pocreationtime'];
                $date_cration = date_format($actiontime, 'd/m/Y H:i:s');

              
                    array_push($polist,array("ponumberid"=>$data['ponumberid'],"ponumber"=>$data['ponumber'],"lineid"=>$data['lineid'],
                    "itemname"=>$data['itemname'],"shipto"=>$data['shipto'],"billto"=>$data['billto'],
                    "isuueqty"=>$data['isuueqty'],"availableqty"=>$data['availableqty']
                    ,"sitename"=>$data['sitename'],"vemail"=>$data['vendoremail'],"createdid"=>$data['createdid'],"needbydt"=>$date_needby
                    ,"pocreationtime"=>$date_cration));
                
                 
              
            //  $response['images']=$dt;
             $response['status']=1;
             $response['vendorname']=$data['vendorname'];
             $response['vendorid']=$data['venodrid'];
             $response['vendoremail']=$data['vendoremail'];
             $response['vendorlocation']=$data['sitename'];
             $response['contactperson']=$data['contactpersonname'];
             $response['vendormobile']=$data['vendormobile'];
             $response['locid']=$data['locid'];
             $response['checkattempt']=$data['user_image'];
      
      
			}
		
        }
        else
        {
            $response['status']=0;
            $response['message']="Your Mobile Number Is not Registered";
			
        }
      
        $response['polist']=$polist;
       echo json_encode($response);
	}
    else{
        $mobile=$_REQUEST['mobile']; 
        $vsapid=$_REQUEST['vsapid'];
        // $password=$_REQUEST['password'];
   
   
           $sql=sqlsrv_query($connection,"	select TOP(1) POD.ID as 'ponumberid',vendor.id as 'venodrid',POD.PO_NUMBER as 'ponumber',vendor.user_image,
           POD.LINE_ID as 'lineid',POD.ITEM_NAME as 'itemname' ,
           POD.CONTACT_PERSON as 'contactpersonname',POD.SHIP_TO as 'shipto',
           POD.BILL_TO as 'billto',POD.PO_QTY as 'isuueqty',POD.AVAILABLE_QTY as 'availableqty'
           ,POD.CREATION_DATE as 'pocreationtime' ,POD.NEED_BY as 'needbydt'
           ,POD.SITENAME as 'sitename',POD.CREATED_BY as 'createdid',
           vendor.user_mobile as 'vendormobile',vendor.location_id as 'locid',
           vendor.user_fullname as 'vendorname',vendor.user_email as 'vendoremail',
           vendor.user_join_date from po_details as POD 
           inner JOIN cu_users as vendor on vendor.id=POD.V_ID 
           where   vendor.user_mobile='$mobile' AND POD.AP_SEGMENT1='$vsapid' AND format(POD.NEED_BY,'yyyy-MM-dd') >= format(getdate(),'yyyy-MM-dd') AND   MONTH(POD.NEED_BY)= MONTH(GETDATE())  order by POD.ID desc", array(), array( "Scrollable" => 'static' ));
           $num=sqlsrv_num_rows($sql);
           //$data1= mysqli_fetch_assoc($sql);
         
           if($num >0)
           {
              
   
               while($data= sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                   // $img = file_get_contents(
                   //     "images/".$data['image']);
                   //     $dt = base64_encode($img);
   
                           
                   $datevalneedby=$data['needbydt'];
                   $date_needby = date_format($datevalneedby, 'd/m/Y H:i:s');
                   $actiontime=$data['pocreationtime'];
                   $date_cration = date_format($actiontime, 'd/m/Y H:i:s');
   
                 
                       array_push($polist,array("ponumberid"=>$data['ponumberid'],"ponumber"=>$data['ponumber'],"lineid"=>$data['lineid'],
                       "itemname"=>$data['itemname'],"shipto"=>$data['shipto'],"billto"=>$data['billto'],
                       "isuueqty"=>$data['isuueqty'],"availableqty"=>$data['availableqty']
                       ,"sitename"=>$data['sitename'],"vemail"=>$data['vendoremail'],"createdid"=>$data['createdid'],"needbydt"=>$date_needby
                       ,"pocreationtime"=>$date_cration));
                   
                    
                 
               //  $response['images']=$dt;
                $response['status']=1;
                $response['vendorname']=$data['vendorname'];
                $response['vendorid']=$data['venodrid'];
                $response['vendoremail']=$data['vendoremail'];
                $response['vendorlocation']=$data['sitename'];
                $response['contactperson']=$data['contactpersonname'];
                $response['vendormobile']=$data['vendormobile'];
                $response['locid']=$data['locid'];
                $response['checkattempt']=$data['user_image'];
         
         
               }
           
           }
           else
           {
               $response['status']=0;
               $response['message']="Your Mobile Number Is not Registered";
               
           }
         
           $response['polist']=$polist;
          echo json_encode($response);
    }
	
sqlsrv_close($connection);
	
	?>


<?php
// include "newconfig.php";
?>


