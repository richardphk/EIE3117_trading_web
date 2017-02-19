<?php
	
	include_once('../page_gen.php');
	include_once('upload_fns.php');
	
	page_header('Upload');
	
	echo $_FILES['product_image']['name'] . '<br/>';
	echo $_FILES['product_image']['type'] . '<br/>';
	echo $_FILES['product_image']['size'] . '<br/>';
	echo $_FILES['product_image']['tmp_name'] . '<br/>';
	
	$upfile = '';
	
	if (isset($_FILES['product_image'])) {
		if ($_FILES['product_image']['error'] > 0) {
			echo 'Image upload problem';
			switch ($_FILES['product_image']) {
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
			} exit;
		}
		

		$upfile = 'uploads/' . $_FILES['product_image']['name'];
		echo $upfile . '<br />';
		if (is_uploaded_file($_FILES['product_image']['tmp_name'])) {
			echo 'Problem: Could not move file to destination directory'; 
			exit;
		} else {
			echo 'Problem: Possible file upload attack. Filename: '; 
			echo $_FILES['product_image']['name']; 
			exit;
		}
		
		$contents = file_get_contents($upfile);
		$contents = strip_tags($contents); 
		file_put_contents($_FILES['userfile']['name'], $contents);


		echo '<p>Preview of uploaded file contents:<br/><hr/>'; 
		echo nl2br($contents); 
		echo '<br/><hr/>';


	}
	
	
	echo $upfile . '<br />';
	
	$product_id = 'BBBB';
	$product_name = $_POST['product_name']; //string
	$product_price = $_POST['product_price']; //price
	$product_quantity = $_POST['product_quantity']; //dropdownlist
	$product_type = $_POST['product_type']; //dropdownlist
	$date = date('Y-m-d'); //date
	$product_creator = 'U00001';//$_SESSION['login_user']; //userid
	$product_desc = $_POST['product_desc']; //String
	
	echo insert_goods($product_id, $product_name, $product_price, $product_quantity, $upfile, $product_type, $date, $product_creator, $product_desc);
	
	
	page_footer();
	
?>