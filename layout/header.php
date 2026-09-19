<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: ../login.php");
    exit;
}

$current_page = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Business CRM' ?></title>

    <?php if ($current_page != 'my_profile') { ?>
     <!-- Common CSS -->

   <link rel="stylesheet" href="../css/sampa_css/all.min.css">
   <link rel="stylesheet" href="../css/sampa_css/bootstrap.min.css">
   <!-- <link rel="stylesheet" type="text/css" href="../css/ipsita_css/bootstrap-theme.css"/>
    <link rel="stylesheet" type="text/css" href="../css/ipsita_css/bootstrap.css"/> -->

    <!-- css style for sidebar (layout.php) -->
    <link rel="stylesheet" type="text/css" href="../css/layout.css">

    <?php } ?>

    <?php if($role == 'admin') { ?>

        <!-- Admin CSS -->

        <?php if($current_page == 'admin_dashboard') { ?>
            <link rel="stylesheet" href="../css/admin/admin_dashboard.css">
        <?php } ?>

        <?php if($current_page == 'sales') { ?>
            <link rel="stylesheet" href="../css/admin/sales.css">
        <?php } ?>

        <?php if($current_page == 'employees') { ?>
            <link rel="stylesheet" href="../css/admin/employees.css">
        <?php } ?>
        <?php if($current_page == 'attendance') { ?>
            <link rel="stylesheet" href="../css/admin/attendance.css">
        <?php } ?>
        <?php if($current_page == 'reports') { ?>
            <link rel="stylesheet" href="../css/admin/reports.css">
        <?php } ?>
        <?php if($current_page == 'notice_board') { ?>
            <link rel="stylesheet" href="../css/admin/notice_board.css">
        <?php } ?>

        <?php if($current_page == 'my_profile') { ?>
            <link rel="stylesheet" href="./css/sampa_css/bootstrap.min.css">
            <link rel="stylesheet" href="./css/sampa_css/all.min.css">
            <link rel="stylesheet" type="text/css" href="./css/layout.css">

            <link rel="stylesheet" href="./css/my_profile.css">
        <?php } ?>

    <?php }  elseif($role == 'employee') { ?>

        <!-- Employee CSS -->

        <?php if($current_page == "clients" || $current_page == "projects") { ?>
            <link rel="stylesheet" href="../css/employee/empdash_global_style.css">
           
        <?php } ?>

        <?php if($current_page == 'employee_dashboard') { ?> 
            <link rel="stylesheet" href="../css/admin/admin_dashboard.css">
        <?php } ?>

        <?php if($current_page == 'clients') { ?>           
            <link rel="stylesheet" href="../css/employee/clients.css">
        <?php } ?>

        <?php if($current_page == 'projects') { ?>
            <link rel="stylesheet" href="../css/employee/projects.css">
        <?php } ?>

        <?php if($current_page == 'my_attendance') { ?>
            <link rel="stylesheet" href="../css/employee/my_attendance.css">
        <?php } ?>
        <?php if($current_page == 'notices') { ?>
            <link rel="stylesheet" href="../css/employee/notices.css">
        <?php } ?>

        <?php if($current_page == 'my_profile') { ?>
            <link rel="stylesheet" href="./css/sampa_css/all.min.css">
            <link rel="stylesheet" type="text/css" href="./css/layout.css">
            <link rel="stylesheet" href="./css/my_profile.css">
        <?php } ?>

    <?php } ?>

</head>