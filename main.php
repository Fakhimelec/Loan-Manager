<?php 

session_start(); 
ob_start();

?>
<!DOCTYPE html>
<html>
    
    <!-- ################################################################### -->
    <!-- Head Attributes -->
    
    <head>
        <meta charset="UTF-8">
        <meta name="keywords"  content="PHP, 
                                        HTML, 
                                        CSS, 
                                        JavaScript, 
                                        Accounting, 
                                        Loan Management">
        <meta name="description" content="Vaghef Loan">
        <meta name="author" content="Babak Fakhim">
        <!-- <meta http-equiv="refresh" content="60"> -->
        <meta name="viewport"  content="width=device-width, 
                                        initial-scale=1.0">
    
        <!-- Linking External CSS File -->
        <link rel="stylesheet" href="general_styles.css">
        <!-- Icon library to show a hamburger menu (bars) on small screens -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        
        <!-- Page Title -->
        <title>
            صندوق پس انداز واقف
        </title>
        
        <!-- Icon Beside Page Title -->
        <link rel="icon" type="image/x-icon" href="images/icon.png">
        
        <!-- Global Style Definitions -->
        <style>
        </style>
<!--        <script src="jquery-3.7.0.min.js"></script>  
        <script>
        $(document).ready(function(){
          $("h1").mouseenter(function(){
            $(this).slideUp("slow");
          });
          $("h1").mouseleave(function(){
            $(this).slideDown("slow");
          });
        });
        </script>-->
    </head>
    
    <!-- ################################################################### -->
    <!-- ################################################################### -->
    <!-- Body Attributes -->
    <body>
        <!-- ############################# -->
        <!-- First Header -->
        <h1>
            صندوق پس انداز واقف
        </h1>
        <br>
        <!-- ############################# -->
        <!-- Top Navigation Menu Bar --> 
        <div class="topnav" id="topnav">
            <a href="javascript:void(0);" class="icon" 
                onclick="menu_drop()">
                <form style="border:none">
                    <i class="material-icons">dataset</i>
                </form>
            </a>
            <a class= "disblock"></a>
            <a class= "disblock"></a>
            <a href="main.php" >صفحه اصلي</a>
            <a href="members.php" >اعضاي صندوق</a>
            <a href="loans.php">وام هاي دريافتي</a>
            <a href="expenses.php">هزينه هاي صندوق</a>
            <a href="reports.php">گزارشات صندوق</a>
            <a href="lottery.php">قرعه کشي</a>
            <a href="guide.php">راهنما</a>
            <a href="login.php" onClick="return confirm('خارج مي شويد؟')">خروج</a>
        </div>
      <!-- ############################# -->
      <!-- Page Header -->
      <h3>
        صفحه ي اصلي
      </h3>
        <br><br>
        <div id= "status" class="status">
            <?php
            echo "وقت بخير جناب آقاي"."<br>";
            echo $_SESSION['first_name']."  ".$_SESSION['last_name'];
            ?>
        </div>    
    
      <!-- ############################# -->
      <!-- Persian Date -->
            <?php
                # Include
                require_once 'jdf.php';
                $now = jdate('Y/m/d',time(),'','Asia/Tehran','en');
                echo "<div>$now</div>";
            ?>
      </div>
      
      <!-- ############################# -->
      <!-- JavaScript Code -->
      <script type="text/javascript" src="general_javascript.js"></script>
    
      <script>  
      </script>
    </body>
</html>
