
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($current_page != 'my_profile') { ?>
    <script type="text/javascript" src="../js/index.js"></script>
<?php } else { ?>
    <script type="text/javascript" src="./js/index.js"></script>
    <script type="text/javascript" src="./js/my_profile.js"></script>
<?php } ?>

<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<?php if ($role == 'admin') { ?>

    <?php if ($current_page == 'admin_dashboard') { ?>
        <script type="text/javascript" src="../js/admin/admin_dashboard.js"></script>
    <?php } ?>

    <?php if ($current_page == 'sales') { ?>
        <script type="text/javascript" src="../js/admin/sales.js"></script>
    <?php } ?>

    <?php if ($current_page == 'employees') { ?>
        <script type="text/javascript" src="../js/admin/employees.js"></script>
    <?php } ?>

    <?php if ($current_page == 'reports') { ?>
        <script type="text/javascript" src="../js/admin/reports.js"></script>
    <?php } ?>

    <?php if ($current_page == 'notice_board') { ?>
        <script type="text/javascript" src="../js/admin/notice_board.js"></script>
    <?php } ?>

<?php } elseif ($role == 'employee') { ?>

    <?php if ($current_page == 'employee_dashboard') { ?>
        <script type="text/javascript" src="../js/admin/admin_dashboard.js"></script>
    <?php } ?>

    <?php if ($current_page == 'clients') { ?>
        <script src="../js/employee/client.js"></script>
    <?php } ?>

    <?php if ($current_page == 'projects') { ?>
        <script src="../js/employee/projects.js"></script>
    <?php } ?>

<?php } ?>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined') {
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 4000);
        }
    });
</script>