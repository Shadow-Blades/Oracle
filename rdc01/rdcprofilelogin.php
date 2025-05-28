<?php 
include "config.php";
	  $response=array();
    $locationname="";
   

      if($_REQUEST['uname'] != null && $_REQUEST['upass'] != null){
        $umail=$_REQUEST['uname'];
        $pass=$_REQUEST['upass'];

        $sql1234565789=sqlsrv_query($connection,"UPDATE cu_users set IsActive = 0 where id in (select cu_users.id from cu_users 
        left join cu_statuses on cu_statuses.collector_id=cu_users.id 
        where cu_statuses.actiontime < DATEADD(MINUTE, 330,GETUTCDATE())-90  and cu_users.role_id not in(1,2,3,5,6,7)
		and cu_users.IsActive!=0 and cu_users.id not in (select collector_id as id from cu_statuses where complate!=1 and denied!=1))" , array(), array( "Scrollable" => 'static' ));

      $sql123456=sqlsrv_query($connection,"UPDATE   cu_statuses  set pick=0 ,collected=0,complate=0,denied=1 where order_id in (select cu_orders.id from cu_orders 
      left join cu_statuses on cu_statuses.order_id=cu_orders.id 
      where cu_statuses.actiontime < getdate()-5  and cu_statuses.complate!=1 and cu_statuses.denied!=1 )", array(), array( "Scrollable" => 'static' ));

        $sql=sqlsrv_query($connection," SELECT rdcsupport.user_fullname as 'name',rdcsupport.id as 'userid',rdcsupport.user_email as 'useremail',rdcsupport.user_mobile as 'usermonbile',
        lmc.id as 'locationid',lmc.Sitename as'sitename',cu_userroles.role_name as'role'
        from cu_users as rdcsupport  inner join LocationMaster as lmc on lmc.id=rdcsupport.location_id
        left join cu_userroles on cu_userroles.id=rdcsupport.role_id where
        role_name != 'DRIVER' AND role_name != 'VENDOR' and  role_name != 'DRIVER' 
         and  role_name != 'USER' and  role_name != 'COLLECTOR' AND rdcsupport.user_email='$umail' and user_password='$pass'", array(), array( "Scrollable" => 'static' ));
		$num=sqlsrv_num_rows($sql);
        if($num >0){
           
			while($data= sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC)){
                $data["status"]='1';
                $locationname=$data["sitename"];
                $response=$data;
                $sql123 = sqlsrv_query($connection, "UPDATE cu_statuses set complate=1 
                where order_id in ( select cu_orders.id as  'order_id' from  TransactionData 
                inner join cu_orders on cu_orders.id=TransactionData.Cu_orderID 
                left join cu_statuses on cu_statuses.order_id=cu_orders.id
                where TransactionData.Status='Complete' and
                cu_statuses.complate !=1)", array(), array("Scrollable" => 'static'));
        }
 
      }
      echo json_encode($response);
    sqlsrv_close($connection);
      }

?>


<?php
// include "newconfig.php";

?>