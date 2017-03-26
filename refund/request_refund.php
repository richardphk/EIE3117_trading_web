<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/refund/refund_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
    try {
        add_refund($_POST['sid'], $_POST['pid'], $_POST['oid']);
        response_message2rediect("Request successfully, please wait for your saler for the reply. ", "../user/record.php");
    } catch (Exception $ex) {
        response_message2rediect("Error: " . $ex->getMessage(), "../user/record.php");
    }
    
?>
