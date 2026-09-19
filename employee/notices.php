<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$page_title = "Notice Board";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';

$employee_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$read_id = isset($_GET['read_id']) ? (int) $_GET['read_id'] : 0;
$selected_notice = null;

if ($read_id > 0 && $employee_id > 0) {
    mysqli_query($conn, "INSERT INTO notice_seen (notice_id, employee_id) VALUES ($read_id, $employee_id)
        ON DUPLICATE KEY UPDATE seen_at = CURRENT_TIMESTAMP");

    $selected_result = mysqli_query($conn, "SELECT * FROM notices WHERE id = $read_id LIMIT 1");
    if ($selected_result && mysqli_num_rows($selected_result) > 0) {
        $selected_notice = mysqli_fetch_assoc($selected_result);
    }
}

$company_id = 0;
if ($employee_id > 0) {
    $user_result = mysqli_query($conn, "SELECT company_id FROM users WHERE id = $employee_id LIMIT 1");
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_row = mysqli_fetch_assoc($user_result);
        $company_id = (int) $user_row['company_id'];
    }
}

if ($company_id > 0) {
    $notices = mysqli_query($conn, "SELECT * FROM notices WHERE company_id IN (0, $company_id) ORDER BY created_at DESC, id DESC");
} else {
    $notices = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC, id DESC");
}
?>

<body>
    <div class="main">
        <div class="main-content" id="content-area">
            <link rel="stylesheet" href="../css/employee/employee_notice.css">

            <div class="notice-container">
                <div class="notice-header">
                    <h2><i class="fa-solid fa-bell"></i> Employee Notice Board</h2>
                </div>

                <div class="table-responsive">
                    <table class="notice-table">
                        <thead>
                            <tr>
                                <th>Notice Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$notices || mysqli_num_rows($notices) === 0) { ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 30px; color: #777; font-style: italic;">
                                        No notice has been published yet.
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <?php while ($notice = mysqli_fetch_assoc($notices)) { ?>
                                    <tr>
                                        <td style="font-weight: 600;"><?php echo htmlspecialchars($notice['title']); ?></td>
                                        <td style="white-space: pre-line;"><?php echo htmlspecialchars($notice['description']); ?></td>
                                        <td><?php echo date('j M Y', strtotime($notice['created_at'])); ?></td>
                                        <td>
                                            <a class="btn-view" href="notices.php?read_id=<?php echo (int) $notice['id']; ?>" title="Read Notice">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($selected_notice) { ?>
                <div id="readModal" class="modal" style="display:flex;">
                    <div class="modal-content">
                        <!-- 
                        <h3><?php echo htmlspecialchars($selected_notice['title']); ?></h3>
                        <div class="notice-date-badge">Published on: <?php echo date('j M Y', strtotime($selected_notice['created_at'])); ?></div>
                        <hr class="modal-divider">
                        <div class="notice-desc-body">
                            <p><?php echo nl2br(htmlspecialchars($selected_notice['description'])); ?></p>
                        </div> -->
                        <a class="close-btn" href="notices.php">&times;</a>
                        <p>You have read the notice: <strong><?php echo htmlspecialchars($selected_notice['title']); ?></strong>. </p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include '../layout/footer.php'; ?>
</body>