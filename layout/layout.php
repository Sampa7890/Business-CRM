<?php
include __DIR__ . '/../db.php';

if (isset($_POST['select_company_id'])) {
    $comid = intval($_POST['select_company_id']);
}

if (isset($_SESSION['role']) && $_SESSION['role']=='employee' && isset($_SESSION['company_id'])) {
    $comid = $_SESSION['company_id'];
}

if (isset($comid) && !empty($comid)) {
    $_SESSION['company_id'] = $comid;
    $company = mysqli_query($conn, "SELECT * FROM company WHERE company_id = $comid");
    $crow = mysqli_fetch_assoc($company);

    $_SESSION['company_name'] = $crow['company_name'];
}


if (isset($_POST['companysave'])) {
    $name = $_POST['company_name'];
    if ($name != "") {
        $name = mysqli_real_escape_string($conn,$name);
        $check = mysqli_query($conn, "SELECT * FROM company WHERE company_name = '$name'");
        if (mysqli_num_rows($check) == 0)
            mysqli_query($conn, "INSERT INTO company(company_name) VALUES('$name')");
    }
}
if (isset($_GET['del'])) {
    if(!isset($_SESSION['company_id'])){
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $comid = intval($_GET['del']);

    mysqli_query($conn, "DELETE FROM attendance WHERE company_id = $comid");
    mysqli_query($conn, "DELETE FROM sales WHERE company_id = $comid");

    mysqli_query($conn, "DELETE FROM users WHERE company_id = $comid");
    mysqli_query($conn, "DELETE FROM company WHERE company_id = $comid");


    if (
        isset($_SESSION['company_id']) &&
        $_SESSION['company_id'] == $comid
    ) {
        unset($_SESSION['company_id']);
        unset($_SESSION['company_name']);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
$result = mysqli_query($conn, "SELECT * FROM company ORDER BY company_id DESC");
?>



<div class="header">
    <div class="header-right">
        <h5>Welcome <?php echo isset($_SESSION['role']) && $_SESSION['role'] == 'admin' ? 'Admin' : $_SESSION['name']; ?></h5>
        <h6>(Role : <?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Employee'; ?>)</h6>
    </div>
</div>

<div class="sidebar">
    <div class="sidebar-header" id="headername">
        <?php
        if (!empty($_SESSION['company_name'])) {
            echo $_SESSION['company_name'];
        } else {
            echo "BUSINESS CRM";
        }
        ?>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <button id="crmarrow" onclick="companyN()">
                <i class="fas fa-chevron-down"></i></button>
        <?php } ?>
    </div>

    <div class="company-dropdown" id="dropdown" style="display: none;">
        <div class="company-list" id="companylists">
            <?php while ($comrow = mysqli_fetch_assoc($result)) { ?>
                <div class="company-item">
                    <form method="POST" action="">
                    <input type="hidden" name="select_company_id" value="<?php echo $comrow['company_id']; ?>">
                        <button type="submit" class="com-select">
                            <?php echo $comrow['company_name']; ?>
                        </button>
                    </form>

                    <?php 
                    $com_selected = (isset($_SESSION['company_id']) && $_SESSION['company_id'] == $comrow['company_id']);
                    ?>

                    <button type="button" class="com-delete"
                    <?php echo !$com_selected ? 'disabled title = "Please select this company first to delete"' : ''; ?>
                     onclick="deletecompany('<?php echo $comrow['company_id']; ?>',
                    '<?php echo $comrow['company_name']; ?>')">
                        <i class="fa-solid fa-xmark fa-s"></i>
                    </button>
                </div>
            <?php } ?>
        </div>

        <div>
            <button type="button" id="add-com" onclick="newcompany()">+ Add New Company</button>
        </div>
    </div>


    <div class="company-form" id="form" style="display: none;">
        <form method="POST" onsubmit="return Comesave()">
            <h5>Add New Company</h5>
            <input type="text" name="company_name" id="companyname" placeholder="Company Name">

            <span id="errormsg"></span>
            <br />
            <button type="submit" class="save-com" name="companysave">Add</button>
            <button type="button" class="canform" onclick="cancelform()">Cancel</button>
        </form>
    </div>

    <?php
// Automatically detect the project root folder
$base_url = '/' . explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))[0];
?>

<ul class="menu">

        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        ?>

            <li class="<?php echo ($current_page == 'dmin_dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/admin_dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <li class="<?php echo ($current_page == 'employees') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/employees.php">
                    <i class="fas fa-users"></i> Employees
                </a>
            </li>

            <li class="<?php echo ($current_page == 'sales') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/sales.php">
                    <i class="fa-solid fa-bag-shopping"></i> Sales
                </a>
            </li>

            <li class="<?php echo ($current_page == 'attendance') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/attendance.php">
                    <i class="fa-solid fa-clipboard-user"></i> Attendance
                </a>
            </li>

            <li class="<?php echo ($current_page == 'reports') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/reports.php">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            </li>

            <li class="<?php echo ($current_page == 'notice_board') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/admin/notice_board.php">
                    <i class="fas fa-bell"></i> Notice Board
                </a>
            </li>

            <li class="<?php echo ($current_page == 'my_profile') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/my_profile.php">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </li>

        <?php } else { ?>

            <li class="<?php echo ($current_page == 'employee_dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/employee/employee_dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            <li class="<?php echo ($current_page == 'projects') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/employee/projects.php">
                    <i class="fa-solid fa-bag-shopping"></i> Projects
                </a>
            </li>

            <li class="<?php echo ($current_page == 'clients') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/employee/clients.php">
                    <i class="fa-regular fa-handshake"></i> Clients
                </a>
            </li>
            <li class="<?php echo ($current_page == 'my_attendance') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/employee/my_attendance.php">
                    <i class="fa-solid fa-clipboard-user"></i> My Attendance
                </a>
            </li>

            <li class="<?php echo ($current_page == 'notices') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/employee/notices.php">
                    <i class="fa-solid fa-clipboard-user"></i> Notices
                </a>
            </li>

            <li class="<?php echo ($current_page == 'my_profile') ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>/my_profile.php">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </li>

        <?php } ?>

        <li>
            <a href="<?php echo $base_url; ?>/logout.php">
                <i class="fas fa-user-times"></i> Sign Out
            </a>
        </li>

    </ul>
</div>

<div class="del-popup" id="deletepop">
    <h3>Delete Company</h3>
    <p id="poptext"></p>
    <button class="delbtn" onclick="conpop()">Delete</button>
    <button class="canbtn" onclick="cancelpop()">Cancel</button>
</div>

<div class="del-popup" id="confirmdel">
    <h3>Final Confirmation</h3>
    <p id="confirmtext"></p>
    <button class="yesbtn" onclick="context()">Yes</button>
    <button class="canbtn" onclick="cancelpop()">Cancel</button>
</div>
<?php
include __DIR__ . '/footer.php';
?>