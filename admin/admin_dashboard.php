<?php
//session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

$page_title = "Admin Dashboard";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';

$company_id = $_SESSION['company_id'];

/*total revenue*/
/*1st code ( -revenue always show 0)*/
$total_earn_query = mysqli_query($conn, "SELECT SUM(price) AS total FROM clients WHERE company_id = '$company_id'");
$total_earning = mysqli_fetch_assoc($total_earn_query)['total'] ?? 0;

$total_salary_query = mysqli_query($conn, "SELECT SUM(salary) AS total FROM users WHERE company_id = '$company_id'");
$total_salaries = mysqli_fetch_assoc($total_salary_query)['total'] ?? 0;

$query = max(0, $total_earning - $total_salaries);
if($query < 0){
    $query = 0;
}
$loss= $total_salaries - $total_earning;
if($loss < 0){
    $loss = 0;
}
/*$query = max(0, $total_earning - $total_salaries);*/



/*total employee*/
$query1 = mysqli_query($conn,"SELECT COUNT(*) AS total_employee FROM users WHERE role = 'employee' AND company_id = '$company_id'");
$row1 = mysqli_fetch_assoc($query1);
$totemp = $row1['total_employee'] ?? 0;

/*total clients*/
$query2 = mysqli_query($conn,"SELECT COUNT(*) AS total_client FROM clients WHERE company_id = '$company_id'");
$row2 = mysqli_fetch_assoc($query2);
$totclient = $row2['total_client'] ?? 0;

/*total projects*/
$query3 = mysqli_query($conn,"SELECT COUNT(DISTINCT name) AS total_project FROM sales WHERE company_id = '$company_id'");
$row3 = mysqli_fetch_assoc($query3);
$totproject = $row3['total_project'] ?? 0;

$monthlysale = array_fill(0, 12 , 0);
$ms = mysqli_query($conn,"SELECT MONTH(created_at)AS month,
                SUM(price)AS total_sales FROM sales 
                WHERE company_id = '$company_id' 
                GROUP BY month(created_at)");
    
    while($row = mysqli_fetch_assoc($ms)){
        $index = $row['month'] - 1;
        $monthlysale[$index] = $row['total_sales'];
    }

$empsales = [];
$barsale = mysqli_query($conn, "SELECT employee_id,SUM(price)AS total_sales FROM clients 
                WHERE company_id = '$company_id'
                GROUP BY employee_id");
    while($row = mysqli_fetch_assoc($barsale)){
        $empsales[$row['employee_id']] = $row['total_sales'];
    }
$empname = [];
$empsale = [];
$baremp = mysqli_query($conn, "SELECT id,name FROM users WHERE role = 'employee' AND company_id = '$company_id'");
    while($row = mysqli_fetch_assoc($baremp)){
        $empname[] = $row['name'];
        $empsale[] = $empsales[$row['id']] ?? 0;
    }

$projectsql = mysqli_query($conn, "SELECT project AS name, COUNT(id) AS users
                FROM clients WHERE company_id = '$company_id'
                GROUP BY project
                ORDER BY users DESC");
        $projects = [];
        while($row = mysqli_fetch_assoc($projectsql)){
            $projects[] = $row;
        }
?>

<body>
    <div class="main">        
        <h1 class="admin"> Welcome to Admin Dashboard</h1>        
        <div class="for-all">
            <div class="rev">
                <h3><i class="icon fa-solid fa-hand-holding-dollar" style="color: rgb(77, 227, 14);"></i> Total Revenue</h3>
                <div class="data"><span>₹ </span><?php echo $query ?></div>
            </div>
            <div class="rev">
                <h3><i class="icon fa-solid fa-arrow-trend-down" style="color: red;"></i> Total Loss</h3>
                <div class="data"><span>₹ </span><?php echo $loss ?></div>
            </div>
            <div class="emp">
                <h3><i class="icon fa-solid fa-users" style="color: rgb(20, 123, 228);"></i> Total Employees</h3>
                <div class="data"><?php echo $totemp ?></div>
            </div>
            <div class="client">
                <h3><i class="icon fa-solid fa-people-group" style="color: rgb(217, 187, 13);"></i> Total Clients</h3>
                <div class="data"><?php echo $totclient ?></div>
            </div>
            <div class=" project">
                <h3><i class="icon fa-solid fa-diagram-project" style="color: rgb(14, 65, 227);"></i> Total Projects</h3>
                <div class="data"><?php echo $totproject ?></div>
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
            <h2>Sales By Employee</h2>
            <div class="sales-bar">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>
        <div class="per-emp">
            <h2>Top Performing Employee</h2>
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