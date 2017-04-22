<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');

		page_header("Page 88183b946cc5f0e8c96b2e66e1c74a7e");

		if(isset($_SESSION['login_user_id']) && isset($_SESSION['login_user_pw'])){
			$login_user_id = $_SESSION['login_user_id'];
			$login_user_pw = $_SESSION['login_user_pw'];
			$db_conn = db_connect('root','root');
			$result_credit = $db_conn->prepare('SELECT * FROM Tweb_User_Credit WHERE Tweb_User_ID = :id;');

			$result_credit->bindValue(':id', $login_user_id);
			$result_credit->execute();
			$rec_credit = $result_credit->fetchAll(PDO::FETCH_ASSOC);

			if(empty($rec_credit)){
				$login_user_wallet_balance = 'Unknown: need to create wallet first';
			} else{
				$wallet = init_wallet($login_user_id, $login_user_pw, $client);
				$balance = wallet_balance($wallet);
				$login_user_wallet_balance = $balance[0];
			#	print 'wallet_balance:'.$login_user_wallet_balance;
			}
				$_SESSION['login_user_wallet_balance'] = $login_user_wallet_balance;
			}

?>
	<style>
		#main{
			 position: relative;
			 top: 20%;
			 color:lightblue;
			 font-size:50px;
		}
		#content{
			height:100%;
			/*background-image:url(image/home.jpg);*/
			background-position: 50%;
			background-size: cover;
		}
		#search{
			width:500px;
			height:45px;
			font-size:25px;
			color:#555;
		}
	</style>

	<div class="col-lg" id="content">
		<main id="main">
			<center style="font-family: 'Lora', serif;color:#333;">
			9FCdxASeyRHI052bkBSZyVGagcmbxgGdw42e<br/>
			<a href="https://www.3ctrading.tk">Click here to reture to the home page</a>
			</center>
		</main>
	</div>

<?php
	page_footer();
?>