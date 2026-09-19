<?php
//session_start();
include './layout/header.php';
include './layout/layout.php';
include 'db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$current_page = 'my_profile';

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $contact = mysqli_real_escape_string($conn, trim($_POST['contact'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $dob = mysqli_real_escape_string($conn, trim($_POST['dob'] ?? ''));
    $designation = mysqli_real_escape_string($conn, trim($_POST['designation'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $contact === '' || $email === '' || $dob === '' || $designation === '') {
        $message = 'Fill all fields!';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Enter a valid email format!';
        $messageType = 'error';
    } elseif (!preg_match('/^[0-9]{10}$/', $contact)) {
        $message = 'Contact must be exactly 10 digits!';
        $messageType = 'error';
    } else {
        $passwordSql = '';
        if ($password !== '') {
            $passwordHash = md5($password);
            $passwordSql = ", password='$passwordHash'";
        }

        $sql = "UPDATE users SET name='$name', contact='$contact', email='$email', joindate='$dob', jobrole='$designation' $passwordSql WHERE id=$userId";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['name'] = $name;
            $_SESSION['username'] = $email;
            $message = 'Profile saved successfully!';
            $messageType = 'success';
        } else {
            $message = 'Profile could not be saved.';
            $messageType = 'error';
        }
    }
}

$profile = [
    'name' => '',
    'contact' => '',
    'email' => '',
    'joindate' => '',
    'jobrole' => '',
    'role' => '',
];

$result = mysqli_query($conn, "SELECT name, contact, email, joindate, jobrole, role FROM users WHERE id=$userId");
if ($result && mysqli_num_rows($result) > 0) {
    $profile = mysqli_fetch_assoc($result);
}
?>

<div class="wrapper">
    <div class="main-panel">
        <div class="content">
            <div class="container-fluid">
                
                <div class="profile-card" style="margin-top: 20px;">
                    <div class="profile-header">
                        <div class="avatar-container" onclick="document.getElementById('fileInput').click()">
                            <div class="avatar-circle" id="profileDisplay">
                                <span class="cam-icon">📷</span>
                            </div>
                            <input type="file" id="fileInput" style="display:none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <h2>My Profile</h2>
                    </div>

                    <form id="profileForm" method="post">
                        <div class="form-section">
                            <div class="row">
                                <div class="col">
                                    <div class="input-box">
                                        <label>Full Name</label>
                                        <input type="text" id="mainName" name="name" placeholder="Enter full name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="input-box">
                                        <label>Contact</label>
                                        <input type="tel" id="mainPhone" name="contact" placeholder="Enter 10-digit contact number" maxlength="10" pattern="[0-9]{10}" title="Please enter exactly 10 digits" value="<?php echo htmlspecialchars($profile['contact'] ?? ''); ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                    </div>
                                    <div class="input-box">
                                        <label>Password</label>
                                        <div class="input-group-row">
                                            <input type="password" id="mainPass" name="password" placeholder="">
                                            <button type="submit" class="btn-update-pwd">Update Password</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="input-box">
                                        <label>Email Address</label>
                                        <div class="input-group-row">
                                            <input type="email" id="mainEmail" name="email" placeholder="example@mail.com" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" required>
                                            <button type="button" class="btn-verify" onclick="verifyEmail()">Verify Email</button>
                                        </div>
                                    </div>
                                    <div class="input-box">
                                        <label>Date of Birth</label>
                                        <input type="date" id="mainDob" name="dob" value="<?php echo htmlspecialchars($profile['joindate'] ?? ''); ?>" required>
                                    </div>
                                    <div class="input-box">
                                        <label>Designation</label>
                                        <input type="text" id="mainDesig" name="designation" placeholder="Role" value="<?php echo htmlspecialchars($profile['jobrole'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="btn-primary" onclick="openModal()">Update Profile</button>
                            </div>
                            <?php if ($message !== '') { ?>
                                <p class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></p>
                            <?php } ?>
                        </div>
                    </form>
                </div> </div> </div> </div> </div> <div id="modalOverlay" class="modal-overlay">
    <div class="modal">
        <h3>Update Information</h3>
        <hr>
        <div class="modal-body">
            <div class="input-box">
                <label>Name</label>
                <input type="text" id="modalName">
            </div>
            <div class="input-box">
                <label>Contact</label>
                <input type="tel" id="modalPhone" placeholder="Enter 10-digit contact number" maxlength="10" pattern="[0-9]{10}" title="Please enter exactly 10 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div class="input-box">
                <label>Date of Birth</label>
                <input type="date" id="modalDob">
            </div>
            <div class="input-box">
                <label>Designation</label>
                <input type="text" id="modalDesig">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-primary" onclick="saveModalData()">Save Changes</button>
            <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="js/my_profile.js"></script>