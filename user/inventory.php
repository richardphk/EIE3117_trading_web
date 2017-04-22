<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/user/inventory_fns.php');

    page_header('inventory');
        
    //Check whether the user has logged in
    if (check_login()) {
        //Call the function for checking and displaying the refund requests.
        product_refund($_SESSION['login_user_id']);
            
        //Call the function for displaying the seller's products.
        product_body($_SESSION['login_user_id']);
    } else {
        not_loggedin();
    }
        page_footer();
?>