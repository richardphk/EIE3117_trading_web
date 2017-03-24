<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get_today.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/product_manage/upload_fns.php');
	
	page_header('Upload');
	
	if (check_login()) {
		
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

		if (isset($_FILES['product_image']) && !empty( $_FILES["product_image"]["name"] )) {
			if ($_FILES['product_image']['error'] > 0) {
				?>
					<div class="jumbotron">
						<h1>
				<?php
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
				} exit;
				?>
						</h1>
					</div>
				<?php
			}
			

			$upfile = 'product_image/' . $product_creator . '_' . $product_id . '_' . $_FILES['product_image']['name'];
			print $_SERVER['DOCUMENT_ROOT'] . '/' . $upfile. '<br/>';
			print $_FILES['product_image']["tmp_name"].'<br/>';

			if(is_dir($_SERVER['DOCUMENT_ROOT'] . '/product_image/') ){
				print 'yes'.'<br/>';
			} else{
				print 'no'.'<br/>';

			}

			if(is_writable($_SERVER['DOCUMENT_ROOT'] . '/product_image/') ){
				print 'yes'.'<br/>';
			} else{
				print 'no'.'<br/>';

			}

			move_uploaded_file($_FILES['product_image']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . '/' . $upfile);


			if (is_uploaded_file($_FILES['product_image']['tmp_name'])) {
				echo 'Problem: Could not move file to destination directory';
				exit;
			}
			

		} else {
			$upfile = 'product_image/icon.png';
		}
		
		?>
			<div class="jumbotron">
				<h1>
					<?php
						echo insert_goods($product_id, $product_name, $product_price, $product_quantity, $upfile, $product_type, $date, $sale, $product_creator, $product_desc);
					?>
				</h1>
			</div>
		<?php
			
	} else {
		not_loggedin();
	}
	
	page_footer();
	
?>