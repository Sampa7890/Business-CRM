<?php
//session_start();

$page_title = "Sales";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';

$company_id = $_SESSION['company_id'];
$created_by = $_SESSION['username'] ?? 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = isset($_POST['projectName']) ? mysqli_real_escape_string($conn, $_POST['projectName']) : '';

    $description = isset($_POST['projectDescription']) ? mysqli_real_escape_string($conn, $_POST['projectDescription']) : '';

    $price = isset($_POST['projectPrice']) ? mysqli_real_escape_string($conn, $_POST['projectPrice']) : '';

    $deadline = isset($_POST['projectDeadline']) ? mysqli_real_escape_string($conn, $_POST['projectDeadline']) : '';

    if (isset($_POST['addSale'])) {
        try{
        if (empty($name) || empty($description) || empty($price) || empty($deadline)) {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'All fields are required.'
            ];
            exit();
        }

        $created_by = $_SESSION['username'] ?? 'admin';

        //changed code to fetch employee id and status from form
        $status = $_POST['projectStatus'];

        $employee_id = $_POST['projectAssigned'];

        $sql = "INSERT INTO sales ( name,description,price,deadline,status,employee_id,created_by, company_id) VALUES ('$name','$description','$price','$deadline', '$status', '$employee_id','$created_by','$company_id')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['edited_msg'] = [
                'type' => 'success',
                'msg'  => 'Sales added successfully.'
            ];
        } else {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error adding sale.'
            ];
        }
    } catch (Exception $e) {
        $_SESSION['edited_msg'] = [
            'type' => 'danger',
            'msg'  => 'An error occurred: ' . $e->getMessage()
        ];
    }
}

    if (isset($_POST['editSale'])) {
        if (empty($name) || empty($description) || empty($price) || empty($deadline)) {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'All fields are required.'
            ];
            exit();
        }

        $id = $_POST['sale_id'];

        $status = $_POST['projectStatus'];

        $employee_id = $_POST['projectAssigned'];

        // change code to update status and employee_id in edit query

        $sql = "UPDATE sales SET name='$name', description='$description', price='$price', deadline='$deadline', status='$status', employee_id='$employee_id'
        
        WHERE id=$id
        AND company_id='$company_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['edited_msg'] = [
                'type' => 'success',
                'msg'  => 'Sales updated successfully.'
            ];
        } else {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error updating sale.'
            ];
        }
    }

    if (isset($_POST['deleteSale'])) {
        $id = $_POST['deleteSale'];

        $sql = "DELETE FROM sales WHERE id=$id AND company_id= '$company_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['edited_msg'] = [
                'type' => 'success',
                'msg'  => 'Sales deleted successfully.'
            ];
        } else {
            $_SESSION['edited_msg'] = [
                'type' => 'danger',
                'msg'  => 'Error deleting sale.'
            ];
        }
    }
}
// Fetch sales data with employee names
$sales = mysqli_query($conn, "SELECT sales.*,users.name AS employee_name,users.jobrole FROM sales LEFT JOIN users ON users.id = sales.employee_id WHERE sales.company_id='$company_id' ORDER BY sales.id DESC ");

// Fetch employees for assignment
$employees_query = mysqli_query( $conn,"SELECT id,name,jobrole FROM users WHERE role='employee' AND company_id='$company_id' ORDER BY name ASC");

$employees =mysqli_fetch_all($employees_query, MYSQLI_ASSOC);
?>

