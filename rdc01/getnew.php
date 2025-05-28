<?php
  $response=array();
  include "config.php";
	
      $roles=array();
      $val="";
      $value=array();
      $polist=array();
      $flag='';
      $v_id=1;
      $ismapped='false';
      $val1='';
     
      

        $sql=sqlsrv_query($connection,"SELECT * from SITECODE  where SITECODE.SiteCodeName not in(select SiteCodeName from LocationMaster)" , array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
    $count=0;
        if($num >0){
            
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
        $count++;
        if($count!=1){
                $val.=",'".$data['SiteCodeName']."'";
                //array_push($roles,array("locationname"=>$data['Sitename'],"locationid"=>$data['id']));
        }
        else{
          $val.="'".$data['SiteCodeName']."'";
        }
 
      }
    }


// Create connection to Oracle
$conn = oci_connect("appsread", "apprdcc123", "192.168.100.11:1528/RDCAZPRD");


 //$conn = oci_connect("appsread","apprdcc123", "192.168.0.10:1526/DEV");
if (!$conn) {

  $sql1252=sqlsrv_query($connection,"UPDATE cu_users SET IsActive=0 where id=1" , array(), array( "Scrollable" => 'static' ));
   $m = oci_error();
  //  print_r($m);
   exit;
}
else {
  $sql1252=sqlsrv_query($connection,"UPDATE cu_users SET IsActive=1 where id=1" , array(), array( "Scrollable" => 'static' ));

}
// Close the Oracle connection


// $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST =192.168.0.10)(PORT = 1526)))(CONNECT_DATA=(SID=DEV)))" ;

// if($c = OCILogon("appsread", "apprdcc123", $db))
// {
  
//     OCILogoff($c);
// }
// else
// {
//     $err = OCIError();
//     echo "Connection failed." . $err[text];
// }




$s1 = "SELECT poh.segment1 PO_NUM, pol.LINE_NUM,
ood.organization_name,
msi.SEGMENT1,
msi.description Item_Description,
pol.UNIT_MEAS_LOOKUP_CODE,
pol.QUANTITY,
pol.QUANTITY - pll.QUANTITY_RECEIVED as QUANTITY_Avalable,pll.QUANTITY_RECEIVED,
pll.NEED_BY_DATE,
poh.creation_date,
poh.created_by,
asa.SEGMENT1 as   VENDORCODE  ,
plv1.location_code,
plv2.location_code,
pol.unit_price, 
assa.vendor_SITE_CODE,
apc.first_name
|| ' '
|| apc.last_name as supplierName,
ood.organization_id,
asa.vendor_name,
asa.vendor_id,
assa.vendor_site_id,
poh.po_header_id,
pol.po_line_id,
pll.line_location_id,
 ASA.attribute1 as Vendor_Email
FROM apps.PO_HEADERS_ALL POH,
apps.PO_LINES_ALL POL,
apps.PO_LINE_locations_ALL pLL,
apps.MTL_SYSTEM_ITEMS_B msi,
apps.ap_supplier_contacts apc,
apps.ap_suppliers asa,
apps.ap_supplier_sites_all assa,
apps.org_organization_definitions ood,
apps.po_locations_val_v plv1,
apps.po_locations_val_v plv2,
apps.mtl_item_categories mic,
apps.mtl_categories mc
 WHERE(poh.po_header_id = pol.po_header_id)
 AND pll.po_header_id = pol.po_header_id
 AND pll.po_line_id = pol.po_line_id
 AND pol.item_id = msi.inventory_item_id
 AND poh.vendor_contact_id = apc.vendor_contact_id(+)
 AND asa.vendor_id = poh.vendor_id
 AND assa.vendor_id = poh.vendor_id
 AND assa.vendor_site_id = poh.vendor_site_id
 AND msi.organization_id = pll.ship_to_organization_id
 AND ood.organization_id = pll.ship_to_organization_id
 AND pol.QUANTITY - pll.QUANTITY_RECEIVED > 0
 AND plv1.location_id = poh.ship_to_location_id
 AND plv2.location_id = poh.bill_to_location_id
 AND TRUNC(pll.need_by_date)         >= TRUNC(sysdate)
 and mic.inventory_item_id = msi.inventory_item_id
 AND mic.organization_id = msi.organization_id
 and ood.organization_code IN (....HERE....)
 AND mic.category_id = mc.category_id
 and poh.authorization_status = 'APPROVED'
 AND mc.segment1 = 'Raw Matl'
 AND poh.po_header_id NOT IN (SELECT poh.po_header_id
 FROM apps.po_action_history pah
 WHERE PAH.object_id = poh.po_header_id
 AND pah.object_type_code = 'PO'
 AND pll.CLOSED_CODE <> 'CLOSED FOR RECEIVING'
 AND pah.action_code IN('FINALLY CLOSE','CLOSE'))";
