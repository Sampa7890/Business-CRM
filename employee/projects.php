<?php
//session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

$page_title = "Projects";
include '../layout/header.php';
include '../db.php';

$company_id = $_SESSION['company_id'];

$projects = mysqli_query($conn, "SELECT name,price FROM sales WHERE company_id = '$company_id' ORDER BY id DESC");


?>

  <body>
    <div class="main">
    <div id="layout">
      <?php include '../layout/layout.php'; ?>
    </div>

  <div class="main-content">
    <div id="sales" class="content-section">
      <h2 style="text-align: center; margin-top: 20px">Sales Overview</h2>
      <div class="sales-container">
        <?php while($row = mysqli_fetch_assoc($projects)){?>
        <div class="sales-card">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p class="price">₹<?= number_format($row['price'],2) ?></p>
          </div>
        <?php } ?>
        </div>
      </div>
    </div>
  </div>

    <?php
      include '../layout/footer.php';
    ?>
    
  </body>
</htmL>
