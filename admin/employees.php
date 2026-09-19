<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$page_title = "Employees";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';


$company_id = $_SESSION['company_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $joindate = $_POST['joindate'] ?? '';
    $account = $_POST['account'] ?? '';
    $jobrole = $_POST['jobrole'] ?? '';

    if (isset($_POST['addEmployee'])) {
        $alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&";

        $pass = "";
        for ($a = 0; $a < 8; $a++) {
            $pass .= $alpha[rand(0, strlen($alpha) - 1)];
        }

        $password = md5($pass);
        $created_by = $_SESSION['username'] ?? 'admin';
        $role = 'employee';

        try {
            $sql = "INSERT INTO users (name, email, contact, salary, joindate, account, jobrole, password, role, created_by,company_id) VALUES ('$name', '$email', '$contact', '$salary', '$joindate', '$account', '$jobrole', '$password', '$role', '$created_by', '$company_id')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['added_email'] = $email;
                $_SESSION['added_password'] = $pass;
                $_SESSION['edited_msg'] = [
                    'type' => 'success',
                    'msg'  => 'Employee details added successfully.'
                ];
            } else {
                $_SESSION['edited_msg'] = [
                    'type' => 'danger',
                    'msg'  => 'Error adding employee details. Please check if the user already exists and try again.'
                ];
            }
        } catch (Exception $e) {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error adding employee details. Please check if the user already exists and try again.'
            ];
        }
    }

    if (isset($_POST['editEmployee'])) {
        $id = $_POST['editEmployee'];

        $sql = "UPDATE users SET name='$name', email='$email', contact='$contact', salary='$salary', joindate='$joindate', account='$account', jobrole='$jobrole' WHERE id=$id AND company_id = '$company_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['edited_msg'] = [
                'type' => 'success',
                'msg'  => 'Employee details updated successfully.'
            ];
        } else {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error updating employee details.'
            ];
        }
    }

    if (isset($_POST['deleteEmployee'])) {
        $id = $_POST['deleteEmployee'];

        $sql = "DELETE FROM users WHERE id=$id AND company_id='$company_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['edited_msg'] = [
                'type' => 'success',
                'msg'  => 'Employee details deleted successfully.'
            ];
        } else {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error deleting employee details.'
            ];
        }
    }
}

$employess = mysqli_query($conn, "SELECT * FROM users WHERE role='employee' AND company_id='$company_id' ORDER BY id DESC");
?>

<body>
    <div class="main">
       
        <div class="employee">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="">Employee Managment</h1>
            <button class="btn btn-primary mb-3" onclick="openPop()"><b>+ Add Employee</b></button>

            </div>
        <div>

        <?php if (isset($_SESSION['edited_msg'])): ?>
    <div class="alert alert-<?php echo $_SESSION['edited_msg']['type']; ?> alert-dismissible fade show" role="alert">

        <?php echo $_SESSION['edited_msg']['msg']; ?>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

    <?php unset($_SESSION['edited_msg']); ?>

