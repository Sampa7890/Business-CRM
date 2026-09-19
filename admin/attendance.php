<?php
ob_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Kolkata');

$page_title = "Attendance";

include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';

$company_id = $_SESSION['company_id'];


$today = $_GET['date'] ?? date('Y-m-d');

$msg = $_SESSION['msg'] ?? "";
$error = $_SESSION['error'] ?? "";

$employees = mysqli_query($conn, "SELECT id,name FROM users WHERE company_id = '$company_id' AND role = 'employee' AND joindate <= '$today'");


if(isset($_POST['save-atten'])){
    $date = $_POST['date'] ?? $today;
    $current_date = date('Y-m-d');

    if($date <= $current_date){

        if(mysqli_num_rows($employees) == 0){
            $error = "No employees available for this date";
        } else {

            $emp_id = $_POST['employee_id'];
            $status = $_POST['status'] ?? [];

        $saved = false;

            for($a = 0; $a < count($status); $a++){
                $att_empid = $emp_id[$a];
                $att_stat = $status[$a] ?? '';

            $check = mysqli_query($conn, "SELECT * FROM attendance WHERE company_id = '$company_id' AND employee_id = '$att_empid' AND date = '$date'");

            if(mysqli_num_rows($check) == 0){
                mysqli_query($conn, "INSERT INTO attendance (company_id,employee_id,date,status) VALUES ('$company_id','$att_empid','$date','$att_stat')");

        $saved = true;
        }
    }
        if($saved){
            $_SESSION['msg'] = "Attendance Saved Successfully";
        }
        else{
            $_SESSION['error'] = "Attendance already saved";
        }
        header("Location: attendance.php");
        exit();
    }    
  }
}

$success = $_SESSION['success'] ?? "";
$error2 = $_SESSION['error2'] ?? "";


if(isset($_POST['add_holiday'])){
    $date = $_POST['date'];
    $holiday_name = $_POST['holiday_name'];

    $complete = mysqli_query($conn, "SELECT * FROM holiday WHERE date = '$date'");

    if(mysqli_num_rows($complete) > 0){
        $_SESSION['error2'] = "Holiday Alrady Added";
    } else{ 
        mysqli_query($conn, "INSERT INTO holiday(date,holiday_name,company_id) VALUES('$date','$holiday_name','$company_id')");
        $_SESSION['success'] = "Holiday Added Successfully";
  }
        header("Location: attendance.php");
        exit();
}

if(isset($_POST['delete_btn'])){
    $delete_id = $_POST['delete_btn'];
    mysqli_query($conn, "DELETE FROM holiday WHERE id = '$delete_id'");

    header("Location: attendance.php");
    exit();
}

$holiday = mysqli_query($conn, "SELECT * FROM holiday WHERE company_id = '$company_id' ORDER BY date ASC");
?>

<body>
<div class = "main">
    <div class= "full-page">
    <div class="attendance-page">
        <h2>Mark Attendance</h2>

            <?php if($msg != ""){?>
                <div class="massage"><?= $msg ?>
                </div>
            <?php } ?>

            <?php if($error != ""){?>
                <div class="error"><?= $error ?>
                </div>
            <?php } ?>
            <?php unset($_SESSION['msg']);
                  unset($_SESSION['error']);
            ?>
            <form method = "GET">
                   <input type = "date" name= "date" value= "<?= $today ?>" max= "<?= date('Y-m-d') ?>" onchange = "this.form.submit()">
            </form>
        <form method = "POST">
            <div class="Form-atten">
                <input type = "hidden" name= "date" value= "<?= $today ?>">
            <table class = "attend-teb" >
                <tr>
                    <th>Employee ID</th>
                    <th>Employee</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                    <?php while($emp = mysqli_fetch_assoc($employees)){?>
                <tr>
                    <td>
                        <?= $emp['id'] ?>
                    </td>
                    <td>
                        <?= $emp['name'] ?>
                    </td>
    
                        <?php
                        $check2 = mysqli_query($conn, "SELECT * FROM attendance WHERE company_id = '$company_id' AND employee_id = '$emp[id]' AND date = '$today'");
                        $row2 = mysqli_fetch_assoc($check2);
                        ?>
                    <td>
                        <?php if($row2){?>
                        <?= ucfirst($row2['status']) ?>
                        <?php } else { ?>
                        Pending
                        <?php } ?>
                    </td>

                    <td>
                        <?php if($row2){ ?>
                            <span class="save">Saved</span>
                                <?php } else { ?>

                                    <input type="hidden" name="employee_id[]" value="<?= $emp['id'] ?>">
                                    <input type="hidden" name="employee_name[]" value="<?= $emp['name'] ?>">

                                    <select name="status[]" class="status-select">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="leave">Leave</option>
                                        <option value="holiday">Holiday</option>
                                    </select>

                                <?php } ?>
                    </td>
                </tr>   
                <?php } ?>
            </table>
                <button type = "submit" class="btn-attend" name="save-atten">Save Attendance</button>
            </div>
        </form>
    </div>
    <div class="box-holiday">
        <h2>Holiday Manegment</h2>
                    <?php if($success != ""){?>
                <div class="massage"><?= $success ?>
                </div>
            <?php } ?>
                        <?php if($error2 != ""){?>
                <div class="error"><?= $error2 ?>
                </div>
            <?php } ?>
            <?php unset($_SESSION['success']);
                  unset($_SESSION['error2']);
            ?>
        <form method = "POST" class="">
        <input type = "date" class = "date" name = "date" min= "<?= date('Y-m-d') ?>"required>
        <input type = "text" class="text-holiday" name = "holiday_name" placeholder = "Holiday Name" required>
        <button type = "submit" class= "add-btn" name = "add_holiday"> Save Holiday </button>
        </form>
        <div class= "row-holiday">
            <?php while($row = mysqli_fetch_assoc($holiday)){?>
            <div class = "holiday-item">
            <span><?php echo $row['date'] ?></span>
            <span><?php echo $row['holiday_name'] ?></span>
            <form method = "POST">
                <input type="hidden" name="delete_btn" value="<?php echo $row['id'] ?>">
                <button type="submit" class = "del-btn"><i class="fa-solid fa-trash"></i>
                </button>
            </form>
            
            </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>
</body>