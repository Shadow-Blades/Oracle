<?php

    include "config.php";
    $Q2="SELECT * FROM Sap_PO where NEED_BY_DATE >= CURRENT_TIMESTAMP";
    $res2=sqlsrv_query($connection,$Q2);
    $value=array();
    $response=array();
    $polist=array();
    $flag='';
    $v_id=1;
    $ismapped='false';
    
	if($_REQUEST['id'] != null){

    while( $row1 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC) ) {

        $datevalneedby=$row1['NEED_BY_DATE'];
        $date_stringneedby = date_format($datevalneedby, 'Y/m/d H:i:s');
        $datevalcreation=$row1['creation_date'];
        $date_stringcretedby = date_format($datevalcreation, 'Y/m/d H:i:s');
        $currentpo=$row1['PO_NUM'];
        $lineid=$row1['po_line_id'];
        $itemname=$row1['SEGMENT1'];
        $contact_person=$row1['vendor_name'];
        $ship_to=$row1['Ship_location_code'];
        $billto=$row1['Bill_location_code'];
        $poqty=$row1['QUANTITY'];
        $avalableqty=$row1['QUANTITY_Avalable'];
        $creationdate=$date_stringcretedby ;
        $needbydt=$date_stringneedby;
        $sitename=$row1['organization_name'];
        $createdby=$row1['created_by'];
        $uom=$row1['UNIT_MEAS_LOOKUP_CODE'];
        $linenum=$row1['LINE_NUM'];
        $qty_received=$row1['QUANTITY_RECEIVED'];
        $apsegement=$row1['AP_SEGMENT1'];
        $vendorsitecode=$row1['vendor_SITE_CODE'];
        $suppliername=$row1['supplierName'];
        $organizationid=$row1['organization_id'];
        $vendorsiteid=$row1['vendor_site_id'];
        $poheaderid=$row1['po_header_id'];
        $linelocid=$row1['line_location_id'];
        $posegment=$row1['PO_SEGMENT1'];
        $vendorid=$row1['vendor_id'];
        $v_email=$row1['vendor_Email'];


        $res6=sqlsrv_query($connection,"select * from po_details where AP_SEGMENT1='$apsegement' AND IS_MAPPED='true'", array(), array( "Scrollable" => 'static' ));
        $numofoldpo1=sqlsrv_num_rows($res6);
        if($numofoldpo1 > 0){

            $data11= sqlsrv_fetch_array($res6,SQLSRV_FETCH_ASSOC);
            $v_id=$data11['V_ID'];
            $ismapped='true'; 
        }   
       
        $res3=sqlsrv_query($connection,"select * from po_details where PO_NUMBER = $currentpo AND AP_SEGMENT1='$apsegement' AND IS_MAPPED='true'", array(), array( "Scrollable" => 'static' ));
        $numofoldpo=sqlsrv_num_rows($res3);
        if($numofoldpo > 0){
          
            }
            else{
                $qry="INSERT INTO po_details
                (PO_NUMBER,LINE_ID,ITEM_NAME ,V_ID,CONTACT_PERSON,SHIP_TO
                ,BILL_TO,PO_QTY ,AVAILABLE_QTY,CREATION_DATE,NEED_BY,SITENAME
                ,CREATED_BY,UOM,LINE_NUM,QTY_RECEIVED,AP_SEGMENT1,VENDOR_SITE_CODE
                ,SUUPLIER_NAME,ORGANIZATION_ID,VENDOR_SITE_ID,PO_HEADER_ID
                ,LINE_LOCATION_ID,PO_SEGMENT1,IS_MAPPED,VENDOR_NAME,REC_QTY,VENDOR_SAP_ID,VENDOR_EMAIL) VALUES
                ($currentpo,$lineid,'$itemname',$v_id,'$contact_person'
                ,'$ship_to','$billto',$poqty,$avalableqty,'$creationdate'
                ,'$needbydt','$sitename',$createdby,'$uom','$linenum'
                ,$qty_received,'$apsegement','$vendorsitecode','mm'
                ,$organizationid,$vendorsiteid,$poheaderid,$linelocid,'$posegment','$ismapped','$contact_person',null,'$vendorid','$v_email')";

            
                $res5=sqlsrv_query($connection,$qry);
                if($res5){
                    $response['status']=1;
                    $response['message']="import successfully";
                }
                else{
                 
                    $response['status']=0;
                    $response['message']="import fail";
                }
            }
            $res4=sqlsrv_query($connection,"select * from LocationMaster where Sitename='$sitename'", array(), array( "Scrollable" => 'static' ));
            $registeredloc=sqlsrv_num_rows($res4);

                if($registeredloc > 0){
                  $var="one";
                }
                else{
                    $qry="insert into LocationMaster (Sitename,IsActive,SiteLocation) values('$sitename','1','$sitename')";
    
                
                    $res5=sqlsrv_query($connection,$qry);
                }
            
      
     
        
    }
    if(!$flag){

    }
    echo json_encode($response);    
    }
?>