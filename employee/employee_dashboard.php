<?php
//session_start();

$page_title = "Employee Dashboard";
include '../layout/header.php';
include '../layout/layout.php';
include '../db.php';

$employee_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT SUM(price) AS total_revenue FROM clients WHERE employee_id = '$employee_id'");
$row = mysqli_fetch_assoc($query);
$totrev = $row['total_revenue'] ?? 0;

$query2 = mysqli_query($conn, "SELECT COUNT(*) AS total_client FROM clients WHERE employee_id = '$employee_id'");
$row2 = mysqli_fetch_assoc($query2);
$totclient = $row2['total_client'] ?? 0;

$query3 = mysqli_query($conn, "SELECT COUNT(DISTINCT project) AS total_project FROM clients WHERE employee_id = '$employee_id'");
$row3 = mysqli_fetch_assoc($query3);
$totproject = $row3['total_project'] ?? 0;

$monthlysale = array_fill(0, 12, 0);
$ms = mysqli_query($conn, "SELECT MONTH(created_at)AS month,
                SUM(price)AS total_sales FROM clients 
                WHERE employee_id = '$employee_id' 
                GROUP BY month(created_at)");

while ($row = mysqli_fetch_assoc($ms)) {
    $index = $row['month'] - 1;
    $monthlysale[$index] = $row['total_sales'];
}

$empsales = [];
$barsale = mysqli_query($conn, "SELECT id, price AS total_sales FROM clients 
                WHERE employee_id = '$employee_id'");
while ($row = mysqli_fetch_assoc($barsale)) {
    $empsales[$row['id']] = $row['total_sales'];
}
$empname = [];
$empsale = [];
$baremp = mysqli_query($conn, "SELECT id,client_name FROM clients WHERE employee_id = '$employee_id'");
while ($row = mysqli_fetch_assoc($baremp)) {
    $empname[] = $row['client_name'];
    $empsale[] = $empsales[$row['id']] ?? 0;
}

$projectsql = mysqli_query($conn, "SELECT project AS name, COUNT(id) AS users
                FROM clients WHERE employee_id = '$employee_id'
                GROUP BY project
                ORDER BY users DESC");
$projects = [];
while ($row = mysqli_fetch_assoc($projectsql)) {
    $projects[] = $row;
}
?>

<body>
    <div class="main">

        <h1 class="employee"> Welcome to Employee Dashboard</h1>

        <div class="for-all">
            <div class="rev">
                <h3><i class="icon fa-solid fa-hand-holding-dollar" style="color: rgb(77, 227, 14);"></i> Total Revenue</h3>
                <div class="data"><span>₹ </span><?php echo $totrev; ?></div>
            </div>
            <div class="client">
                <h3><i class="icon fa-solid fa-people-group" style="color: rgb(217, 187, 13);"></i> Total Clients</h3>
                <div class="data"><?php echo $totclient; ?></div>
            </div>
            <div class=" project">
                <h3><i class="icon fa-solid fa-diagram-project" style="color: rgb(14, 65, 227);"></i> Total Projects</h3>
                <div class="data"><?php echo $totproject; ?></div>
            </div>
        </div>
        <div class="all-charts">
            <div class="select-bar">
                <h2> Monthly Sales Overview </h2>
                <div class="graph-box">
                    <canvas id="drawing"></canvas>
                </div>
            </div>
            <div class="sale-emp">
                <h2>Sales By Client</h2>
                <div class="sales-bar">
                    <canvas id="bar-chart"></canvas>
                </div>
            </div>
            <div class="per-emp">
                <h2>Top Performing Client</h2>
                <ul id="topemp"></ul>
            </div>
            <div class="projects">
                <h2>Most Populated Projects</h2>
                <ul id="pro-list"></ul>
            </div>
        </div>

    </div>

    <script>
        var monthlysale = <?php echo json_encode($monthlysale ?? []); ?>;
        var empsale = <?php echo json_encode($empsale ?? []); ?>;
        var empname = <?php echo json_encode($empname ?? []); ?>;
        var projects = <?php echo json_encode($projects); ?>;
    </script>

    <?php
    include '../layout/footer.php';
    ?>
</body>

</htmL>