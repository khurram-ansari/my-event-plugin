<?php
$getParam=isset($_REQUEST['param'])?$_REQUEST['param']:'';
if(!empty($getParam)){
    if ($getParam=="get_message"){
        echo json_encode(array(
            "name"=>"KSA",
            "author"=>"Khurram"
        ));
        die;
    }
    if($getParam=="add_event_data"){
        echo json_encode($_REQUEST);
        die;
    }
}