<?php 
if(!isset($_SESSION['company_id'])){
    echo "<div class='com-warning'>
                Please create or select a Company First
          </div>";
    exit();
}
?>