<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get_today.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/product_manage/upload_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/redirect_page.php');
	
    page_header('Upload');
    
    //Check whether the user has logged in.
    if (check_login()) {
        $token = $_SESSION['upload_token'];
        unset($_SESSION['upload_token']);
        session_write_close();
        if($token && $_POST['token'] == $token) {
            //Initialize the local varibles for the upload process.
            $upfile = '';
            $product_id = gen_id('Tweb_Product');
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_quantity = $_POST['product_quantity'];
            $product_type = $_POST['product_type'];
            $date = get_today();
            $sale = 0;
            $product_creator = $_SESSION['login_user_id'];
            $product_desc = $_POST['product_desc'];

            //Check whether the user has uploaded an file.
            if (isset($_FILES['product_image']) && !empty( $_FILES["product_image"]["name"] )) {
                //Check whether the uploaded file is an image.
                if ($_FILES["product_image"]["type"] == "image/png" ||
                        $_FILES["product_image"]["type"] == "image/jpg" ||
                        $_FILES["product_image"]["type"] == "image/jpeg" ||
                        $_FILES["product_image"]["type"] == "image/gif") {
                    //Check whether there is an error of the uploaded image.
                    if ($_FILES['product_image']['error'] > 0) {
    ?>
                        <div class="jumbotron">
                            <h1>
    <?php
                            //Show the error.
                            echo 'Image upload problem: ';

                            switch ($_FILES['product_image']['error']) {
                                case 1:  echo 'File exceeded upload_max_filesize';
                                    break;
                                case 2:  echo 'File exceeded max_file_size';
                                    break;
                                case 3:  echo 'File only partially uploaded';
                                    break;
                                case 4:  echo 'No file uploaded';
                                    break;
                                case 6:  echo 'Cannot upload file: No temp directory specified';
                                    break;
                                case 7:  echo 'Upload failed: Cannot write to disk';
                                    break;
                            } 

                            exit;
    ?>
                            </h1>
                        </div>
    <?php
                    }
                } else {
                    //If the uploaded file is not an image, it returns an error message and redirect the user to the product upload page.
                    response_message2rediect("Your uploaded file is not an image", $_SERVER['DOCUMENT_ROOT'] . '/product_manage/upload_page.php');
                }

                //Move the file to the directory "/product_image/".
                $upfile = 'product_image/' . $product_creator . '_' . $product_id . '_' . $_FILES['product_image']['name'];
                move_uploaded_file($_FILES['product_image']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . '/' . $upfile);

                //Prompt a message if the image cannot be moved to the directory.
                if (is_uploaded_file($_FILES['product_image']['tmp_name'])) {
                    echo 'Problem: Could not move file to destination directory';
                    exit;
                }


            } else {
                //Use the default image as the product image if the user has not uploaded an image.
                $upfile = 'product_image/icon.png';
            }

    ?>
            <div class="jumbotron">
                <h1>
    <?php
                //If all the checking is ok, add the reocrds into the database
                echo insert_goods($product_id, $product_name, $product_price, $product_quantity, $upfile, $product_type, $date, $sale, $product_creator, $product_desc);
    ?>
                </h1>
            </div>
    <?php

        } else {
            ?>

			<div class="jumbotron">
				<h1>Illegal access.</h1>
			</div>

		<?php
        }
    } else {
        //Prompt a message to the user if the user has not logged in.
        not_loggedin();
    }
	
	page_footer();	
?>