// oci_execute($s);
// oci_fetch_all($s, $res);
// echo "<pre>\n";
// var_dump($res);
// echo "</pre>\n";
$s2=str_replace("....HERE....",$val,$s1);
$s=oci_parse($conn,$s2);



// echo $s2;
oci_execute($s);

$counter12=0;
while (($row1 = oci_fetch_assoc($s)) != false) {
    // Use the uppercase column names for the associative array indices
  $need=$row1['NEED_BY_DATE'];
  $crete=$row1['CREATION_DATE'];

  $counter12++;
  if($counter12!=1){
    $val1.=",'".$row1['PO_NUM']."'";
    //array_push($roles,array("locationname"=>$data['Sitename'],"locationid"=>$data['id']));
}
else{
$val1.="'".$row1['PO_NUM']."'";
}


$datevalneedby=DateTime::createFromFormat('j-F-y', $need);
        $date_stringneedby = $datevalneedby->format('Y/m/d');
        $datevalcreation= DateTime::createFromFormat('j-F-y', $crete);
        $date_stringcretedby = $datevalcreation->format('Y/m/d');

    $currentpo=$row1["PO_NUM"];
    $lineid=$row1["PO_LINE_ID"];
    $itemname=$row1["SEGMENT1"];

    $contact_person  = strlen($row1["VENDOR_NAME"]) > 20 ? substr($row1["VENDOR_NAME"],0,20)."..." : $row1["VENDOR_NAME"];

   // $contact_person=strtoupper($row1["VENDOR_NAME"]);
   
    $ship_to=$row1["ORGANIZATION_NAME"];
    $billto=$row1["ORGANIZATION_NAME"];
    $poqty=$row1["QUANTITY"];
    $avalableqty=$row1["QUANTITY_AVALABLE"];
    $creationdate=$date_stringcretedby ;
    $needbydt=$date_stringneedby;
    $sitename=$row1["ORGANIZATION_NAME"];
    $createdby=$row1["CREATED_BY"];
    $uom=$row1["UNIT_MEAS_LOOKUP_CODE"];
    $linenum=$row1["LINE_NUM"];
    $qty_received=$row1["QUANTITY_RECEIVED"];
    $apsegement=$row1["VENDORCODE"];
    $vendorsitecode=$row1["VENDOR_SITE_CODE"];
    $suppliername=$row1["SUPPLIERNAME"];
    $organizationid=$row1["ORGANIZATION_ID"];
    $vendorsiteid=$row1["VENDOR_SITE_ID"];
    $poheaderid=$row1["PO_HEADER_ID"];
    $linelocid=$row1["VENDOR_SITE_ID"];
    $unitprice=$row1["UNIT_PRICE"];
    $posegment='';
    $vendorid=$row1["VENDOR_ID"];
    $v_email=$row1["VENDOR_EMAIL"];
    $locationname=$row1["ORGANIZATION_NAME"];
    echo $v_email.'<->'.$contact_person.$apsegement.'<br>'. $sitename;
    




    $res6=sqlsrv_query($connection,"SELECT * from po_details where AP_SEGMENT1='$apsegement' AND IS_MAPPED='true'  ", array(), array( "Scrollable" => 'static' ));
    $numofoldpo1=sqlsrv_num_rows($res6);
    if($numofoldpo1 > 0){

        $data11= sqlsrv_fetch_array($res6,SQLSRV_FETCH_ASSOC);
        $v_id=$data11['V_ID'];
        $ismapped='true'; 
    }


    $res3=sqlsrv_query($connection,"select * from po_details where PO_NUMBER = $currentpo AND AP_SEGMENT1='$apsegement' ", array(), array( "Scrollable" => 'static' ));
    $numofoldpo=sqlsrv_num_rows($res3);
    if($numofoldpo > 0){
      $qry1111="UPDATE po_details set unit_price='$unitprice',VENDOR_EMAIL='$v_email', AVAILABLE_QTY='$avalableqty',NEED_BY='$needbydt' ,Status='Open',PO_QTY='$poqty',QTY_RECEIVED='$qty_received' where PO_NUMBER='$currentpo'";
	 $res512=sqlsrv_query($connection,$qry1111);        
}
        else{
            $qry="INSERT INTO po_details
            (PO_NUMBER,LINE_ID,ITEM_NAME ,V_ID,CONTACT_PERSON,SHIP_TO
            ,BILL_TO,PO_QTY ,AVAILABLE_QTY,CREATION_DATE,NEED_BY,SITENAME
            ,CREATED_BY,UOM,LINE_NUM,QTY_RECEIVED,AP_SEGMENT1,VENDOR_SITE_CODE
            ,SUUPLIER_NAME,ORGANIZATION_ID,VENDOR_SITE_ID,PO_HEADER_ID
            ,LINE_LOCATION_ID,PO_SEGMENT1,IS_MAPPED,VENDOR_NAME,REC_QTY,VENDOR_SAP_ID,VENDOR_EMAIL,Status,unit_price) VALUES
            ($currentpo,$lineid,'$itemname',$v_id,'$contact_person'
            ,'$ship_to','$billto',$poqty,$avalableqty,'$creationdate'
            ,'$needbydt','$sitename',$createdby,'$uom','$linenum'
            ,$qty_received,'$apsegement','$vendorsitecode','mm'
            ,$organizationid,$vendorsiteid,$poheaderid,$linelocid,'$posegment','$ismapped','$contact_person',null,'$vendorid','$v_email','Open','$unitprice')";

        
            $res5=sqlsrv_query($connection,$qry);
            if($res5){


                //$response['status']=1;
                //$response['message']="import successfully";
            }
            else{

             

            
               // $response['status']=0;
                //$response['message']="import fail";
            }
        }
        $res4=sqlsrv_query($connection,"select * from LocationMaster where Sitename='$sitename'", array(), array( "Scrollable" => 'static' ));
        $registeredloc=sqlsrv_num_rows($res4);

            if($registeredloc > 0){
            $var="one";
           
            }
            else{
              $qry="INSERT into LocationMaster (Sitename,IsActive,SiteLocation,OnlineSyncTime)
               values('$sitename','1','$sitename',DATEADD(MINUTE, 330,GETUTCDATE()))";

            echo $sitename;
                $res5=sqlsrv_query($connection,$qry);
            }
        
            
            $res10=sqlsrv_query($connection,"SELECT * from Sap_po where PO_NUM = $currentpo AND AP_SEGMENT1='$apsegement' ", array(), array( "Scrollable" => 'static' ));
            $numofoldpo1=sqlsrv_num_rows($res10);
            if($numofoldpo1 >0 ){
              $res100=sqlsrv_query($connection,"UPDATE Sap_PO set unit_price='$unitprice',PO_Status='Open',NEED_BY_DATE='$needbydt',PO_Status='Open',creation_date='$creationdate',vendor_Email='$v_email', QUANTITY_Avalable='$avalableqty',QUANTITY_RECEIVED='$qty_received' ,vendor_Email='$v_email' where PO_NUM='$currentpo'"); 
            }
            else{
                $qry121="INSERT INTO Sap_PO
                (PO_NUM
                ,PO_SEGMENT1
                ,LINE_NUM
                ,organization_name
                ,SEGMENT1
                ,UNIT_MEAS_LOOKUP_CODE
                ,QUANTITY
                ,QUANTITY_Avalable
                ,QUANTITY_RECEIVED
                ,NEED_BY_DATE
                ,creation_date
                ,created_by
                ,AP_SEGMENT1
                ,Ship_location_code
                ,Bill_location_code
                ,vendor_SITE_CODE
                ,supplierName
                ,organization_id
                ,vendor_name
                ,vendor_id
                ,vendor_site_id
                ,po_header_id
                ,po_line_id
                ,line_location_id
                ,vendor_Email
                ,PO_Status
                ,unit_price)
          VALUES($currentpo,null,$linenum,'$ship_to','$itemname','$uom',$poqty,$avalableqty,'$qty_received','$needbydt','$creationdate',$createdby,
          $apsegement,'$ship_to','$ship_to','$vendorsitecode','$suppliername','$organizationid','$contact_person','$vendorid','$linelocid','$poheaderid','$lineid','$linenum','$v_email','Open','$unitprice')";
          $res50=sqlsrv_query($connection,$qry121);


            }
            $v_id=1;
            $ismapped='false';


    //     // echo json_encode($row);
        
            // echo json_encode($row);
}

$qty="UPDATE Sap_PO set PO_Status='Close' where  Organization_name='$locationname'  and  PO_NUM not in(......HERE......) and NEED_BY_DATE >getdate()-30 ";
$qty11="UPDATE po_details set Status='Close' where SHIP_TO='$locationname' and PO_NUMBER not in(......HERE......) and NEED_BY>GETDATE()-30";
$qty1=str_replace("......HERE......",$val1,$qty);
$qty12=str_replace("......HERE......",$val1,$qty11);
$up=sqlsrv_query($connection,$qty1);
$up1=sqlsrv_query($connection,$qty12);
  //echo json_encode($response);  

oci_free_statement($s);
oci_close($conn);

?>

