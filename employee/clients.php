<?php
//session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);
$page_title = "Clients";
include '../layout/header.php';
include '../db.php';

$company_id = $_SESSION['company_id'];

$employee_id = $_SESSION['user_id'];

$error_contact = "";
$error_email = "";
$show_modal = false;

$projectquery = mysqli_query($conn,"SELECT name,price FROM sales WHERE company_id='$company_id' ORDER BY id DESC");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $saveid = $_POST['saveid'] ?? '';

  $name = mysqli_real_escape_string($conn,$_POST['name']);
  $contact = mysqli_real_escape_string($conn,$_POST['contact']);
  $email = mysqli_real_escape_string($conn,$_POST['email']);
  $project = mysqli_real_escape_string($conn,$_POST['project_name']);
  $price = mysqli_real_escape_string($conn,$_POST['price']);

  if(!empty($saveid)){
      $check = mysqli_query($conn,"SELECT * FROM clients WHERE (contact = '$contact' OR email = '$email') AND id != '$saveid' AND employee_id = '$employee_id' AND company_id = '$company_id'");
  }
  else{
    $check = mysqli_query($conn,"SELECT * FROM clients WHERE (contact = '$contact' OR email = '$email') AND company_id = '$company_id'"); 
  }

  if(mysqli_num_rows($check) > 0){
    $row = mysqli_fetch_assoc($check);
    $show_modal = true;
    if($row['contact'] == $contact){
        $error_contact = "This Contact already existed";
    }
    if($row['email'] == $email){
        $error_email = "This email already existed";
    }
  }
  else{
    if(!empty($saveid)){
      mysqli_query($conn,"UPDATE clients SET client_name = '$name',contact = '$contact',
                          email = '$email',project = '$project',price = '$price',
                          updated_at = NOW() WHERE id = '$saveid' AND employee_id= '$employee_id' AND company_id = '$company_id'");
    }
    else{
      mysqli_query($conn,"INSERT INTO clients (client_name,contact,email,project,price,employee_id,company_id,created_at,updated_at) 
      VALUES ('$name','$contact','$email','$project','$price','$employee_id','$company_id',NOW(),NOW())");
    }
      header("Location: clients.php");
      exit();
  }
}


if(isset($_GET['delete'])){
  $id = $_GET['delete'];
  mysqli_query($conn,"DELETE FROM clients WHERE id = '$id' AND employee_id = '$employee_id' AND company_id ='$company_id'");
  header("Location: clients.php");
  exit();
}

$editdata = null;
if(isset($_GET['edit'])){
  $id = (int)$_GET['edit'];
  $res = mysqli_query($conn,"SELECT * FROM clients WHERE id = $id AND employee_id = '$employee_id' AND company_id = '$company_id'");
  $editdata = mysqli_fetch_assoc($res);
  if($editdata){
    $show_modal = true;
  }
}

$clientquery = mysqli_query($conn,"SELECT * FROM clients WHERE employee_id = '$employee_id' AND company_id = '$company_id' ORDER BY id DESC");
?>

<body>
  <div class="main">
    <div id="layout">
      <?php include '../layout/layout.php'; ?>
    </div>

    <div class="container" style="margin-top: 25px;">
      <header>
        <div>
          <h2>Client Management</h2>
        </div>
        <button class="btn-add" onclick="openAddModal()">
          + Add Client</button>
      </header>
      <div class="table-wrapper">
      <table id="clientTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Project</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <?php while($row = mysqli_fetch_assoc($clientquery)){?>
              <tr>
                <td><?= $row['client_name'] ?></td>
                <td><?= $row['contact'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['project'] ?></td>
                <td><?= $row['price'] ?></td>
                <td>
                  <button class="edit-btn" onclick="window.location.href='clients.php?edit=<?= $row['id'] ?>'">Edit</button>
                  <button class="delete-btn" onclick="askDelete(<?= $row['id'] ?>)">Delete</a></button></td>
                </tr>
              <?php } ?>
        </tbody>
      </table>
      </div>
    </div>

    <div id="formModal" class="modal" style="display:<?= $show_modal ? 'block' : 'none' ?>">
      <div class="modal-content" style="height: 75%; overflow:scroll;">
        <h3 id="modalTitle">
        <?= ($editdata || !empty($saveid)) ? "Edit Clients Details" : "Add Client Details" ?></h3>
        <hr />
        <form id="clientForm" method= "POST" action="clients.php">
          <input type="hidden" id="editIndex" name="saveid" value="<?= $_POST['saveid'] ?? $editdata['id'] ?? '' ?>" />
          <label>Name</label>
          <input type="text" id="name" name= "name"
          value= "<?= $_POST['name'] ?? $editdata['client_name'] ?? '' ?>" placeholder="Enter name" required />

          <label>Contact</label>
          <input
            type="tel"
            id="contact"
            name= "contact"
            value= "<?= $_POST['contact'] ?? $editdata['contact'] ?? '' ?>"
            pattern="[0-9]{10}"
            maxlength="10"
            placeholder="Enter 10-digit contact number"
            title="Please enter exactly 10 digits"
            required />
            <span id="error-contact"><?= $error_contact ?></span>

          <label>Email</label>
          <input
            type="email"
            id="email"
            name= "email"
            value= "<?= $_POST['email'] ?? $editdata['email'] ?? '' ?>"
            placeholder="example@mail.com"
            required />
            <span id="error-email"><?= $error_email ?></span>

          <label>Project</label>
          <select id="project" name="project_name" onchange="setPrice()" required>
          <option value = "">--Select Project--</option>
          <?php mysqli_data_seek($projectquery , 0);
          $selected_project = $_POST['project_name'] ?? $editdata['project'] ?? '';
                while($p = mysqli_fetch_assoc($projectquery)){?>
                <option value="<?= $p['name'] ?>"
                data-price="<?= $p['price'] ?>"
                <?= ($selected_project == $p['name']) ? 'selected' : '' ?>>
                <?= $p['name'] ?></option>
          <?php } ?>
          </select>

          <label>Price</label>
          <input type="number" id="price" name= "price"
           value="<?= $_POST['price'] ?? $editdata['price'] ?? '' ?>"/>

          <div class="button-group">
            <button type="submit" class="btn-save" id="saveBtn">
              <?= $editdata ? "Update" : "Save" ?></button>
            <button type="button" class="btn-clear" onclick="closeModal()">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <div id="deleteModal" class="modal">
      <div class="modal-content confirm-box">
        <div class="warning-icon">⚠️</div>
        <h3>Are you sure?</h3>
        <p>
          Do you really want to delete this client? This action cannot be
          undone.
        </p>
        <div class="button-group" style="justify-content: center">
          <button class="btn-yes" id="confirmDeleteBtn">Yes, Delete</button>
          <button class="btn-no" onclick="closeDeleteModal()">
            No, Cancel
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