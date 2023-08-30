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
            $_SESSION['new_member_monthly_deposit'] = $monthly_deposit;
            
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
            ثبت اطلاعات اعضا
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
                    onkeyup="click_source='members';search_member()" 
                    onblur="search_box_lose_focus()"
                    onfocus="search_box_get_focus()" 
                    value= "جست و جوي اعضا" 
                    maxlength="30">
            </input>
        
            <button id="add_member_form_button" 
                    onClick= "add_member()">
              افزودن عضو
            </button>
            
            <button id="delete_member_form_button" 
                onClick= "delete_member()">
              حذف عضو
            </button>
            
            <button id="return_pace_form_button"
                    onClick= "return_pace_change()">
                تغيير سرعت بازپرداخت اعضا
            </button>
        
            <button id="monthly_deposit_form_button"
                    onClick= "monthly_deposit_change()">
                تغيير مبلغ پس انداز ماهيانه
            </button>


        </div>
        <!-- ############################# -->
        <!-- New Member Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="new_member_form" 
                    style="display:none;">
                <label for="new_member_num">
                    شماره عضويت
                </label>
                <input  type="text" 
                        id="new_member_num" 
                        name="new_member_num" 
                        minlength=6
                        maxlength=6
                        required
                        onkeypress="num_validate(event)">
                </input>
                <label for="new_member_prefix">
                    پيشوند
                </label>
                <select id="new_member_prefix" 
                        name="new_member_prefix">
                    <option selected>جناب آقاي</option>
                    <option>سرکار خانم</option>
                </select>
                <label for="new_member_lname">
                    نام خانوادگي
                </label>
                <input  type="text" 
                        id="new_member_lname" 
                        name="new_member_lname" 
                        required
                        onkeypress="persian_text_validate(event)">
                </input>
                <label for="new_member_fname">
                    نام
                </label>
                <input  type="text" 
                        id="new_member_fname" 
                        name="new_member_fname" 
                        required
                        onkeypress="persian_text_validate(event)">
                </input>
                <label for="new_member_id">
                    کد ملي
                </label>
                <input  type="text" 
                        id="new_member_id" 
                        name="new_member_id" 
                        minlength=10
                        maxlength=10
                        onkeypress="num_validate(event)">
                </input>                
                <label for="new_member_deposit">
                    مبلغ پس انداز
                </label>
                <input  type="text" 
                        id="new_member_deposit" 
                        name="new_member_deposit" 
                        readonly="true"
                        onkeypress="num_validate(event)">
                </input>  
                <label for="new_member_detail">
                    توضيحات
                </label>
                <input  type="text" 
                        id="new_member_detail" 
                        name="new_member_detail" 
                        onkeypress="persian_text_validate(event)">
                </input>                 
                <button name="member_add_submit" 
                        style="float: right;" 
                        type="submit" 
                        onclick="return confirm('آيا از افزودن عضو جديد مطمئن هستيد ؟')">
                 ثبت اطلاعات عضو جديد
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Member Delete Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"  
                    id="delete_member_form" 
                    style="display:none;">
                <label for="delete_member_num">
                    شماره عضويت
                </label>
                <select id="delete_member_num" 
                        name="/delete_member_num"
                        onchange="show_member(this.value + this.name)">
                    <?php
                        $sql = "SELECT member_number FROM member_list";
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
                        id="delete_all_member_check" 
                        name="delete_all_member_check" >
                </input>
                
                <label for="delete_all_member_check">
                  حذف همه اعضا
                </label> 
                
                <button name="member_delete_submit"  
                        style="float: right;" 
                        type="submit"
                        onclick="return confirm('آيا از حذف عضو مطمئن هستيد ؟')">
                 پاک کردن عضو
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Return Pace Change -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"  
                    id="return_pace_form" 
                    style="display:none;">
                <label for="return_pace_member_num">
                    شماره عضويت
                </label>
                <select id="return_pace_member_num" 
                        name="/return_pace_member_num"
                        onload="show_member(this.value + this.name)"
                        onchange="show_member(this.value + this.name)">
                    <?php
                        $sql = "SELECT member_number FROM member_list";
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
                <select id="return_pace" 
                        name="return_pace">
                    <?php
                        for ($x = 0; $x <= 24; $x++) {
                            echo "<option>";
                            echo $x;
                            echo "</option>"; 
                        }
                    ?>
                </select>

                <label for="return_pace">
                    سرعت بازپرداخت اعضا
                </label>
                
                <button name="return_pace_submit"
                        style="float: right;" 
                        type="submit"
                        onclick= "return confirm('آيا از تغيير سرعت بازپرداخت مطمئن هستيد ؟')">
                 ثبت تغييرات
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Monthly Deposit Change -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="monthly_deposit_form" 
                    style="display:none;">
                
                <label for="monthly_deposit">
                    مبلغ پس انداز ماهيانه هر عضو
                </label>
                <input  type="text" 
                        id="monthly_deposit" 
                        name="monthly_deposit" 
                        onkeypress="num_validate(event)">
                </input>
                
                <button name="monthly_deposit_submit"
                        style="float: right;" 
                        type="submit"
                        onclick= "return confirm('آيا از تغيير مبلغ پس انداز ماهانه مطمئن هستيد ؟')">
                 ثبت تغييرات
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Member Information -->   
        <div id="info" class="status"></div>
        <!-- ############################# -->
        <!-- Members List Table-->
        <div class="table_div">
            
            <div class="table_caption">
                ليست اعضاي صندوق
            </div>
            
            <div class="dashboard">
                <button  style="width:100%" onClick= "click_source='members';tableToCSV()">
                ذخیره لیست   
                </button>
            </div>            

            <table id="member_table">
                <thead>
                    <th onclick="click_source='members';sort_table(0)">شماره عضويت</th>
                    <th onclick="click_source='members';sort_table(1)">پيشوند</th>
                    <th onclick="click_source='members';sort_table(2)">نام خانوادگي</th>
                    <th onclick="click_source='members';sort_table(3)">نام</th>
                    <th onclick="click_source='members';sort_table(4)">کد ملي</th>
                    <th onclick="click_source='members';sort_table(5)">مبلغ پس انداز</th>
                    <th onclick="click_source='members';sort_table(6)">پس انداز ماهيانه</th>
                    <th onclick="click_source='members';sort_table(7)">وضعيت وام</th>
                    <th onclick="click_source='members';sort_table(8)">وضعيت قرعه کشي</th>
                    <th onclick="click_source='members';sort_table(9)">سرعت بازپرداخت</th>
                    <th onclick="click_source='members';sort_table(10)">توضيحات</th>
                </thead>
                <tbody id="member_table_body">
                    <?php
                        $sql = "SELECT * FROM member_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>". $row["member_number"]. "</td>".
                                    "<td>". $row["prefix"]. "</td>".
                                    "<td>". $row["last_name"]. "</td>".
                                    "<td>". $row["first_name"]. "</td>".
                                    "<td>". $row["id_num"]. "</td>".
                                    "<td>". number_format($row["total_deposit"]). "</td>".
                                    "<td>". number_format($row["monthly_deposit"]). "</td>".
                                    "<td>". $row["loan_status"]. "</td>".
                                    "<td>". $row["win_status"]. "</td>".
                                    "<td>". $row["return_pace"]. "</td>".
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
        // Add Member Button Clicked
        function add_member(){
            var form_display = document.getElementById('new_member_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('add_member_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('delete_member_form').style.display = 'none'
                    document.getElementById('delete_member_form_button').innerHTML = 'حذف عضو';
                    document.getElementById('return_pace_form').style.display = 'none'
                    document.getElementById('return_pace_form_button').innerHTML = 'تغيير سرعت بازپرداخت اعضا';
                    document.getElementById('monthly_deposit_form').style.display = 'none'
                    document.getElementById('monthly_deposit_form_button').innerHTML = 'تغيير مبلغ پس انداز ماهيانه';
                    document.getElementById('new_member_num').value  = '<?php echo $last_member_num;?>';
                    document.getElementById('new_member_lname').focus();
                    document.getElementById('new_member_fname').value  = '';
                    document.getElementById('new_member_id').value  = '';
                    document.getElementById('new_member_deposit').value  = '<?php echo $total_deposit;?>';
                    document.getElementById('new_member_detail').value  = '';
                    document.getElementById("info").innerHTML = ""
                }
                else{
                    document.getElementById('add_member_form_button').innerHTML = 'افزودن عضو';
                    form_display.display = 'none';
                }
        }
        // Delete Member Button Clicked
        function delete_member(){
            var select = document.getElementById('delete_member_num');
            var form_display = document.getElementById('delete_member_form').style;                                      
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('delete_member_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('new_member_form').style.display = 'none'
                    document.getElementById('add_member_form_button').innerHTML = 'افزودن عضو';
                    document.getElementById('return_pace_form').style.display = 'none'
                    document.getElementById('return_pace_form_button').innerHTML = 'تغيير سرعت بازپرداخت اعضا';
                    document.getElementById('monthly_deposit_form').style.display = 'none'
                    document.getElementById('monthly_deposit_form_button').innerHTML = 'تغيير مبلغ پس انداز ماهيانه';                
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('delete_member_form_button').innerHTML = 'حذف عضو';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
            // Retun Pce Change Button Clicked
        function return_pace_change(){
            var select = document.getElementById('return_pace_member_num');
            var form_display = document.getElementById('return_pace_form').style;                                      
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('return_pace_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('delete_member_form').style.display = 'none'
                    document.getElementById('delete_member_form_button').innerHTML = 'حذف عضو';
                    document.getElementById('new_member_form').style.display = 'none'
                    document.getElementById('add_member_form_button').innerHTML = 'افزودن عضو';                        
                    document.getElementById('monthly_deposit_form').style.display = 'none'
                    document.getElementById('monthly_deposit_form_button').innerHTML = 'تغيير مبلغ پس انداز ماهيانه';                        
                    document.getElementById('return_pace').value  = '1';
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('return_pace_form_button').innerHTML = 'تغيير سرعت بازپرداخت اعضا';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
        // Monthly Deposit Change Button Clicked
        function monthly_deposit_change(){
            var form_display = document.getElementById('monthly_deposit_form').style;                                      
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('monthly_deposit_form_button').innerHTML = 'بستن فرم';
		            document.getElementById('delete_member_form').style.display = 'none'
                    document.getElementById('delete_member_form_button').innerHTML = 'حذف عضو';
                    document.getElementById('new_member_form').style.display = 'none'
                    document.getElementById('add_member_form_button').innerHTML = 'افزودن عضو';          
                    document.getElementById('return_pace_form').style.display = 'none'
                    document.getElementById('return_pace_form_button').innerHTML = 'تغيير سرعت بازپرداخت اعضا';                    
                    document.getElementById('monthly_deposit').value  = '<?php echo $monthly_deposit;?>';
                    document.getElementById("info").innerHTML = ""
                }
                else{
                    document.getElementById('monthly_deposit_form_button').innerHTML = 'تغيير مبلغ پس انداز ماهيانه';
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
