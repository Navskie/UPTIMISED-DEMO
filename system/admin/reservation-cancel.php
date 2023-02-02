<?php include 'include/header.php' ?>
<?php include 'include/navbar.php' ?>
<?php include 'include/topbar.php' ?>

<div class="page-wrapper">
<!-- Page-header start -->
<div class="page-header">
    <div class="page-header-title">
        <h4>Reservation Page</h4>
        <span>Manage Pending & On Process Status</span>
    </div>
    <div class="page-header-breadcrumb">
        <ul class="breadcrumb-title">
            <li class="breadcrumb-item">Hidden Haven Resort
            </li>
            <li class="breadcrumb-item">Reservation Page
            </li>
        </ul>
    </div>
</div>
<!-- Page-header end -->
<!-- Page-body start -->
<div class="page-body">
    <div class="row">
        <div class="col-sm-12">
            <!-- Language - Comma Decimal Place table start -->
            <div class="card">
                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="lang-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                  <th class="text-center">#</th>
                                  <th class="text-center">Book Reference</th>
                                  <th class="text-center">Book Date</th>
                                  <th class="text-center">Reservation Date</th>
                                  <th class="text-center">Amount</th>
                                  <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                                $booking_qry = "SELECT * FROM haven_details INNER JOIN haven_booking ON details_ref = booking_ref WHERE booking_status = 'Canceled' ORDER BY booking_date DESC";
                                $booking = mysqli_query($connect, $booking_qry);
                                $number = 1;
                                foreach ($booking as $data) {
                              ?>
                                <tr>
                                    <td class="text-center"><?php echo $number ?></td>
                                    <td class="text-center"><?php echo $data['details_ref'] ?></td>
                                    <td class="text-center"><?php echo $data['booking_date'] ?></td>
                                    <td class="text-center"><?php echo $data['booking_start'] ?> - <?php echo $data['booking_end'] ?></td>
                                    <td class="text-center"><?php echo $data['details_amount'] ?></td>
                                    <td class="text-center"><?php echo $data['booking_status'] ?></td>
                                </tr>
                              <?php
                                include 'backend/reserve-on-modal.php';
                                include 'backend/success-modal.php';
                                $number++;
                                }
                              ?>
                            </tbody>
                        </table>
                    </div>
                </div>                  
            </div>
            <!-- Language - Comma Decimal Place table end -->
        </div>
    </div>
</div>
<!-- Page-body end -->
<?php include 'include/footer.php' ?>