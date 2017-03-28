<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/refund/refund_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
    
    session_start();

    if ($_POST['approve'] == 1) {
        try {
            if ($_POST['payment_type'] == 'Bitcoin') {
                refund_process_bitcoin($_SESSION['login_user_id'], $_SESSION['login_user_pw'], $_POST['cid'], $_POST['pid'], $_POST['oid'], $_POST['quantity']);
            } elseif ($_POST['payment_type'] == 'Credit') {
                refund_process($_POST['uid'], $_POST['cid'], $_POST['pid'], $_POST['oid'], $_POST['quantity']);
            }
            
            change_refund_status($_POST['rid'], $_POST['approve']);
            response_message2rediect("Approve for the refund request successfully. ", "../user/inventory.php");
        } catch (Exception $ex) {
                response_message2rediect("Error: " . $ex->getMessage(), "../user/inventory.php");
        }
            
    } elseif ($_POST['approve'] == 2) {
        try {
            change_refund_status($_POST['rid'], $_POST['approve']);
            response_message2rediect("Reject for the refund request successfully. ", "../user/inventory.php");
        } catch (Exception $ex) {
            response_message2rediect("Error: " . $ex->getMessage(), "../user/inventory.php");
        }
        
    }
?>