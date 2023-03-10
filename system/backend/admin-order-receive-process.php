<?php
    include '../dbms/conn.php';

    session_start();
    $uid = $_SESSION['code'];
    // echo '<br>';
    $date = date('m-d-Y');

    $id = $_GET['id'];

    if (isset($_POST['receive'])) {

        // // Stockist Refund
        $percentage_sql = "SELECT p_amount FROM stockist_percentage WHERE p_code = '$uid' AND p_poid = '$id'";
        $percentage_qry = mysqli_query($connect, $percentage_sql);
        $percentage = mysqli_fetch_array($percentage_qry);

        $p_amount = $percentage['p_amount'];

        $refund_sql = "SELECT SUM(e_refund) AS e_refs FROM stockist_earning WHERE e_id = '$uid' AND e_poid = '$id'";
        $refund_qry = mysqli_query($connect, $refund_sql);
        $refund = mysqli_fetch_array($refund_qry);

        $r_amount = $refund['e_refs'];

        $deduct = $p_amount + $r_amount;

        $get_wallet_qry = mysqli_query($connect, "SELECT w_earning FROM stockist_wallet WHERE w_id = '$uid'");
        $get_wallet = mysqli_fetch_array($get_wallet_qry);

        $balance = $get_wallet['w_earning'];

        $remain_balance = $balance - $deduct;

        $update_wallet_sql = "UPDATE stockist_wallet SET w_earning = '$remain_balance' WHERE w_id = '$uid'";
        $update_wallet_qry = mysqli_query($connect, $update_wallet_sql);

        $delete_percentage = mysqli_query($connect, "DELETE FROM stockist_percentage WHERE p_poid = '$id'");

        $delete_earning = mysqli_query($connect, "DELETE FROM stockist_earning WHERE e_poid = '$id'");
        // End Refund

        $get_order_list = "SELECT * FROM upti_order_list INNER JOIN upti_transaction ON ol_poid = trans_poid WHERE ol_poid = '$id'";
        $get_order_list_sql = mysqli_query($connect, $get_order_list);

        while ($get_order_list_fetch = mysqli_fetch_array($get_order_list_sql)) {
            $code = $get_order_list_fetch['ol_code'];
            $qty = $get_order_list_fetch['ol_qty'];
            $country = $get_order_list_fetch['ol_country'];
            $date_order = $get_order_list_fetch['ol_date'];
            $state = $get_order_list_fetch['trans_state'];

            if ($state != 'ALL' && $country != 'CANADA') {
              $state = 'ALL';
            } else {
              if ($state != 'ALBERTA') {
                $state = 'ALL';
              } else {
                $state = 'ALBERTA';
              }
            }

            // echo $state;

            $check_package = "SELECT * FROM upti_package WHERE package_code = '$code'";
            $check_package_qry = mysqli_query($connect, $check_package);
            $pack_fetch = mysqli_fetch_array($check_package_qry);
            $check_package_sql = mysqli_num_rows($check_package_qry);

            // echo $c1 = $pack_fetch['package_one_code'];
            
            // if ($date_order > '07-03-2022') {
                if ($check_package_sql == 0) {
                  // echo '<br>';
                  $get_main_code = mysqli_query($connect, "SELECT * FROM upti_code WHERE code_name = '$code'");
                  $get_main_code_fetch = mysqli_fetch_array($get_main_code);

                  $code_main = $get_main_code_fetch['code_main'];

                  if ($code_main == '') {
                    $code = $code;
                  } else {
                    $code = $code_main;
                  }

                  $get_remain_sql = "SELECT * FROM stockist_inventory WHERE si_item_code = '$code' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry = mysqli_query($connect, $get_remain_sql);
                  $get_remain_fetch = mysqli_fetch_array($get_remain_qry);
                  
                  $remain_stock_code = $get_remain_fetch['si_item_code'];
                  $remain_stock_qty = $get_remain_fetch['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks = $remain_stock_qty + $qty;
                  
                  $receive_sql = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks' WHERE si_item_code = '$code' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $receive_qry = mysqli_query($connect, $receive_sql);

                  $re_sql = "INSERT INTO stockist_return (re_poid, re_code, re_qty, re_date, re_status) VALUES ('$id', '$remain_stock_code', '$qty', '$date', 'Received')";
                  $re_qry = mysqli_query($connect, $re_sql);
                    // }
                  $change_status = "UPDATE upti_transaction SET trans_stockist = 'Received' WHERE trans_poid = '$id'";
                  $change_status_qry = mysqli_query($connect, $change_status);
                } else {

                  $q1 = $pack_fetch['package_one_qty'] * $qty;
                  $c1 = $pack_fetch['package_one_code'];

                  $q2 = $pack_fetch['package_two_qty'] * $qty;
                  $c2 = $pack_fetch['package_two_code'];

                  $q3 = $pack_fetch['package_three_qty'] * $qty;
                  $c3 = $pack_fetch['package_three_code'];

                  $q4 = $pack_fetch['package_four_qty'] * $qty;
                  $c4 = $pack_fetch['package_four_code'];

                  $q5 = $pack_fetch['package_five_qty'] * $qty;
                  $c5 = $pack_fetch['package_five_code'];

                  $q6 = $pack_fetch['package_six_qty'] * $qty;
                  $c6 = $pack_fetch['package_six_code'];

                  // 1

                  $get_remain_sql1 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c1' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry1 = mysqli_query($connect, $get_remain_sql1);
                  $get_remain_fetch1 = mysqli_fetch_array($get_remain_qry1);
                  
                  $remain_stock_code = $get_remain_fetch1['si_item_code'];
                  $remain_stock_qty = $get_remain_fetch1['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks1 = $remain_stock_qty + $q1;
                  
                  $receive_sql1 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks1' WHERE si_item_code = '$c1' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $receive_qry1 = mysqli_query($connect, $receive_sql1);

                  // 2

                  $get_remain_sql2 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c2' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry2 = mysqli_query($connect, $get_remain_sql2);
                  $get_remain_fetch2 = mysqli_fetch_array($get_remain_qry2);
                  
                  $remain_stock_qty2 = $get_remain_fetch2['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks2 = $remain_stock_qty2 + $q2;
                  
                  $receive_sql2 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks2' WHERE si_item_code = '$c2' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $receive_qry2 = mysqli_query($connect, $receive_sql2);

                  // // 3

                  $get_remain_sql3 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c3' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry3 = mysqli_query($connect, $get_remain_sql3);
                  $get_remain_fetch3 = mysqli_fetch_array($get_remain_qry3);
                  
                  $remain_stock_qty3 = $get_remain_fetch3['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks3 = $remain_stock_qty3 + $q3;
                  
                  $receive_sql3 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks3' WHERE si_item_code = '$c3' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $receive_qry3 = mysqli_query($connect, $receive_sql3);

                  // 4

                  $get_remain_sql4 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c4' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry4 = mysqli_query($connect, $get_remain_sql4);
                  $get_remain_fetch4 = mysqli_fetch_array($get_remain_qry4);
                  
                  $remain_stock_qty4 = $get_remain_fetch4['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks4 = $remain_stock_qty4 + $q4;
                  
                  $receive_sql4 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks4' WHERE si_item_code = '$c4' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $receive_qry4 = mysqli_query($connect, $receive_sql4);

                  // 5

                  $get_remain_sql5 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c5' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry5 = mysqli_query($connect, $get_remain_sql5);
                  $get_remain_fetch5 = mysqli_fetch_array($get_remain_qry5);
                  
                  $remain_stock_qty5 = $get_remain_fetch5['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks5 = $remain_stock_qty5 + $q5;
                  
                  $receive_sql5 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks5' WHERE si_item_code = '$c5' AND si_item_country = '$country'";
                  $receive_qry5 = mysqli_query($connect, $receive_sql5);

                  // 5

                  $get_remain_sql6 = "SELECT * FROM stockist_inventory WHERE si_item_code = '$c6' AND si_item_country = '$country' AND si_item_state = '$state'";
                  $get_remain_qry6 = mysqli_query($connect, $get_remain_sql6);
                  $get_remain_fetch6 = mysqli_fetch_array($get_remain_qry6);
                  
                  $remain_stock_qty6 = $get_remain_fetch6['si_item_stock'];
                  // echo '<br>';
                  
                  $new_stocks6 = $remain_stock_qty6 + $q6;
                  
                  $receive_sql6 = "UPDATE stockist_inventory SET si_item_stock = '$new_stocks6' WHERE si_item_code = '$c6' AND si_item_country = '$country'";
                  $receive_qry6 = mysqli_query($connect, $receive_sql6);

                  $re_sql = "INSERT INTO stockist_return (re_poid, re_code, re_qty, re_date, re_status) VALUES ('$id', '$remain_stock_code', '$qty', '$date', 'Received')";
                  $re_qry = mysqli_query($connect, $re_sql);
                    // }
                  $change_status = "UPDATE upti_transaction SET trans_stockist = 'Received' WHERE trans_poid = '$id'";
                  $change_status_qry = mysqli_query($connect, $change_status);
                }
            // }
        }
    
        if ($_SESSION['role'] != 'LOGISTIC') {
            echo "<script>alert('Transfered Successfully');window.location.href = '../incoming-rts-order.php';</script>";
        } else {
            echo "<script>alert('Transfered Successfully');window.location.href = '../ph-rts.php';</script>";
        }
    }
?>