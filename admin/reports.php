<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$page_title = "Reports";
include '../layout/header.php';
include '../db.php';
include '../layout/layout.php';
include '../check_company.php';

$company_id = $_SESSION['company_id'];
$employee_id = $_SESSION['user_id'];

/*Financial report*/ 
$total_earn_query = mysqli_query($conn, "SELECT SUM(price) AS total FROM clients WHERE company_id = '$company_id'");
$total_earning = mysqli_fetch_assoc($total_earn_query)['total'] ?? 0;

$monthly_earn_query = mysqli_query($conn, "SELECT SUM(price) AS total FROM clients WHERE company_id = '$company_id'");
$monthly_earning = mysqli_fetch_assoc($monthly_earn_query)['total'] ?? 0;

$total_salary_query = mysqli_query($conn, "SELECT SUM(salary) AS total FROM users WHERE company_id = '$company_id'");
$total_salaries = mysqli_fetch_assoc($total_salary_query)['total'] ?? 0;

$net_profit = $total_earning - $total_salaries;
if($net_profit <0){
    $net_profit=0;
}
$loss= $total_salaries - $total_earning;
if($loss <0){
    $loss=0;}
/*client report*/ 
$total_client_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients WHERE company_id = '$company_id'");
$total_clients = mysqli_fetch_assoc($total_client_query)['total'] ?? 0;

$active_client_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients WHERE company_id = '$company_id' AND created_at >= NOW() - INTERVAL 30 DAY");
$active_clients = mysqli_fetch_assoc($active_client_query)['total'] ?? 0;

$new_client_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients WHERE company_id = '$company_id' AND created_at >= NOW() - INTERVAL 7 DAY");
$new_clients = mysqli_fetch_assoc($new_client_query)['total'] ?? 0;

$repeat_client_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM (SELECT client_name FROM clients WHERE company_id = '$company_id' GROUP BY client_name HAVING COUNT(id) > 1)AS tableemp");
$repeat_clients = mysqli_fetch_assoc($repeat_client_query)['total'] ?? 0;
?>