<?php endif; ?>


            <div id="Popup" style="display: none;">
            <div class="popup-content">
                <form id="add_employee" method="post" class="popup-form" action="" onsubmit="return saveEmployee()">
                    <input id="form_action" type="hidden" name="addEmployee" value="1">
                    <h2 id="popupTitle" class="text-light">Add Employee</h2>
                    <label><input type="text" id="name" name="name" placeholder="Name"></label>
                    <label><input type="email" id="email" name="email" placeholder="Email">
                        <span id="emailmsg" style="color: red; padding-left:5px; display: none;"></span>
                    </label>
                    <label>
                        <input type="tel"  id="contact" name="contact" placeholder="Enter 10-digit contact number" maxlength="10" pattern="[0-9]{10}" >
                    </label>
                    <label><input type="number" id="salary" name="salary" min="0" step="1" placeholder="Salary"></label>
                    <label><input type="text" id="joindate" name="joindate" placeholder="Date"
                            onfocus="this.type='date'" onblur="if(!this.value)this.type='text'"></label>
                    <label><input type="text" id="account" name="account" placeholder="Bank Account No">
                        <span id="bankmsg" style="color: red; font-size: 12px; display: none;"></span></label>
                        <label class="" style="width: 100%;">
                            <select id="jobrole" name="jobrole" class="form-control" style="height:50px;width: 100%;border-radius: 10px;">
                                <option value="">Select Job Role</option>
                                <option value="Frontend Developer">Frontend Developer</option>
                                <option value="Backend Developer">Backend Developer</option>
                                <option value="Full Stack Developer">Full Stack Developer</option>
                                <option value="UI/UX Designer">UI/UX Designer</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="HR">HR</option>
                                <option value="Sales Executive">Sales Executive</option>
                            </select>

                        </label>
                        <div class="d-flex gap-3 justify-content-center align-items-center mt-3">
                            <button type="submit" id="saveBTN" class="btn btn-primary px-4 text-light">
                                Save
                            </button>

                            <button type="button" id="cancel" class="btn btn-danger px-4 text-light" onclick="closePop()">
                                Cancel
                            </button>
                        </div>
                        
                </form>
                </div>
            </div>
            
            <table id="employeeTable" class="mainTable">
                <thead>
                    <tr class="bg-primary">
                        <th >Name</th>
                        <th >Email</th>
                        <th >Contact</th>
                        <th >Salary</th>
                        <th >Joining Date</th>
                        <th >Jobrole</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody class="bg-primary-subtle">
                    <?php 
                    if(mysqli_num_rows($employess) == 0){
                        echo '<tr><td colspan="8" style="text-align:center;">No employees found.</td></tr>';
                    }else{
                    while ($employee = mysqli_fetch_assoc($employess)) { 
$employee_clients = mysqli_query($conn, "SELECT * FROM clients WHERE employee_id='".$employee['id']."' AND company_id='$company_id' ORDER BY id DESC");
                        
                        ?>
                        <tr data-bank="<?= $employee['account'] ?>" data-id="<?= $employee['id'] ?>"
                            data-salary="<?= $employee['salary'] ?>">
                            <td><?= $employee['name'] ?></td>
                            <td><?= $employee['email'] ?></td>
                            <td><?= $employee['contact'] ?></td>
                            <td><span class="currency">&#8377;</span>
                                <?= number_format($employee['salary'],2) ?></td>
                            <td><?= $employee['joindate'] ?></td>
                            <td><?= $employee['jobrole'] ?></td>
                            <td>
                                <button class="btn btn-md view_button" data-emp_email="<?= $employee['email'] ?>" data-emp_name="<?= $employee['name'] ?>" data-clients='<?= json_encode(mysqli_fetch_all($employee_clients,1)) ?>'  onclick="onlyread(this)"><i class="fa-solid fa-eye" style="color: rgb(164, 145, 208);"></i></button>
                                <button class="btn btn-md edit_btn"  onclick="editButt(this)"><i class="fa-regular fa-pen-to-square" style="color: rgb(96, 85, 236);"></i></button>
                                <button class="btn btn-md del_btn" onclick="deleteButt(this)" data-id="<?= $employee['id'] ?>"><i class="fa-solid fa-trash" style="color: rgb(227, 34, 14);"></i></button>
                            </td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>

   <!-- View Details Modal -->

    <div id="viewModal" style="display: none;">
        <div class="view-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                <h4 class="m-0">Sales Activity</h4>
                <h6 id="emp_details">Employee Name (Employee Id)</h6>
                </div>
                <button class="btn btn-danger btn-sm" onclick="closeViewModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <table class="table table-bordered table-striped">
                <tbody id="sales_activity_tbody">
                    <tr class="bg-primary text-light text-center">
                        <th class="p-2">Date</th>
                        <th>Client ID</th>
                        <th>Client Name</th>
                        <th>Project Name</th>
                        <th>Project Price</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="delpop" style="display: none;">
        <div class="del-box">
            <div class="warning-icon">
                    ⚠️
                </div>
            <h2 class="text-danger">Are you sure delete this employee ?</h2>
            <div class="delete-btn">
                <form action="" method="post" style="margin-bottom: 0px;">
                    <input id="deleteEmployee" type="hidden" name="deleteEmployee">
                    <button id="yes-btn" type="submit" class="btn btn-danger">YES</button>
                </form>
                <button onclick="closedelete()" id="no-btn" class="btn btn-primary">NO</button>
            </div>
        </div>
    </div>

    <div id="logindel" style="<?php echo isset($_SESSION['added_email']) ? 'display: flex;' : 'display: none;'; ?>">
        <div class="log-box">
            <h2 class="text-primary">Employee Login Details</h2>
            <h6 class="text-danger">Please save these login ID & Password.They will not be shown again.</h6>
            <div class="box-idpass">
                <div><b>Employee ID: </b><span id="employeeID"><?php echo isset($_SESSION['added_email']) ? $_SESSION['added_email'] : ''; ?></span></div>
                <div class="password_div">
                    <div>
                        <b>Password: </b>
                        <span id="password">
                            <?php echo isset($_SESSION['added_password']) ? $_SESSION['added_password'] : ''; ?>
                        </span>
                    </div>

                    <button type="button"
                        class="btn btn-md btn-primary"
                        onclick="copyPassword()">

                        <i class="fa-regular fa-copy"></i>
                    </button>

                </div>
            </div>

            <button class="logbtn btn btn-primary px-4 text-light" onclick="closelog()">OK</button>
            <?php
            unset($_SESSION['added_email']);
            unset($_SESSION['added_password']);
            ?>
        </div>
        </div>
    </div>
    <?php
include '../layout/footer.php';
?>
    
</body>

</html>