<?php 

session_start(); 
ob_start();

?>

<?php
if(isset($_SESSION['user_logged_in'])){
    if(!$_SESSION['user_logged_in']){
        header("Location: login.php");
        exit();
    }
}else{
    header("Location: login.php");
    exit();
}
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
        
    </head>
    
    <!-- ################################################################### -->
    <!-- ################################################################### -->
    <!-- Body Attributes -->
    <body>
        <!-- ############################# -->
        <!-- Including Time Variables And Connecting Database -->  
        <?php
            # Include
            require_once 'jdf.php';
            $now = explode('/', jdate('Y/m/d',time(),'','Asia/Tehran','en'));
            $year = $now[0];
            $month = $now[1];
            $day = $now[2];
            # MySQL Database Connection
            # MySQLi Object Oriented Way
            require "db_connect.php";
            
            $sql = "SELECT * FROM member_list ORDER BY member_number DESC LIMIT 1" ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $last_member_num = (int)$row['member_number'] + 1;
            $total_deposit = (int)$row['total_deposit'];
            $monthly_deposit = (int)$row['monthly_deposit'];
        ?>
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
            درباره صندوق پس انداز واقف
        </h3>
        <br>
        <div style="
                    position: relative;
                    ">
            <div style="
                        
                        margin:8% 10% 0 10%;
                        border: 5px solid;
                        border-radius:8px;
                        padding:25px;
                        box-shadow: 0 4px 23px 5px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(0, 0, 0, 0.15);
                        text-align:right;
                        font-size:1.5em;
                        color:white;
                        background-color: rgba(0, 0, 0, 0.5);
                        height:auto;
                        width:auto;">
                قدم اول: براي انجام محاسبات هر ماه بايد بعد از مشخص شدن مبلغ سود بانکي و موجود
                ي حساب بانکي اقدام گردد<br> اين موارد حدودا در پنجم هر ماه مشخص ميگردند
                بنابراين براي مثال براي انجام محاسبات ماه ششم ، بايد حدودا روز<br> پنجم ماه ششم 
                اقدام به انجام محاسبات نمود<br>
                قدم دوم: قبل از  انجام محاسبات ابتدا بايد اعضاي اضافه شده به صندوق 
                پس از انجام محاسبات قبلي، ثبت گردند<br>
                قدم سوم: در صورتي که فردي قصد صفر کردن وام را دارد بايد سرعت باز پرداخت 
                را به اندازه اقساط باقي مانده تعيين<br> نمود و يا اگر فردي قصد تغيير سرعت باز پرداخت
                را داشت آن را در اين مرحله اعمال نمود<br>
                قدم چهارم: سپس وام هاي اعطا شده به افراد در صف انتظار افزوده گردد<br>
                قدم پنجم: سپس هزينه هاي صرف شده از صندوق بايد لحاظ شوند<br>
                قدم نهايي: در انتها با در دست داشتن مبلغ سود بانکي ماه پنجم 
                (که حدودا پنجم ماه بعدي مشخص ميشود) و  مبلغ کل <br>موجودي حساب صندوق
                اقدام به محاسبه و چاپ قبوض ماه ششم نمود<br>
                
            </div>
        </div>

        <!-- ############################# -->
        <!-- Member Information -->   
        <div id="info" class="status"></div>
    <!-- ############################# -->
    <!-- JavaScript Code -->
    <script src="general_javascript.js"></script>
    <script type="text/javascript">
                 
    /*     window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        }); */
    </script>
</body>
</html>
