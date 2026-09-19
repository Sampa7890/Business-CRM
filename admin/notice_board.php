<?php
ob_start();
$page_title = "Notice Board";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php'; 
include '../check_company.php';

$company_id = $_SESSION['company_id'];
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $flashMessage = '';
    $flashType = '';

    if ($action === 'save') {
        $id = isset($_POST['notice_id']) ? (int) $_POST['notice_id'] : 0;
        $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
        $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
        $created_by = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

        $company_id = 0;
        if ($created_by > 0) {
            $user_result = mysqli_query($conn, "SELECT company_id FROM users WHERE id = $created_by LIMIT 1");
            if ($user_result && mysqli_num_rows($user_result) > 0) {
                $user_row = mysqli_fetch_assoc($user_result);
                $company_id = (int) $user_row['company_id'];
            }
        }

        if ($title === '' || $description === '') {
            $message = 'Please fill in both Title and Description.';
            $message_type = 'danger';
        } elseif ($id > 0) {
            $sql = "UPDATE notices SET title = '$title', description = '$description' WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                $flashMessage = 'Notice updated successfully.';
                $flashType = 'success';
            } else {
                $message = 'Notice update failed.';
                $message_type = 'danger';
            }
        } else {
            $duplicate_check = mysqli_query($conn, "SELECT id, created_at FROM notices WHERE company_id = $company_id AND title = '$title' AND description = '$description' ORDER BY created_at DESC LIMIT 1");
            $is_duplicate_recent = false;
            if ($duplicate_check && mysqli_num_rows($duplicate_check) > 0) {
                $dup_row = mysqli_fetch_assoc($duplicate_check);
                if (!empty($dup_row['created_at'])) {
                    $created_ts = strtotime($dup_row['created_at']);
                    if (time() - $created_ts < 10) {
                        $is_duplicate_recent = true;
                    }
                }
            }

            if ($is_duplicate_recent) {
                $flashMessage = 'Duplicate notice detected; not saved.';
                $flashType = 'warning';
            } else {
                $sql = "INSERT INTO notices (company_id, title, description, created_by) VALUES ($company_id, '$title', '$description', $created_by)";
                if (mysqli_query($conn, $sql)) {
                    $flashMessage = 'Notice published successfully.';
                    $flashType = 'success';
                } else {
                    $message = 'Notice publish failed.';
                    $message_type = 'danger';
                }
            }
        }
    }

    if ($action === 'delete') {
        $id = isset($_POST['notice_id']) ? (int) $_POST['notice_id'] : 0;
        if ($id > 0) {
            mysqli_query($conn, "DELETE FROM notice_seen WHERE notice_id = $id");
            mysqli_query($conn, "DELETE FROM notices WHERE id = $id");
            $flashMessage = 'Notice deleted successfully.';
            $flashType = 'success';
        }
    }

    if ($flashMessage !== '') {
        $_SESSION['notice_board_message'] = $flashMessage;
        $_SESSION['notice_board_message_type'] = $flashType;
        ob_end_clean();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

if (isset($_SESSION['notice_board_message'])) {
    $message = $_SESSION['notice_board_message'];
    $message_type = $_SESSION['notice_board_message_type'] ?? 'success';
    unset($_SESSION['notice_board_message'], $_SESSION['notice_board_message_type']);
}

$notices = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC, id DESC");
?>

<body>
    <div class="main">

        <div class="main-content" id="content-area">
            <div class="notice-container">
                <div class="notice-header">
                    <h2><i class="fa-solid fa-bell"></i> Notice Board</h2>
                    <button type="button" id="addNoticeBtn" class="btn-add"><i class="fa-solid fa-plus"></i> Add New</button>
                </div>

                <?php if ($message !== '') { ?>
                    <div class="alert alert-<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
                <?php } ?>

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
                                <tr class="no-notice-row">
                                    <td colspan="4">No notice has been published yet.</td>
                                </tr>
                            <?php } else { ?>
                                <?php while ($notice = mysqli_fetch_assoc($notices)) { ?>
                                    <?php
                                    $notice_id = (int) $notice['id'];
                                    $seen_result = mysqli_query($conn, "SELECT u.id AS employee_id, u.name, ns.seen_at
                                        FROM notice_seen ns
                                        INNER JOIN users u ON u.id = ns.employee_id
                                        WHERE ns.notice_id = $notice_id
                                        ORDER BY ns.seen_at DESC");
                                    ?>
                                    <tr>
                                        <td style="font-weight: 600;"><?php echo htmlspecialchars($notice['title']); ?></td>
                                        <td style="white-space: pre-line;"><?php echo htmlspecialchars($notice['description']); ?></td>
                                        <td><?php echo date('j M Y', strtotime($notice['created_at'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn-view" onclick="openSeenModal('seenModal<?php echo $notice_id; ?>')" title="View Seen List">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn-edit"
                                                    data-id="<?php echo $notice_id; ?>"
                                                    data-title="<?php echo htmlspecialchars($notice['title'], ENT_QUOTES); ?>"
                                                    data-description="<?php echo htmlspecialchars($notice['description'], ENT_QUOTES); ?>"
                                                    onclick="openEditModal(this)"
                                                    title="Edit Notice">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="notice_id" value="<?php echo $notice_id; ?>">
                                                    <button type="submit" class="btn-delete" title="Delete Notice">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div id="seenModal<?php echo $notice_id; ?>" class="modal">
                                                <div class="modal-content">
                                                    <span class="close-btn" onclick="closeModal('seenModal<?php echo $notice_id; ?>')">&times;</span>
                                                    <h3>Notice Seen By</h3>
                                                    <div class="seen-container">
                                                        <table class="seen-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Employee ID</th>
                                                                    <th>Name</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!$seen_result || mysqli_num_rows($seen_result) === 0) { ?>
                                                                    <tr>
                                                                        <td colspan="3" style="text-align:center;">No employee has seen this notice yet.</td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <?php while ($seen = mysqli_fetch_assoc($seen_result)) { ?>
                                                                        <tr>
                                                                            <td><?php echo (int) $seen['employee_id']; ?></td>
                                                                            <td><?php echo htmlspecialchars($seen['name']); ?></td>
                                                                            <td>Seen<br><small><?php echo date('j M Y, h:i A', strtotime($seen['seen_at'])); ?></small></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal('addModal')">&times;</span>
                    <h3 id="modalTitle">Add New Notice</h3>

                    <form method="post" id="noticeForm">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" id="noticeIdInput" name="notice_id" value="">

                        <div class="form-group">
                            <label for="noticeTitleInput">Notice Title</label>
                            <input type="text" id="noticeTitleInput" name="title" placeholder="Enter notice title..." required>
                        </div>

                        <div class="form-group">
                            <label for="noticeDescInput">Notice Description</label>
                            <textarea id="noticeDescInput" name="description" placeholder="Write notice description here..." rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn-save" id="btnSave">Save Notice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>   

    <?php include '../layout/footer.php'; ?>
</body>
</html>
