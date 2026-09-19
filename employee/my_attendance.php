<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Kolkata');

$page_title = "My Attendance";

include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';

$company_id = $_SESSION['company_id'];
$employee_id = $_SESSION['user_id'];

$today = $_GET['date'] ?? date('Y-m-d');

// Employee Details
$employee = mysqli_query($conn, "SELECT * FROM users 
WHERE id = '$employee_id' 
AND company_id = '$company_id'");

$emp = mysqli_fetch_assoc($employee);

// Joining date & month limits
$join_month    = date('Y-m', strtotime($emp['joindate']));
$current_month = date('Y-m');
$selected_month = $_GET['month'] ?? date('Y-m');

// First and last day of selected month
$month_start = $selected_month . '-01';
$month_end   = date('Y-m-t', strtotime($month_start));

// Attendance Records
$attendance = mysqli_query($conn, "
    SELECT * FROM attendance 
    WHERE company_id = '$company_id' 
    AND employee_id = '$employee_id'
    AND date BETWEEN '$month_start' AND '$month_end'
    ORDER BY date DESC
");

// Holidays
$holiday = mysqli_query($conn, "
    SELECT * FROM holiday 
    ORDER BY date ASC
");
?>

<body>

<div class="main">
    <div class="container-fluid py-4">
        <div class="row g-4">

            <!-- ── Attendance Section ── -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fa-solid fa-calendar me-2"></i>My Attendance
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Employee Info -->
                        <div class="d-flex flex-wrap gap-4 mb-4 p-3 bg-light rounded">
                            <div>
                                <h6 class="text-muted d-block">Employee ID</h6>
                                <span class="fw-semibold"><?= 'EMP' . $emp['id'] ?> </span>
                            </div>
                            <div>
                                <h6 class="text-muted d-block">Name</h6>
                                <span class="fw-semibold"><?= $emp['name'] ?></span>
                            </div>
                        </div>

                        <!-- Month Filter -->
                        <form method="GET" class="mb-4">
                            <div class="input-group" style="max-width: 220px;">
                                <span class="input-group-text bg-white">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </span>
                                <input
                                    type="month"
                                    name="month"
                                    class="form-control"
                                    value="<?= $selected_month ?>"
                                    min="<?= $join_month ?>"
                                    max="<?= $current_month ?>"
                                    onchange="this.form.submit()"
                                >
                            </div>
                        </form>

                        <!-- Attendance Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-info">
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if (mysqli_num_rows($attendance) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($attendance)): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($row['date'])) ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'present'): ?>
                                                    <span class="badge bg-success px-3 py-2">Present</span>

                                                <?php elseif ($row['status'] == 'absent'): ?>
                                                    <span class="badge bg-danger px-3 py-2">Absent</span>

                                                <?php elseif ($row['status'] == 'leave'): ?>
                                                    <span class="badge bg-warning text-dark px-3 py-2">Leave</span>

                                                <?php else: ?>
                                                    <span class="badge bg-info text-dark px-3 py-2">Holiday</span>

                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>

                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-inbox fs-4 d-block mb-1"></i>
                                                No Attendance Found
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>

                    </div><!-- /card-body -->
                </div><!-- /card -->
            </div>


            <!-- ── Holiday Section ── -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white py-3">
                        <h4 class="mb-0 fw-semibold">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>Holiday List
                        </h4>
                    </div>

                    <div class="card-body p-0">

                        <?php if (mysqli_num_rows($holiday) > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php while ($row = mysqli_fetch_assoc($holiday)): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        <?= date('d M Y', strtotime($row['date'])) ?>
                                    </span>
                                    <span class="fw-semibold text-success">
                                        <?= $row['holiday_name'] ?>
                                    </span>
                                </li>
                                <?php endwhile; ?>
                            </ul>

                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="fa-solid fa-calendar me-2"></i>No Holidays Added
                            </div>
                        <?php endif; ?>

                    </div><!-- /card-body -->
                </div><!-- /card -->
            </div>

        </div><!-- /row -->
    </div><!-- /container -->
</div><!-- /main -->

</body>