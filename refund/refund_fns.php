<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/order/order_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/tBTC/tBTC_fns.php');

    //Function for adding a refund record with requesting status to database.
    function add_refund($sid, $pid, $oid) {
        try {
            $rid = gen_id('Tweb_Refund');
            $approve = 0;
            
            $db_conn = db_connect('root','root');
                
            $stmt = $db_conn->prepare('INSERT INTO Tweb_Refund (Tweb_Refund_ID, Tweb_Refund_Sale_Record_ID, Tweb_Refund_Payment_ID, Tweb_Refund_Order_ID, Tweb_Refund_Approve) VALUES (:rid, :sid, :pid, :oid, :approve)');
            $stmt->bindparam(':rid', $rid);
            $stmt->bindparam(':sid', $sid);
            $stmt->bindparam(':pid', $pid);
            $stmt->bindparam(':oid', $oid);
            $stmt->bindparam(':approve', $approve);
            $stmt->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    //Function for getting the refund status of a purchase record.
    function change_refund_status($rid, $approve) {
        try {
            $db_conn = db_connect('root','root');
            
            $stmt = $db_conn->prepare('UPDATE Tweb_Refund SET Tweb_Refund_Approve = "' . $approve . '" WHERE Tweb_Refund_ID = "' . $rid . '";');
            $stmt->bindparam(':rid', $rid);
            $stmt->bindparam(':approve', $approve);
            $stmt->execute();
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    //Function for website credit refund process
    function refund_process($uid, $cid, $pid, $oid, $quantity) {
        try {
            $price = get_price($oid);
            
            $db_conn = db_connect('root','root');
            $stmt = $db_conn->prepare('UPDATE Tweb_User_Credit SET Tweb_User_Credit_Cash = ' . (get_user_credit($uid, 'Tweb_User_Credit_Cash')-$price) .' WHERE Tweb_User_ID = :uid');
            $stmt->bindparam(':uid', $uid);
            $stmt->execute();

            $stmt = $db_conn->prepare('UPDATE Tweb_User_Credit SET Tweb_User_Credit_Cash = ' . (get_user_credit($cid, 'Tweb_User_Credit_Cash')+$price) .' WHERE Tweb_User_ID = :cid');
            $stmt->bindparam(':cid', $cid);
            $stmt->execute();
            
            $stmt = $db_conn->prepare('UPDATE Tweb_Product SET Tweb_Product_Inventory = ' . (get_result($pid, 'Inventory') + $quantity) .' WHERE Tweb_Product_ID = :pid');
            $stmt->bindparam(':pid', $pid);
            $stmt->execute();
            
            $stmt = $db_conn->prepare('UPDATE Tweb_Product SET Tweb_Product_Sale = ' . (get_result($pid, 'Sale') - $quantity) .' WHERE Tweb_Product_ID = :pid');
            $stmt->bindparam(':pid', $pid);
            $stmt->execute();
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    //Function for bitcoin refund process
    function refund_process_bitcoin($uid, $upw, $cid, $pid, $oid, $quantity){
        try {
            $price = get_price($oid);

            $db_conn = db_connect('root','root');
            $stmt = $db_conn->prepare('UPDATE Tweb_Product SET Tweb_Product_Inventory = ' . (get_result($pid, 'Inventory') + $quantity) .' WHERE Tweb_Product_ID = :pid');
            $stmt->bindparam(':pid', $pid);
            $stmt->execute();
            
            $stmt = $db_conn->prepare('UPDATE Tweb_Product SET Tweb_Product_Sale = ' . (get_result($pid, 'Sale') - $quantity) .' WHERE Tweb_Product_ID = :pid');
            $stmt->bindparam(':pid', $pid);
            $stmt->execute();

            global $client;
            $wallet = init_wallet($uid, $upw, $client);
            send_tran($wallet, get_receiver_bitcoin($cid), $price);

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    
    //Function for getting the amount of a puchased product
    function get_price($oid) {
        $db_conn = db_connect('root','root');
    	$result = $db_conn->prepare('SELECT * FROM Tweb_Order WHERE Tweb_Order_ID = "' . $oid . '";');
    		
    	$result->execute();
    	$rec = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rec as $r) {
            return $r['Tweb_Order_Price'];
        }
    }

    //Function for getting the buyer's bitcoin receive address
    function get_receiver_bitcoin($cid) {
        $db_conn = db_connect('root','root');
        $result = $db_conn->prepare('SELECT * FROM Tweb_User_Credit WHERE Tweb_User_ID = "' . $cid . '";');
            
        $result->execute();
        $rec = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rec as $r) {
            return $r['Tweb_User_Bitcon_RevAddress'];
        }
    }

?>

