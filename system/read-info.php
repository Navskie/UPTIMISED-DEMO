<?php
  include 'dbms/conn.php';

  session_start();

  $id = $_GET['id'];

  $getpoidtest = mysqli_query($connect, "SELECT * FROM upti_transaction WHERE id = '$id'");
  $kulot_fetch = mysqli_fetch_array($getpoidtest);

  if (mysqli_num_rows($getpoidtest) > 0) {

    $poid = $kulot_fetch['trans_poid'];

    $update_remarks = "UPDATE upti_remarks SET remark_csr = '' WHERE remark_poid = '$poid'";
    $update_remarks_qry = mysqli_query($connect, $update_remarks);
    
?>
<script>window.location.href = 'poid-list.php?id=<?php echo $id ?>';</script>
<?php
  } else {
    $get_poid = "SELECT * FROM stockist_remarks WHERE id = '$id'";
    $get_poid_qry = mysqli_query($connect, $get_poid);
    $get_poid_fetch = mysqli_fetch_array($get_poid_qry);

    $ref = $get_poid_fetch['remarks_reference'];
    $role = $_SESSION['role'];

    if ($role == 'UPTIACCOUNTING') {
      $update_remarks = "UPDATE stockist_remarks SET remarks_stockist = '' WHERE remarks_reference = '$ref'";
      $update_remarks_qry = mysqli_query($connect, $update_remarks);
    } else {
      $update_remarks = "UPDATE stockist_remarks SET remarks_csr = '' WHERE remarks_reference = '$ref'";
      $update_remarks_qry = mysqli_query($connect, $update_remarks);
    }
?>
<script>window.location.href = 'reference-info.php?poid=<?php echo $ref ?>';</script>
<?php
  }
?>