<body>
    <div class="main">
        <div class="main-content">
            <header>
                <h2>Sales Overview</h2>
                <button
                    class="btn-add"
                    onclick="openProjectModal()">
                    + Add Project
                </button>
            </header>
            <?php if (isset($_SESSION['edited_msg'])): ?>
                <div class="alert alert-<?php echo $_SESSION['edited_msg']['type']; ?> alert-dismissible fade show" role="alert">

                    <?php echo $_SESSION['edited_msg']['msg']; ?>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
                <?php unset($_SESSION['edited_msg']); ?>
            <?php endif; ?>
            <div>

                
                    <div class="table-wrapper">

                        <table class="sales-table ">

                            <thead class="bg-primary">
                                <tr class="">
                                    <th>Project Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-primary-subtle">
                            <?php if (mysqli_num_rows($sales) > 0): ?>
                                <?php while ($sale = mysqli_fetch_assoc($sales)): ?>

                                <tr>

                                    <td><?= $sale['name'] ?></td>

                                    <td class="description-cell">
                                        <?= $sale['description'] ?>
                                    </td>

                                    <td>
                                        ₹<span class="price"><?= $sale['price'] ?></span>
                                    </td>

                                    <td>
                                        <span
                                            class="deadline"
                                            data-deadline="<?= date('Y-m-d', strtotime($sale['deadline'])) ?>"
                                            data-id="<?= $sale['id'] ?>">
                                            <?= date('d.m.Y', strtotime($sale['deadline'])) ?>
                                        </span>
                                    </td>

                                    <td id="status_value">
                                        <?= $sale['status'] ?>
                                    </td>

                                    <td id="assigned_value">
                                        <?= $sale['employee_name'] ?? 'Unassigned' ?>
                                    </td>

                                    <td>

                                        <div class="card-buttons">
                                            <button class="edit-btn"                                               
                                                 data-status="<?= $sale['status'] ?>"
                                                data-emp_id="<?= $sale['employee_id'] ?>"

                                                data-employees='<?= json_encode($employees) ?>'

                                                onclick="editProject(this)"> 
                                                
                                                <i class="fa-regular fa-pen-to-square" style="color: rgb(96, 85, 236);">
                                                </i>                                      
                                            </button>
                                            <button
                                                class="delete-btn"
                                                onclick="deleteProject(this)">
                                                    <i class="fa-solid fa-trash" style="color: rgb(227, 34, 14);"></i>
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                                <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">

                                            <p class="text-muted m-0">
                                                No projects or sales records available.
                                            </p>
                                            
                                        </td>

                                    </tr>
                                    <?php endif; ?>
                            </tbody>

                        </table>

                    </div>
                
                
              
            </div>
        </div>

        <!-- ADD / EDIT MODAL -->

        <div
            class="modal"
            id="projectModal">
            <div class="modal-content">
                <h2>Add / Edit Project</h2>
                <form id="projectForm" action="" method="post">
                    <input id="sale_form_action" type="hidden" name="addSale" value="1">
                    <input id="sale_edit_id" type="hidden" name="sale_id" value="">
                    <label>
                        Project Name
                    </label>
                    <input type="text" id="projectName" name="projectName" required>
                    <label>
                        Project Description
                    </label>
                    <textarea id="projectDescription" name="projectDescription" rows="4" required></textarea>
                    <label>
                        Project Price
                    </label>
                    <input type="number" id="projectPrice" name="projectPrice" required>
                    <label>
                        Project Deadline
                    </label>
                    <input type="date" id="projectDeadline" name="projectDeadline" required>
                    <label>
                        Project Status
                    </label>
                    <!-- changed code to add status dropdown -->
                    <select id="projectStatus" name="projectStatus">

                        <option value="Not Started">
                            Not Started
                        </option>

                        <option value="In Progress">
                            In Progress
                        </option>

                        <option value="Completed">
                            Completed
                        </option>

                    </select>
                    <!-- change code to add employee assignment dropdown -->
                    <label>
                        Assign To
                    </label>
                    <select id="projectAssigned"  class="assigned-select" name="projectAssigned" onchange="showEmployeeDetails()" style="width: 100%;">

                        <option value="">
                            Select Employee
                        </option>

                        <?php foreach($employees as $employee){ ?>
                            <option
                                value="<?= $employee['id'] ?>"
                                data-jobrole="<?= $employee['jobrole'] ?>">
                                <?= $employee['name'] ?> (EMP<?= $employee['id'] ?> - <?= $employee['jobrole'] ?>)
                            </option>

                        <?php } ?>

                    </select>

                        <!-- <div class="employee-info mt-2">

                            <p>
                                <strong>Employee ID:</strong>

                                <span id="employeeIdText">-</span>
                            </p>

                            <p>
                                <strong>Job Role:</strong>

                                <span id="employeeRoleText">-</span>
                            </p>

                        </div> -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" onclick="closeProjectModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE MODAL -->

        <div
            class="modal"
            id="deleteModal">
            <div
                class="modal-content confirm-box">
                <div class="warning-icon">
                    ⚠️
                </div>
                <h2>
                    Delete Project
                </h2>
                <p>
                    Are you sure you want to delete this project?
                </p>
                <div class="button-group">
                    <form id="deleteForm" action="" method="post">
                        <input type="hidden" name="deleteSale" id="delete_sale_id" value="">
                        <button
                            class="btn btn-primary"
                            onclick="confirmDeleteProject()">
                            Yes Delete
                        </button>
                    </form>

                    <button
                        class="btn btn-danger"
                        onclick="closeDeleteModal()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
include '../layout/footer.php';
?>
</body>

</html>