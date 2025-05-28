<?php
 include("config.php");
    $response=array();
  

    if(isset($_REQUEST['pro_name']) ) 
    {
        $name=$_REQUEST['pro_name'];
        //$price=$_REQUEST['pro_price'];
        //$qty=$_REQUEST['pro_qty'];

        $sql=sqlsrv_query($connection,"delete from todos where name='$name'");
        if($sql)
        {
            $response['success']=1;
            $response['message']="success";
        }
        else
        {
            $response['success']=0;
            $response['message']="Error";

        }
        echo json_encode($response);
        sqlsrv_close($connection);
    }
?>