<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/refund/refund_fns.php');
    add_refund($_POST['sid'], $_POST['pid'], $_POST['oid']);
    echo 'OK';
?>
