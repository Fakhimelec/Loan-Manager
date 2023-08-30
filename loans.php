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
            
            $sql = "SELECT * FROM loan_list ORDER BY loan_number DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $last_loan_num = (int)$row['loan_number'] + 1;
            $last_loan_price = $row['loan_price']
            
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
            وام هاي دريافتي اعضا
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
                    onkeyup="click_source='loans';search_member()" 
                    onblur="search_box_lose_focus()"
                    onfocus="search_box_get_focus()" 
                    value= "جست و جوي اعضا" 
                    maxlength="30">
            </input>
        
            <button id="add_loan_form_button" 
                    onClick= "add_loan()">
              افزودن وام
            </button>
            
            <button id="delete_loan_form_button" 
                onClick= "delete_loan()">
              حذف وام
            </button>

        </div>
        <!-- ############################# -->
        <!-- New Loan Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="add_loan_form" 
                    style="display:none;">
                <label for="loan_member_num">
                    شماره عضويت
                </label>
                <select id="loan_member_num" 
                        name="/loan_member_num"
                        onchange="show_member(this.value + this.name)">
                    <?php
                        $sql = "SELECT member_number FROM member_list WHERE loan_status=0";
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
                
                <label for="new_loan_num">
                    شماره وام
                </label>
                <input  type="text" 
                        id="new_loan_num" 
                        name="new_loan_num" 
                        minlength=7
                        maxlength=7
                        required
                        value = "<?php echo $last_loan_num; ?>"
                        onkeypress="num_validate(event)">
                </input>
                
                <label for="new_loan_ydate">
                    تاريخ (سال)
                </label>
                <select id="new_loan_ydate" 
                        name="new_loan_ydate">
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
                
                <label for="new_loan_mdate">
                    تاريخ (ماه)
                </label>
                <select id="new_loan_mdate" 
                        name="new_loan_mdate">
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
                
                <label for="new_loan_ddate">
                    تاريخ (روز)
                </label>
                <select id="new_loan_ddate" 
                        name="new_loan_ddate">
                    <?php
                        $var = range(1, 1);
                        foreach($var as $value){
                            if($value == (int)$day){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                               
                            }
                        }
                    ?>
                </select>
                
                <label for="new_loan_price">
                    مبلغ وام
                </label>
                <input  type="text" 
                        id="new_loan_price" 
                        name="new_loan_price" 
                        required
                        value = <?php $last_loan_price ?>
                        onkeypress="num_validate(event)">
                </input>
                
                <label for="new_loan_debt_left">
                    مانده بدهي
                </label>
                <input  type="text" 
                        id="new_loan_debt_left" 
                        name="new_loan_debt_left" 
                        required
                        onkeypress="num_validate(event)"
                        onclick="document.getElementById('new_loan_debt_left').value = 
                                document.getElementById('new_loan_price').value"
                        onfocus="document.getElementById('new_loan_debt_left').value = 
                                document.getElementById('new_loan_price').value">
                </input>                
                
                <label for="new_loan_commision_rate">
                    درصد کارمزد (%)
                </label>
                <select id="new_loan_commision_rate" 
                        name="new_loan_commision_rate">
                    <?php
                        $var = range(1, 20);
                        echo "<option selected>"."0.5"."</option>";
                        echo "<option selected>"."0.6"."</option>";
                        echo "<option selected>"."0.7"."</option>";
                        echo "<option selected>"."0.8"."</option>";
                        echo "<option selected>"."0.9"."</option>";
                        foreach($var as $value){
                            if($value == 1){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                               
                            }
                        }
                    ?>
                </select>  
                
                <label for="new_loan_installment_count">
                    تعداد اقساط (ماه)
                </label>
                <select id="new_loan_installment_count" 
                        name="new_loan_installment_count">
                    <?php
                        $var = range(20, 30);
                        
                        foreach($var as $value){
                            if($value == 25){
                                echo "<option selected>".$value."</option>";
                            }else{
                                echo "<option>".$value."</option>";                               
                            }
                        }
                    ?>
                </select>  
                <label for="new_loan_detail">
                    توضيحات
                </label>
                <input  type="text" 
                        id="new_loan_detail" 
                        name="new_loan_detail" 
                        onkeypress="persian_text_validate(event)">
                </input>
                
                <button name="loan_add_submit"  
                        style="float: right;" 
                        type="submit" 
                        onclick="return confirm('آيا از افزودن وام جديد مطمئن هستيد ؟')">
                 ثبت اطلاعات وام جديد
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Delete Loan Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="delete_loan_form" 
                    style="display:none;"
                    >
                <label for="delete_loan_num">
                    شماره وام
                </label>
                <select id="delete_loan_num" 
                        name="/delete_loan_num"
                        onchange="show_loan(this.value + this.name)">
                    <?php
                        $sql = "SELECT loan_number FROM loan_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option>";
                                echo $row['loan_number'];
                                echo "</option>";
                            }
                        } else {
                            echo "";
                        }
                    ?>
                </select> 
                
                <!--These Two Elements Are Not In Right Sequence Becaus Of Persian Language-->

                <input  type="checkbox" 
                        id="delete_all_loan_check" 
                        name="delete_all_loan_check" >
                </input>

                <label for="delete_all_loan_check">
                  حذف همه وام ها
                </label>
                
                <button name="loan_delete_submit"   
                        style="float: right;" 
                        type="submit" 
                        onclick="return confirm('آيا از حذف وام مطمئن هستيد ؟')">
                    حذف وام
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Member Information -->        
        <div id="info" class="status"></div>
        <!-- ############################# -->
        <!-- Loans List Table-->
        <div class="table_div">

            <div class="table_caption">
                ليست وامهاي دريافتي
            </div>
            
            <div class="dashboard">
                <button  style="width:100%" onClick= "click_source='loans';tableToCSV()">
                ذخیره لیست   
                </button>
            </div>            
            
            <table id="loan_table">
                <thead>
                    <th onclick="click_source='loans';sort_table(0)">شماره عضويت</th>
                    <th onclick="click_source='loans';sort_table(1)">نام خانوادگي</th>
                    <th onclick="click_source='loans';sort_table(2)">نام</th>
                    <th onclick="click_source='loans';sort_table(3)">شماره وام</th>
                    <th onclick="click_source='loans';sort_table(4)">تاريخ (سال)</th>
                    <th onclick="click_source='loans';sort_table(5)">تاريخ (ماه)</</th>
                    <th onclick="click_source='loans';sort_table(6)">تاريخ (روز)</th>
                    <th onclick="click_source='loans';sort_table(7)">مبلغ وام</th>
                    <th onclick="click_source='loans';sort_table(8)">تعداد اقساط</th>
                    <th onclick="click_source='loans';sort_table(9)">مبلغ اقساط</th>
                    <th onclick="click_source='loans';sort_table(10)">درصد کارمزد</th>
                    <th onclick="click_source='loans';sort_table(11)">مبلغ کارمزد</th>
                    <th onclick="click_source='loans';sort_table(12)">مانده بدهي</th>
                    <th onclick="click_source='loans';sort_table(13)">اقساط باقي مانده</th>
                    <th onclick="click_source='loans';sort_table(14)">توضيحات</th>
                </thead>
                <tbody id="loan_table_body">
                    <?php
                        $sql = "SELECT * FROM loan_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>". $row["member_number"]. "</td>".
                                    "<td>". $row["last_name"]. "</td>".
                                    "<td>". $row["first_name"]. "</td>".
                                    "<td>". $row["loan_number"]. "</td>".
                                    "<td>". $row["ydate"]. "</td>".
                                    "<td>". $row["mdate"]. "</td>".
                                    "<td>". $row["ddate"]. "</td>".
                                    "<td>". number_format($row["loan_price"]). "</td>".
                                    "<td>". $row["installment_count"]. "</td>".
                                    "<td>". number_format($row["installment_amount"]). "</td>".
                                    "<td>". $row["commision_rate"]. "</td>".
                                    "<td>". number_format($row["commision_amount"]). "</td>".
                                    "<td>". number_format($row["debt_left"]). "</td>".
                                    "<td>". $row["installment_left"]. "</td>".
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
        // Add Loan Button Clicked
        function add_loan(){
            var select = document.getElementById('loan_member_num');
            var form_display = document.getElementById('add_loan_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('add_loan_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('delete_loan_form').style.display = 'none'
                    document.getElementById('delete_loan_form_button').innerHTML = 'حذف وام';
                    document.getElementById('new_loan_num').value  = '<?php echo $last_loan_num;?>';
                    document.getElementById('new_loan_price').value  = '';
                    document.getElementById('new_loan_price').focus();
                    document.getElementById('new_loan_debt_left').value  = '';
                    document.getElementById('new_loan_detail').value  = '';
                    show_member(select.value + select.name);
                }
                else{
                    document.getElementById('add_loan_form_button').innerHTML = 'افزودن وام';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
        // Delete Loan Button Clicked
        function delete_loan(){
            var select = document.getElementById('delete_loan_num');
            var form_display = document.getElementById('delete_loan_form').style;                                      
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('delete_loan_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('add_loan_form').style.display = 'none'
                    document.getElementById('add_loan_form_button').innerHTML = 'افزودن وام';
                    show_loan(select.value + select.name);
                }
                else{
                    document.getElementById('delete_loan_form_button').innerHTML = 'حذف وام';
                    document.getElementById("info").innerHTML = ""
                    form_display.display = 'none';
                }
        }
        
 
/*        const form = document.getElementById('delete_loan_form');
        
        form.addEventListener('submit', (event) => {
          event.preventDefault();
        });*/

    /*     window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        }); */
    </script>
</body>
</html>
