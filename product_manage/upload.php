<?php
	
	include_once('../page_gen.php');
	include_once('../includes/gen_id.php');
	include_once('../includes/get_today.php');
	include_once('../session/checking.php');
	include_once('upload_fns.php');
	
	page_header('Upload');
	
	if (check_login()) {
		$upfile = '';

		if (isset($_FILES['product_image']) && !empty( $_FILES["product_image"]["name"] )) {

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
			

			$upfile = 'product_image/' . $_FILES['product_image']['name'];
			move_uploaded_file($_FILES['product_image']["tmp_name"], '../' . $upfile);
			
			if (is_uploaded_file($_FILES['product_image']['tmp_name'])) {
				echo 'Problem: Could not move file to destination directory'; 
				exit;
			}
		} else {
			$upfile = 'product_image/icon.png';
		}
		
		
		$product_id = gen_id('Tweb_Product');
		$product_name = $_POST['product_name']; //string
		$product_price = $_POST['product_price']; //price
		$product_quantity = $_POST['product_quantity']; //dropdownlist
		$product_type = $_POST['product_type']; //dropdownlist
		$date = get_today();
		$sale = 0;
		$product_creator = 'U00001';//$_SESSION['login_user']; //userid
		$product_desc = $_POST['product_desc']; //String
		
		?>
			<div class="jumbotron">
				<h1><?php echo insert_goods($product_id, $product_name, $product_price, $product_quantity, $upfile, $product_type, $date, $sale, $product_creator, $product_desc); ?></h1>
			</div>
		<?php
			
	} else {
		not_loggedin();
	}
	
	page_footer();
	
?>