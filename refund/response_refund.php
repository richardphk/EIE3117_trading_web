<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/refund/refund_fns.php');
    
    if ($_POST['approve'] == 1) {
        change_refund_status($_POST['rid'], $_POST['approve']);
        refund_process($_POST['uid'], $_POST['cid'], $_POST['pid'], $_POST['quantity']);
        echo 'Approve successfully.';
        
    } elseif ($_POST['approve'] == 2) {
        change_refund_status($_POST['rid'], $_POST['approve']);
        echo 'Reject successfully.';
    }
?>