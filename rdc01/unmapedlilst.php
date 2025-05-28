<?php




    include "config.php";
    

    $mapped=array();
    $response=array();
    $unmapeed=array();
    $mappedcounter=0;
    $unmappedcounter=0;
    $uptodate="yes";
    $flag='';
    $locationname="";
    
	if($_REQUEST['location'] != null){

        $loc=$_REQUEST['location'];
        $locationname=$_REQUEST['location'];
        $Q2="SELECT * from po_details INNER JOIN  cu_users on cu_users.id=po_details.V_ID where 
        SITENAME='$loc' AND Status='Open' AND MONTH(po_details.NEED_BY)= MONTH(GETDATE()) and
         format(NEED_BY,'yyyy-MM-dd') >= format(getdate(),'yyyy-MM-dd')  order by po_details.ID DESC";
        $res2=sqlsrv_query($connection,$Q2);
    while( $row1 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC) ) {

        $datevalneedby=$row1['NEED_BY'];
        $date_stringneedby = date_format($datevalneedby, 'd/m/Y H:i:s');
        $datevalcreation=$row1['CREATION_DATE'];
        $date_stringcretedby = date_format($datevalcreation, 'd/m/Y H:i:s');
        if($row1['IS_MAPPED']=='true'){
            array_push($mapped,array("ponumberid"=>$row1['ID'],"ponumber"=>$row1['PO_NUMBER'],"lineid"=>$row1['LINE_ID'],
            "itemname"=>$row1['ITEM_NAME'],"shipto"=>$row1['SHIP_TO'],"billto"=>$row1['BILL_TO'],
            "isuueqty"=>$row1['PO_QTY'],"availableqty"=>$row1['AVAILABLE_QTY'],"vendoremail"=>$row1['VENDOR_EMAIL'],"contactperson"=>$row1['CONTACT_PERSON']
            ,"sitename"=>$row1['SITENAME'],"createdid"=>$row1['CREATED_BY'],"needbydt"=>"$date_stringneedby","vendorid"=>$row1['AP_SEGMENT1']
            ,"pocreationtime"=>"$date_stringcretedby",'UOM'=>$row1['UOM'],"ismapped"=>$row1['IS_MAPPED'],"mobile"=>$row1['user_mobile'],"vendorname"=>$row1['user_fullname'],"vendorsysid"=>$row1['V_ID']));
            $mappedcounter++;
        }
        elseif($row1['IS_MAPPED']=='false'){
            array_push($unmapeed,array("ponumberid"=>$row1['ID'],"ponumber"=>$row1['PO_NUMBER'],"lineid"=>$row1['LINE_ID'],
            "itemname"=>$row1['ITEM_NAME'],"shipto"=>$row1['SHIP_TO'],"billto"=>$row1['BILL_TO'],
            "isuueqty"=>$row1['PO_QTY'],"availableqty"=>$row1['AVAILABLE_QTY'],"vendoremail"=>$row1['VENDOR_EMAIL'],"contactperson"=>$row1['CONTACT_PERSON']
            ,"sitename"=>$row1['SITENAME'],"createdid"=>$row1['CREATED_BY'],"needbydt"=>$date_stringneedby,"vendorid"=>$row1['AP_SEGMENT1']
            ,"pocreationtime"=>$date_stringcretedby,'UOM'=>$row1['UOM'],"ismapped"=>$row1['IS_MAPPED'],"vendorname"=>$row1['user_fullname']));
            $unmappedcounter++;
        }


    }
    $response['mappedcounter']=$mappedcounter;
    $response['uptodate']=$uptodate;
    $response['unmappedcounter']=$unmappedcounter;
    $response['mapped']=$mapped;
    $response['unmapped']=$unmapeed;
    echo json_encode($response);
}
sqlsrv_close($connection);
 ?>
 
<?php
include "newconfig.php";
?>