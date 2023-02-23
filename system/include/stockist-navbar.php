<?php
    $myid = $_SESSION['uid'];
    $role = $_SESSION['role'];

    $get_country_sql = "SELECT * FROM upti_users WHERE users_id = '$myid'";
    $get_country_qry = mysqli_query($connect, $get_country_sql);
    $get_country_fetch = mysqli_fetch_array($get_country_qry);

    $employee = $get_country_fetch['users_employee'];
    $codekotowagka = $get_country_fetch['users_code'];

    $get_country_ = mysqli_query($connect, "SELECT * FROM stockist WHERE stockist_code = '$codekotowagka'");
    $fetchkocountry = mysqli_fetch_array($get_country_);

    $bansakonato = $fetchkocountry['stockist_country'];

    if ($role == 'UPTIRESELLER') { 
?>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav"> 
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php
            $notifcode = $_SESSION['code'];
            $notif_sum_sql = "SELECT COUNT(stockist_remarks.remarks_comment) AS NOTIF FROM stockist_remarks
            INNER JOIN
            stockist_request ON stockist_remarks.remarks_reference = stockist_request.req_reference
            WHERE
            stockist_remarks.remarks_code = '$notifcode' AND stockist_remarks.remarks_csr = 'Unread'";
            $notif_sum_qry = mysqli_query($connect, $notif_sum_sql);
            $notif_sum_num = mysqli_num_rows($notif_sum_qry);
            $notif_sum_fetch = mysqli_fetch_array($notif_sum_qry);

            $notif_sum_sql1 = "SELECT COUNT(*) AS NOTIF FROM upti_remarks
            INNER JOIN
            upti_transaction ON remark_poid = trans_poid
            WHERE
            trans_country = '$bansakonato' AND remark_csr = 'Unread' AND remark_code = 'Stockist'";
            $notif_sum_qry1 = mysqli_query($connect, $notif_sum_sql1);
            $notif_sum_num1 = mysqli_num_rows($notif_sum_qry1);
            $notif_sum_fetch1 = mysqli_fetch_array($notif_sum_qry1);
            // echo $notif_sum_fetch['NOTIF'];
            $note = $notif_sum_fetch['NOTIF'] + $notif_sum_fetch1['NOTIF'];

            if ($note == 0) {
          ?>
            <span class="badge badge-danger navbar-badge"></span>
          <?php } else { ?>
            <span class="badge badge-danger navbar-badge"><?php echo $note ?></span>
          <?php } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Notifications</span>
          <div class="dropdown-divider"></div>
          <?php
            
            $get_notif = "
            SELECT DISTINCT remarks_reference, stockist_remarks.id FROM stockist_remarks
            INNER JOIN
            stockist_request ON remarks_reference = req_reference
            WHERE
            remarks_code = '$notifcode' AND remarks_csr = 'Unread'
            UNION
            SELECT trans_poid, upti_remarks.id  FROM upti_remarks
            INNER JOIN
            upti_transaction ON remark_poid = trans_poid
            WHERE
            trans_country = '$bansakonato' AND remark_csr = 'Unread' AND remark_code = 'Stockist'";
            $get_notif_sql = mysqli_query($connect, $get_notif);
            while ($row = mysqli_fetch_array($get_notif_sql)) {
              $newcode = $row['remarks_reference'];

              $getpoidtest = mysqli_query($connect, "SELECT * FROM upti_transaction WHERE trans_poid = '$newcode'");
              $kulot_fetch = mysqli_fetch_array($getpoidtest);

              if (mysqli_num_rows($getpoidtest) > 0) {
                $idnikulot = $kulot_fetch['id'];
          ?>
          <a href="./read-info.php?id=<?php echo $idnikulot; ?>" class="dropdown-item">
            <i class="fas fa-comment mr-2 text-primary"></i> <b><?php echo $row['remarks_reference']; ?></b>
            <span class="float-right text-muted text-sm">Remarks</span>
          </a>
          <?php
              } else {
          ?> 
          <a href="./read-info.php?id=<?php echo $row['id']; ?>" class="dropdown-item">
            <i class="fas fa-comment mr-2 text-primary"></i> <b><?php echo $row['remarks_reference']; ?></b>
            <span class="float-right text-muted text-sm">Remarks</span>
          </a>
          <?php } } ?>
        </div>
      </li>
      <?php
        if ($role == 'UPTIMAIN' || $role == 'UPTIOSR' || $role == 'SPECIAL' || $role == 'IT/Sr Programmer' || $role == 'BRANCH' || $role == 'UPTIACCOUNTING') {
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <img class="img-responsive" src="./images/icon/274966106_640652090373107_513539919171817442_n.ico" width="30" style="border-radius: 25px; border: 1px solid #aeaeae;"/>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="main-information.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Information
            <!-- <span class="float-right  text-sm">3 mins</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } elseif ($role == 'UPTICREATIVES' || $role == 'UPTICSR') { ?>
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <img class="img-responsive" src="./images/icon/274966106_640652090373107_513539919171817442_n.ico" width="30" style="border-radius: 25px; border: 1px solid #aeaeae;"/>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } elseif ($role == 'UPTIRESELLER') { ?>
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <?php if ($get_info_fetch['users_img'] != '') { ?>
          <img class="img-responsive" src="./images/profile/<?php echo $get_info_fetch['users_img'] ?>" width="30" height="30" style="border-radius: 50%; border: 2px solid #aeaeae; padding: 1px"/>
          <?php } else { ?>
            <img class="img-responsive" src="./images/profile/default.png" width="30" height="30" style="border-radius: 50%; border: 2px solid #aeaeae; padding: 1px"/>
          <?php } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="information.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Information
            <!-- <span class="float-right  text-sm">3 mins</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } ?>
    </ul>
  </nav>
  <!-- /.navbar -->
  <?php } else { ?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav"> 
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php
            $notifcode = $_SESSION['code'];
            $notif_sum_sql = "SELECT COUNT(stockist_remarks.remarks_comment) AS NOTIF FROM stockist_remarks
            INNER JOIN
            stockist_request ON stockist_remarks.remarks_reference = stockist_request.req_reference
            WHERE stockist_remarks.remarks_stockist = 'Unread'";
            $notif_sum_qry = mysqli_query($connect, $notif_sum_sql);
            $notif_sum_num = mysqli_num_rows($notif_sum_qry);
            $notif_sum_fetch = mysqli_fetch_array($notif_sum_qry);

            $note = $notif_sum_fetch['NOTIF'];

            if ($note == 0) {
          ?>
            <span class="badge badge-danger navbar-badge"></span>
          <?php } else { ?>
            <span class="badge badge-danger navbar-badge"><?php echo $note ?></span>
          <?php } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Notifications</span>
          <div class="dropdown-divider"></div>
          <?php
            
            $get_notif = "SELECT DISTINCT remarks_reference, stockist_remarks.id FROM stockist_remarks
            INNER JOIN
            stockist_request ON stockist_remarks.remarks_reference = stockist_request.req_reference
            WHERE stockist_remarks.remarks_stockist = 'Unread'";
            $get_notif_sql = mysqli_query($connect, $get_notif);
            while ($row = mysqli_fetch_array($get_notif_sql)) {
          ?>
          <a href="./read-info.php?id=<?php echo $row['id']; ?>" class="dropdown-item">
            <i class="fas fa-comment mr-2 text-primary"></i> <b><?php echo $row['remarks_reference']; ?></b>
            <span class="float-right text-muted text-sm">New Remarks</span>
          </a>
          <?php } ?>
        </div>
      </li>
      <?php
        if ($role == 'UPTIMAIN' || $role == 'UPTIOSR' || $role == 'SPECIAL' || $role == 'IT/Sr Programmer' || $role == 'BRANCH' || $role == 'UPTIACCOUNTING') {
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <img class="img-responsive" src="./images/icon/274966106_640652090373107_513539919171817442_n.ico" width="30" style="border-radius: 25px; border: 1px solid #aeaeae;"/>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="main-information.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Information
            <!-- <span class="float-right  text-sm">3 mins</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } elseif ($role == 'UPTICREATIVES' || $role == 'UPTICSR' || $role == 'WEBSITE') { ?>
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <img class="img-responsive" src="./images/icon/274966106_640652090373107_513539919171817442_n.ico" width="30" style="border-radius: 25px; border: 1px solid #aeaeae;"/>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } elseif ($role == 'UPTIRESELLER') { ?>
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php echo $get_info_fetch['users_name'] ?>&nbsp;&nbsp;
          <?php if ($get_info_fetch['users_img'] != '') { ?>
          <img class="img-responsive" src="./images/profile/<?php echo $get_info_fetch['users_img'] ?>" width="30" height="30" style="border-radius: 50%; border: 2px solid #aeaeae; padding: 1px"/>
          <?php } else { ?>
            <img class="img-responsive" src="./images/profile/default.png" width="30" height="30" style="border-radius: 50%; border: 2px solid #aeaeae; padding: 1px"/>
          <?php } ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Account Settings</span>
          <div class="dropdown-divider"></div>
          <a href="information.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Information
            <!-- <span class="float-right  text-sm">3 mins</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="./logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
            <!-- <span class="float-right text-sm">Logout</span> -->
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <?php } ?>
    </ul>
  </nav>
  <?php } ?>