<body>
    <div class="main">
        <div class="full-report">
        <h1 class="main-title">BUSINESS OVERVIEW & PERFOMANCE REPORTS</h1>
        <div class="emp-table report-card-section">
            <h2>Employee Performance Reports</h2>
            <div class = "search-wrapper">
                <i class="fa-solid fa-magnifying-glass fa-sm"></i>
                <input type="text" class="search-container" id="emp_search" name="emp_search" placeholder="Search by Employee name or ID or designation">
            </div>
                <div class="report-teb">
                    <table class="teb-final" id="emp_table_data">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Total Project Sold</th>
                                <th>Revenue Generated</th>
                                <th>Attendance</th>
                                <th>Employee salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $emp_list_query = mysqli_query($conn, "SELECT id,name,jobrole,salary FROM users WHERE company_id = '$company_id' AND role = 'employee'");
                            while($emp = mysqli_fetch_assoc($emp_list_query)){
                                $emp_id = $emp['id'];

                                $sold_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM clients WHERE employee_id = '$emp_id'");
                                $total_sold = mysqli_fetch_assoc($sold_query)['total'] ?? 0;

                                $revenue_query = mysqli_query($conn, "SELECT SUM(price) AS total FROM clients WHERE employee_id = '$emp_id'");
                                $revenue = mysqli_fetch_assoc($revenue_query)['total'] ?? 0;

                                $attendance_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE employee_id = '$emp_id'");
                                $attendance = mysqli_fetch_assoc($attendance_query)['total'] ?? 0;
                            ?>
                            <tr>
                                <td><?php echo $emp_id; ?></td>
                                <td class="emp-name"><?php echo htmlspecialchars($emp['name']); ?></td>
                                <td><?php echo htmlspecialchars($emp['jobrole']); ?></td>
                                <td><?php echo $total_sold ?></td>
                                <td><?php echo number_format($revenue, 2); ?></td>
                                <td><?php echo $attendance; ?> days</td>
                                <td><?php echo number_format($emp['salary'], 2); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>

        <div class= "project-teb report-card-section">
            <h2>Project Reports</h2>
            <div class = "search-wrapper">
                <i class="fa-solid fa-magnifying-glass fa-sm"></i>
                <input type="text" class= "search-container" id="project_search" name="project_search" placeholder="Search by Project name or ID">
            </div>
                <div class="report-teb">
                    <table class="teb-final" id="project_table_data">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Project Price</th>
                                <th>Start date</th>
                                <th>Deadline</th>
                                <th>Client Name</th>
                                <th>Employee Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $project_query = mysqli_query($conn, "SELECT * FROM sales WHERE company_id = '$company_id'");

                                while($project = mysqli_fetch_assoc($project_query)){
                                    $sales_project = $project['name'];
                                    $sales_price = $project['price'];
                                    $client_name = 'N/A';
                                    $employee_name = 'Not Assigned';
                                    $status_text = 'Active';
                                        $employees_id = $project['employee_id'];

                                        $emp_query = "SELECT name FROM users WHERE id = '$employees_id'";
                                        $emp_data = mysqli_query($conn,$emp_query);

                                    if($users_data = mysqli_fetch_assoc($emp_data)){
                                        $employee_name = $users_data['name'];
                                    }

                                    $safe_project = mysqli_real_escape_string($conn,$sales_project);

                                    $client_check ="SELECT client_name FROM clients WHERE company_id = '$company_id' AND employee_id = '$employees_id'";
                                    $client_query = mysqli_query($conn,$client_check);
                                    $has_sales = false;

                                    $client_data = mysqli_fetch_array($client_query);
                                    if(!empty($client_data)){
                                        $has_sales = true;
                                        $client_name = $client_data['client_name'];
                                        $status_text = 'Sold';
                                    }
                        ?>
                            <tr>
                                <td><?php echo $project['id']; ?></td>
                                <td class="project-name"><?php echo htmlspecialchars($sales_project); ?></td>
                                <td><?php echo number_format($project['price'], 2); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($project['created_at'])); ?></td>
                                <td><?php
                                    if(!empty($project['deadline'])){
                                        echo date('d-m-Y', strtotime($project['deadline']));
                                    }
                                    else{
                                        echo 'N/A';
                                    }
                                ?></td>
                                <td><?php echo htmlspecialchars($client_name); ?></td>
                                <td><?php echo htmlspecialchars($employee_name); ?></td>
                                <td><span class= "status" data-status= "<?php echo $status_text; ?>">
                                    <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                            
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>

        <div class= "finance-teb report-card-section">
            <h2>Financial Reports</h2>
                <div class="report-teb">
                    <table class="teb-final">
                        <thead>
                            <tr>
                                <th>Total Project Earning</th>
                                <th>Monthly Earning</th>
                                <th>Employee Salaries</th>
                                <th>Net Profit</th>
                                <th>Total Loss</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo number_format($total_earning, 2); ?></td>
                                <td><?php echo number_format($monthly_earning, 2); ?></td>
                                <td><?php echo number_format($total_salaries, 2); ?></td>
                                <td style="font-weight: bold; color:<?php echo $net_profit >= 0 ? 'green' : 'red'; ?>">
                                    <?php echo $net_profit; ?>
                                </td>
                                <td style="font-weight: bold; color:<?php echo $loss <= 0 ? 'green' : 'red'; ?>"><?php echo number_format($loss, 2);?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>

        <div class= "client-teb report-card-section">
            <h2>Client Reports</h2>
                <div class="report-teb">
                    <table class="teb-final">
                        <thead>
                            <tr>
                                <th>Total clients</th>
                                <th>Active Clients</th>
                                <th>New Clients</th>
                                <th>Repeat Clients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $total_clients; ?></td>
                                <td><?php echo $active_clients; ?></td>
                                <td><?php echo $new_clients; ?></td>
                                <td><?php echo $repeat_clients; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
        </div>
    </div>

<?php
include '../layout/footer.php';
?>
</body>
