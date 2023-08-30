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
        <script src="jquery-3.6.4.min.js">
        </script>       
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
            
            $sql = "SELECT * FROM expense_list ORDER BY expense_number DESC LIMIT 1" ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $last_expense_num = (int)$row['expense_number'] + 1;
            
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
            هزينه هاي صندوق
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
                    onkeyup="click_source='expenses';search_member()" 
                    onblur="search_box_lose_focus()"
                    onfocus="search_box_get_focus()" 
                    value= "جست و جوي اعضا" 
                    maxlength="30">
            </input>
        
            <button id="add_expense_form_button" 
                    onClick= "add_expense()">
              افزودن هزينه
            </button>
            
            <button id="delete_expense_form_button" 
                onClick= "delete_expense();show_expense(this.value + this.name)">
              حذف هزينه
            </button>

        </div>
        <!-- ############################# -->
        <!-- New Expense Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="add_expense_form" 
                    style="display:none;">
                <label for="new_expense_num">
                    شماره هزينه
                </label>
                <input  type="text" 
                        id="new_expense_num" 
                        name="new_expense_num" 
                        required
                        value = "<?php echo $last_expense_num; ?>"
                        onkeypress="num_validate(event)">
                </input>

                <label for="new_expense_name">
                    عنوان هزينه
                </label>
                <input  type="text" 
                        id="new_expense_name" 
                        name="new_expense_name" 
                        required
                        onkeypress="persian_text_validate(event)">
                </input>
                
                
                <label for="new_expense_price">
                    مبلغ هزينه
                </label>
                <input  type="text" 
                        id="new_expense_price" 
                        name="new_expense_price" 
                        required
                        onkeypress="num_validate(event)">
                </input>
                
                <label for="new_expense_ydate">
                    تاريخ (سال)
                </label>
                <select id="new_expense_ydate" 
                        name="new_expense_ydate">
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
                
                <label for="new_expense_mdate">
                    تاريخ (ماه)
                </label>
                <select id="new_expense_mdate" 
                        name="new_expense_mdate">
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
                
                <label for="new_expense_ddate">
                    تاريخ (روز)
                </label>
                <select id="new_expense_ddate" 
                        name="new_expense_ddate">
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

                
                <label for="new_expense_detail">
                    توضيحات
                </label>
                <input  type="text" 
                        id="new_expense_detail" 
                        name="new_expense_detail" 
                        onkeypress="persian_text_validate(event)">
                </input>
                
                <button name="expense_add_submit"  
                        style="float: right;" 
                        type="submit" 
                        onclick="return confirm('آيا از افزودن هزينه جديد مطمئن هستيد ؟')">
                 ثبت اطلاعات هزينه جديد
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Delete Expense Input -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="delete_expense_form" 
                    style="display:none;"
                    >
                <label for="delete_expense_num">
                    شماره هزينه
                </label>
                <select id="delete_expense_num" 
                        name="/delete_expense_num"
                        onchange="show_expense(this.value + this.name)">
                    <?php
                        $sql = "SELECT expense_number FROM expense_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option>";
                                echo $row['expense_number'];
                                echo "</option>";
                            }
                        } else {
                            echo "";
                        }
                    ?>
                </select> 
                
                <!--These Two Elements Are Not In Right Sequence Becaus Of Persian Language-->

                <input  type="checkbox" 
                        id="delete_all_expense_check" 
                        name="delete_all_expense_check" >
                </input>
                <label for="delete_all_expense_check">
                  حذف همه هزينه ها
                </label>
                
                <button name="expense_delete_submit"  
                        style="float: right;" 
                        type="submit" 
                        onclick="return confirm('آيا از حذف هزينه مطمئن هستيد ؟')">
                    پاک کردن هزينه
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Member Information -->        
        <div id="info" class="status"></div>
        <!-- ############################# -->
        <!-- Exoenses List Table-->
        <div class="table_div">

            <div class="table_caption">
                ليست هزينه هاي صندوق
            </div>
            
            <div class="dashboard">
                <button  style="width:100%" onClick= "click_source='expenses';tableToCSV()">
                ذخیره لیست   
                </button>
            </div>   
            
            <table  id="expense_table">
                <thead>
                    <th onclick="click_source='expenses';sort_table(0)">شماره هزينه</th>
                    <th onclick="click_source='expenses';sort_table(1)">عنوان هزينه</th>
                    <th onclick="click_source='expenses';sort_table(2)">مبلغ هزينه</th>
                    <th onclick="click_source='expenses';sort_table(3)">تاريخ (سال)</th>
                    <th onclick="click_source='expenses';sort_table(4)">تاريخ (ماه)</</th>
                    <th onclick="click_source='expenses';sort_table(5)">تاريخ (روز)</th>
                    <th onclick="click_source='expenses';sort_table(6)">مجموع هزينه </th>
                    <th onclick="click_source='expenses';sort_table(7)">توضيحات</th>
                </thead>
                <tbody id="expense_table_body">
                    <?php
                        $sql = "SELECT * FROM expense_list";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>". $row["expense_number"]. "</td>".
                                    "<td>". $row["expense_name"]. "</td>".
                                    "<td>". number_format($row["expense_price"]). "</td>".
                                    "<td>". $row["ydate"]. "</td>".
                                    "<td>". $row["mdate"]. "</td>".
                                    "<td>". $row["ddate"]. "</td>".
                                    "<td>". number_format($row["total_expense"]). "</td>".
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
        function add_expense(){
            var form_display = document.getElementById('add_expense_form').style;                                          
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('add_expense_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('delete_expense_form').style.display = 'none'
                    document.getElementById('delete_expense_form_button').innerHTML = 'حذف هزينه';
                    document.getElementById('new_expense_num').value  = '<?php echo $last_expense_num;?>';
                    document.getElementById('new_expense_name').focus();
                    document.getElementById('new_expense_price').value  = '';
                    document.getElementById('new_expense_detail').value  = '';
                    document.getElementById("info").innerHTML = ""
                }
                else{
                    document.getElementById('add_expense_form_button').innerHTML = 'افزودن هزينه';
                    form_display.display = 'none';
                }
        }
        // Delete Expense Button Clicked
        function delete_expense(){
            var select = document.getElementById('delete_expense_num');
            var form_display = document.getElementById('delete_expense_form').style;                                      
                if (form_display.display === 'none'){
                    form_display.display = 'inline-block';
                    document.getElementById('delete_expense_form_button').innerHTML = 'بستن فرم';
                    document.getElementById('add_expense_form').style.display = 'none'
                    document.getElementById('add_expense_form_button').innerHTML = 'افزودن هزينه';
                    show_expense(select.value + select.name);
                }
                else{
                    document.getElementById('delete_expense_form_button').innerHTML = 'حذف هزينه';
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
