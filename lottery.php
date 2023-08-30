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
            
            $sql = "SELECT * FROM member_list WHERE win_status=0";
            $result = $conn->query($sql);
            $array = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    array_push($array, $row['member_number']);
                };
                
            } else {
                echo "";
            }
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
            قرعه کشي
        </h3>
        <br>
        <div id= "status" class="status">
            <?php
                if(isset($_SESSION["message"])){
                    echo $_SESSION["message"];
                }
            ?>
        </div>
        <br>
        <!-- ############################# -->
        <!-- Search Bar And Buttons -->
        <div class="dashboard" style="width:auto;">
            
            <input  type="search"  
                    id="search_box_input"
                    onkeyup="click_source='lottery';search_member()" 
                    onblur="search_box_lose_focus()"
                    onfocus="search_box_get_focus()" 
                    value= "جست و جوي اعضا" 
                    maxlength="30">
            </input>
            
            <button id="manual_winner_form_button" 
                onClick= "add_manual_winner()">
                انتخاب دستي
            </button>
        
            <button id="auto_winner_form_button" 
                    onClick= "add_auto_winner()">
                انتخاب اتوماتيک
            </button>
            
            <button id="delete_winner_form_button"
                    onClick= "delete_winner()">
                 پاک کردن برندگان 
            </button>
            
            
        </div>
        <!-- ############################# -->
        <!-- Manual Winner Add Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="manual_winner_form" 
                    style="display:none;">
                <label for="manual_member_num">
                    شماره عضويت
                </label>
                <select id="manual_member_num" 
                        name="/manual_member_num"
                        onchange="show_member(this.value + this.name)">
                    <?php
                        $sql = "SELECT member_number FROM member_list WHERE win_status=0";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option>";
                                echo $row['member_number'];
                                echo "</option>";
                            }
                        } else {
                            echo "";
                        }
                    ?>
                </select>

                <label for="new_winner_ydate">
                    تاريخ (سال)
                </label>
                <select id="new_winner_ydate" 
                        name="new_winner_ydate">
                    <?php
                        $var = range(1400, 1500);
                        foreach($var as $value){
                            if($value == (int)$year){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                                
                            }
                        }
                    ?>
                </select>
                
                <label for="new_winner_mdate">
                    تاريخ (ماه)
                </label>
                <select id="new_winner_mdate" 
                        name="new_winner_mdate">
                    <?php
                        $var = range(1, 12);
                        foreach($var as $value){
                            if($value == (int)$month){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                                
                            }
                        }
                    ?>
                </select>
                
                <label for="new_winner_ddate">
                    تاريخ (روز)
                </label>
                <select id="new_winner_ddate" 
                        name="new_winner_ddate">
                    <?php
                        $var = range(1, 31);
                        foreach($var as $value){
                            if($value == (int)$day){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                               
                            }
                        }
                    ?>
                </select>

                <label for="new_manual_winner_detail">
                    توضيحات (جايزه ي قرعه کشي)
                </label>
                <input  type="text" 
                        id="new_manual_winner_detail" 
                        name="new_manual_winner_detail" 
                        required
                        onkeypress="persian_text_validate(event)">
                </input>
                
                <button name="manual_winner_add_submit"
                        style="float: right;" 
                        type="submit">
                    ثبت برنده جديد
                </button>
            </form>
        </div>

        <!-- ############################# -->
        <!-- Automatic Winner Add Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="auto_winner_form" 
                    style="display:none;">
                <label for="auto_member_num">
                    شماره عضويت
                </label>
                <select id="auto_member_num" 
                        name="/auto_member_num">
                    <?php
                        echo "<option>";
                        echo $array[array_rand($array)];
                        echo "</option>";
                    ?>
                </select>
                <button id="new_pick"
                    onClick= "new_random_pick()">
                    .
                </button>
                
                <label for="new_winner_ydate">
                    تاريخ (سال)
                </label>
                <select id="new_winner_ydate" 
                        name="new_winner_ydate">
                    <?php
                        $var = range(1400, 1500);
                        foreach($var as $value){
                            if($value == (int)$year){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                                
                            }
                        }
                    ?>
                </select>
                
                <label for="new_winner_mdate">
                    تاريخ (ماه)
                </label>
                <select id="new_winner_mdate" 
                        name="new_winner_mdate">
                    <?php
                        $var = range(1, 12);
                        foreach($var as $value){
                            if($value == (int)$month){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                                
                            }
                        }
                    ?>
                </select>
                
                <label for="new_winner_ddate">
                    تاريخ (روز)
                </label>
                <select id="new_winner_ddate" 
                        name="new_winner_ddate">
                    <?php
                        $var = range(1, 31);
                        foreach($var as $value){
                            if($value == (int)$day){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                               
                            }
                        }
                    ?>
                </select>

                <label for="new_auto_winner_detail">
                    توضيحات (جايزه ي قرعه کشي)
                </label>
                <input  type="text" 
                        id="new_auto_winner_detail" 
                        name="new_auto_winner_detail" 
                        required
                        onkeypress="persian_text_validate(event)">
                </input>
                
                <button name="auto_winner_add_submit"
                        style="float: right;" 
                        type="submit">
                    ثبت برنده جديد      
                </button>
            </form>
        </div>
        
        <!-- ############################# -->
        <!-- Delete Winner Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="delete_winner_form" 
                    style="display:none;">
                <label for="delete_winner_num">
                    شماره عضويت
                </label>
                <select id="delete_winner_num" 
                        name="/delete_winner_num"
                        onchange="show_member(this.value + this.name)">
                    <?php
                        $sql = "SELECT member_number FROM member_list WHERE win_status=1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option>";
                                echo $row['member_number'];
                                echo "</option>";
                            }
                        } else {
                            echo "";
                        }
                    ?>
                </select>
                
                <!--These Two Elements Are Not In Right Sequence Becaus Of Persian Language-->

                <input  type="checkbox" 
                        id="delete_all_winner_check" 
                        name="delete_all_winner_check" >
                </input>
                <label for="delete_all_winner_check">
                    حذف همه برندگان
                </label>
                
                <button name="winner_delete_submit"
                        style="float: right;" 
                        type="submit">
                 پاک کردن برنده
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Member Information -->        
        <div id="info" class="status"></div>
        <!-- ############################# -->
        <!-- Lottery Winner List Table-->
        <div class="table_div">

            <div class="table_caption">
                ليست برندگان قرعه کشي
            </div>
            
            <div class="dashboard">
                <button  style="width:100%" onClick= "click_source='lottery';tableToCSV()">
                ذخیره لیست   
                </button>
            </div>   
            
            <table id="lottery_table">
                <thead>
                    <th onclick="click_source='lottery';sort_table(0)">شماره عضويت</th>
                    <th onclick="click_source='lottery';sort_table(1)">نام خانوادگي</th>
                    <th onclick="click_source='lottery';sort_table(2)">نام</th>
                    <th onclick="click_source='lottery';sort_table(3)">تاريخ (سال)</th>
                    <th onclick="click_source='lottery';sort_table(4)">تاريخ (ماه)</</th>
                    <th onclick="click_source='lottery';sort_table(5)">تاريخ (روز)</th>
                    <th onclick="click_source='lottery';sort_table(6)">توضيحات</th>
                </thead>
                <tbody id="lottery_table_body">
                    <?php
                        $sql = "SELECT * FROM lottery_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>". $row["member_number"]. "</td>".
                                    "<td>". $row["last_name"]. "</td>".
                                    "<td>". $row["first_name"]. "</td>".
                                    "<td>". $row["ydate"]. "</td>".
                                    "<td>". $row["mdate"]. "</td>".
                                    "<td>". $row["ddate"]. "</td>".
                                    "<td>". $row["details"]. "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "";
                        }
                        $conn->close();
                    ?>                    
                </tbody>
            </table>
        </div>
    <!-- ############################# -->
    <!-- JavaScript Code -->
    <script src="general_javascript.js"></script>
    <script type="text/javascript">
        // Add Manual Winner Button Clicked
        function add_manual_winner(){
            var select = document.getElementById('manual_member_num');
            var form_display = document.getElementById('manual_winner_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('manual_winner_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('auto_winner_form').style.display = 'none'
                    document.getElementById('auto_winner_form_button').innerHTML = 'انتخاب اتوماتيک';
                    document.getElementById('delete_winner_form').style.display = 'none'
                    document.getElementById('delete_winner_form_button').innerHTML = 'پاک کردن برندگان';
                    document.getElementById('new_manual_winner_detail').value  = '';
                    document.getElementById('new_manual_winner_detail').focus();
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('manual_winner_form_button').innerHTML = 'انتخاب دستي';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
        // Add Automatic Winner Button Clicked
        function add_auto_winner(){
            var select = document.getElementById('auto_member_num');
            var form_display = document.getElementById('auto_winner_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('auto_winner_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('manual_winner_form').style.display = 'none'
                    document.getElementById('manual_winner_form_button').innerHTML = 'انتخاب دستي';
                    document.getElementById('delete_winner_form').style.display = 'none'
                    document.getElementById('delete_winner_form_button').innerHTML = 'پاک کردن برندگان';
                    document.getElementById('new_auto_winner_detail').value  = '';
                    document.getElementById('new_auto_winner_detail').focus();
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('auto_winner_form_button').innerHTML = 'انتخاب اتوماتيک';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
        // Delete Winner Button Clicked
        function delete_winner(){
            var select = document.getElementById('delete_winner_num');
            var form_display = document.getElementById('delete_winner_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('delete_winner_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('manual_winner_form').style.display = 'none'
                    document.getElementById('manual_winner_form_button').innerHTML = 'انتخاب دستي';
                    document.getElementById('auto_winner_form').style.display = 'none'
                    document.getElementById('auto_winner_form_button').innerHTML = 'انتخاب اتوماتيک';
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('delete_winner_form_button').innerHTML = 'پاک کردن برندگان';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }      
    /*     window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        }); */
    </script>
</body>
</html>
