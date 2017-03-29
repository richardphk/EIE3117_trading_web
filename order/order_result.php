<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/order/order_fns.php');

    page_header("Receipt");
    order_table_header($_GET['sid'], $_GET['type']);
    order_table_body($_GET['sid']);
    order_table_footer();

?>