<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/product_manage/upload_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

    page_header('Upload');

    //Check whether the user has logged in
    if (check_login()) {
        //Call the functino for displaying the upload form
        upload_form();
    } else {
        //If the user has not logged in, it will prompt a message box and redirect the user to the login page.
        response_message2rediect("Please login", "./user/login.php");
    }
        page_footer();
?>