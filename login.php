<?php
    //$_SESSION = [];
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    
    ob_start();
    session_start();
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
        <!-- Page Title -->
        <title>صندوق پس انداز واقف</title>
        <!-- Icon Beside Page Title -->
        <link rel="icon" type="image/x-icon" href="images/icon.png">
        <!-- Global Style Definitions -->
        <!-- Linking External CSS File -->
        <link rel="stylesheet" href="general_styles.css">
        <style>
        </style>
        
    </head>

    <!-- ################################################################### -->
    <!-- ################################################################### -->
    <!-- Body Attributes -->
    <body>
        <!-- ############################# -->
        <!-- First Header -->
        <div  style="font-size:3vw;
                    color:yellow;
                    text-align:center;
                    background-color: rgb(90, 90, 90);
                    font-family:titr;">
            صندوق پس انداز واقف
        </div>
        <!-- Login Form -->
        <form action="validate.php" method="post">
            <div class="imgcontainer">
                <img src="images/vagif3.jpg" alt="Vaghef Loan Management" class="avatar">
            </div>
            <h2>
                ورود مديران
            </h2>
            
            <!-- To Show Error Message After Validating Result In Login.php -->
            <?php if (isset($_GET['error'])) { ?>
    
                <p class="error"><?php echo $_GET['error']; ?></p>
    
            <?php } ?>
        
            <div class="container">
                <label for="uname"><b>نام کاربری</b></label>
                <br>
                <input  type="text" 
                        placeholder="Enter Username" 
                        name="uname" 
                        required>
                <br>
                <label for="password"><b>کلمه عبور</b></label>
                <br>
                <input  type="password" 
                        placeholder="Enter Password" 
                        name="password" 
                        required>
                <br>
                <button type="submit">ورود</button>
                <br>
                <label>
                    <input  type="checkbox" 
                            checked="checked" 
                            name="remember"> مرا به خاطر بسپار
                </label>
            </div>
            
            <div class="container" style="background-color:#f1f1f1">
                <span class="password">
                    <a href="#">کلمه عبور را فراموش کرده ايد؟</a>
                </span>
                <?php
                    # Include
                    require_once 'jdf.php';
                    $now = jdate('Y/m/d',time(),'','Asia/Tehran','en');
                    echo "<div>$now</div>";
                ?>
            </div>
        </form>


        <!-- ############################# -->
        <!-- ############################# -->
        <!-- JavaScript Code -->
        
        <script type="text/javascript">  
        </script>
    </body>
</html>
