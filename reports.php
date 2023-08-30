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
        <script>
        </script>       
    </head>
    
    <!-- ################################################################### -->
    <!-- ################################################################### -->
    <!-- Body Attributes -->
    <body>
        <!-- ############################# -->
        <!-- Including Time Variables And Connecting Database -->
        <?php
            # Include;

            require_once 'jdf.php';
            $now = explode('/', jdate('Y/m/d',time(),'','Asia/Tehran','en'));
            $year = $now[0];
            $month = $now[1];
            $day = $now[2];
            
            # MySQL Database Connection
            require_once 'db_connect.php';           
            # Get The Total Number Of Members From member_list
            $sql = "SELECT COUNT(*) AS 'member_number' FROM member_list";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $member_count = $row['member_number'];
            # Get The Total And Monthly Deposit Values From member_list
            $sql = "SELECT * FROM member_list";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();  
            $total_deposit = $row['total_deposit'];
            $monthly_deposit = $row['monthly_deposit'];
            # Get The Number Of Members Waiting For Loan (loan_status = 0)
            $sql = "SELECT COUNT(*) AS 'member_number' FROM member_list 
                    WHERE loan_status = 0";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $loan_ready_member_count = $row['member_number'];
            
            // Get The Data Related To balance_list
            $sql = "SELECT * FROM balance_list";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();  
            $total_asset = $row['total_asset'];
            $total_liability = $row['total_liability'];
            $balance = $row['balance'];
            $bank_profit = $row['bank_profit'];
            $account_balance = $row['account_balance'];
            
            // Get The Data Related To expense_list
            $sql = "SELECT * FROM expense_list ORDER BY expense_number DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();  
            $total_expense = $row['total_expense'];
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
            گزارش هاي صندوق
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
        <!-- Command Buttons -->
        <div class="dashboard" style="width:auto;">        
        
            <button id="toggle_table_button" 
                onClick= "toggle_table()">
                نمايش ليست پرداخت ماه جاري
            </button>
            
            <button id="payment_calc_form_button" 
                    onClick= "payment_calc()">
                محاسبه پرداخت ماهيانه
            </button>
            
            <button id="bill_print_form_button" 
                onClick= "bill_print()">
                چاپ قبوض دوره
            </button>
            
            <button id="report_print_form_button" 
                onClick= "report_print()">
                گزارش ماهيانه صندوق  
            </button>

        </div>
        <!-- ############################# -->
        <!-- Payment Calculations -->
        <div class="dashboard">
            <form   method="post"
                    action="request_handler.php"
                    id="payment_calc_form" 
                    style="display:none;">
                
                <label for="payment_ydate">
                    تاريخ (سال)
                </label>
                <select id="payment_ydate" 
                        name="payment_ydate">
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
                
                <label for="payment_mdate">
                    تاريخ (ماه)
                </label>
                <select id="payment_mdate" 
                        name="payment_mdate">
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


                <label for="bank_profit">
                    مبلغ سود بانکي 
                </label>
                <input  type="text" 
                        id="bank_profit" 
                        name="bank_profit" 
                        required
                        onkeypress="num_validate(event)">
                </input>
                

                <label for="this_month_account_balance">
                     مبلغ کل موجودي صندوق
                </label>
                <input  type="text" 
                        id="this_month_account_balance" 
                        name="this_month_account_balance" 
                        required
                        onkeypress="num_validate(event)">
                </input>
                
                <button name="payment_calc_submit" 
                        style="float: right;" 
                        type="submit">
                    محاسبه ي جدول پرداخت
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Print Bills -->
        <div class="dashboard">
            <form   method="post"
                    action="print_handler.php"
                    id="bill_print_form" 
                    style="display:none;">
                
                <label for="print_bill_ydate">
                    تاريخ (سال)
                </label>
                <select id="print_bill_ydate" 
                        name="print_bill_ydate">
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
                

                <select id="print_bill_mdate" 
                        name="print_bill_mdate">
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
                
                <label for="print_bill_mdate">
                    تاريخ (ماه)
                </label>
                
                <button name="print_bills_submit" 
                        style="float: right;" 
                        type="submit"
                        onclick="return confirm('آيا از چاپ قبض هاي دوره مطمئن هستيد ؟')">
                    چاپ
                </button>
            </form>
        </div>
        <!-- ############################# -->
        <!-- Print Reports -->
        <div class="dashboard">
            <form   method="post"
                    action="print_handler.php"
                    id="report_print_form" 
                    style="display:none;">
                
                <button name="print_reports_submit" 
                        style="float: right;" 
                        type="submit"
                        onclick="return confirm('آيا از چاپ گزارش ماهانه مطمئن هستيد ؟')">
                    چاپ
                </button>
                
                
                <div style="height:auto">
                    <table id="report_table" name="report_data">
                        <tbody id="report_table_body">
                            <tr>
                                <td><input type='text' name='member_count' value='<?php echo $member_count?>' readonly></input></td>
                                <td>تعداد اعضاي صندوق</td>
                                
                                <td><input type='text' name='total_deposit' value='<?php echo $total_deposit?>' readonly></input></td>
                                <td>مبلغ پس انداز هر عضو</td>

                                <td><input type='text' name='monthly_deposit' value='<?php echo $monthly_deposit?>' readonly></input></td>
                                <td>مبلغ پس انداز ماهيانه هر عضو</td>
                            <tr>
                                <td><input type='text' name='total_asset' value='<?php echo $total_asset?>' readonly></input></td>
                                <td>کل مبلغ پس انداز صندوق</td>

                                <td><input type='text' name='total_liability' value='<?php echo $total_liability - $total_expense?>' readonly></input></td>
                                <td>مجموع مبالغ وام در اعضا</td>

                                <td><input type='text' name='loan_commision' value='<?php echo "-"?>' readonly></input></td>
                                <td>مجموع مبالغ کارمزد وام ها</td>
                            <tr>
                                <td><input type='text' name='total_expense' value='<?php echo $total_expense?>' readonly></input></td>
                                <td>مجموع مبلغ خرج صندوق</td>

                                <td><input type='text' name='bank_profit' value='<?php echo $bank_profit?>' readonly></input></td>
                                <td>سود بانکي</td>

                                <td><input type='text' name='account_balance' value='<?php echo $account_balance?>' readonly></input></td>
                                <td>موجودي کل حساب صندوق در حال حاضر</td>
                            <tr>
                                <td><input type='text' name='total_bills_income' value='<?php echo "-"?>' readonly></input></td>
                                <td>مجموع قبوض ماه جاري</td>

                                <td><input type='text' name='loan_ready_member_count' value='<?php echo $loan_ready_member_count?>' readonly></input></td>
                                <td>تعداد افراد در صف انتظار وام (صفر)</td>
                                
                                <td></td>
                                <td></td>
                        </tbody>
                    </table>
                </div>
            </form>
        </div> 
        

        <!-- Balance List Table-->
        <div    id="balance_list_table"
                style="display:inline;">
            
            <div class="table_caption">
                <?php echo "گردش حساب صندوق"." تا سال ".$year." ماه ".$month;?>
            </div>
            <div class="dashboard" >
                <button style="width:100%" onClick= "click_source='balance';tableToCSV()">
               ذخيره ليست   
                </button>
            </div>                
            <div class="table_div">
                <table id="balance_table">
                    <caption>
                        <?php
                            
                        ?>
                    </caption>
                    <thead>
                        <th onclick="click_source='reports';sort_table(0)">رديف</th>
                        <th onclick="click_source='reports';sort_table(1)">تاريخ (سال)</th>
                        <th onclick="click_source='reports';sort_table(2)">تاريخ (ماه)</</th>
                        <th onclick="click_source='reports';sort_table(3)">تاريخ (روز)</</th>
                        <th onclick="click_source='reports';sort_table(4)">مجموع پس انداز و درصد</th>
                        <th onclick="click_source='reports';sort_table(5)">مجموعه بدهي وام و هزينه هاي صندوق</th>
                        <th onclick="click_source='reports';sort_table(6)">طلب صندوق</th>
                        <th onclick="click_source='reports';sort_table(7)">سود بانکي</th>
                        <th onclick="click_source='reports';sort_table(8)">موجودي با احتساب سود بانکي</th>
                        <th onclick="click_source='reports';sort_table(9)">اختلاف موجودي</th>
                        <th onclick="click_source='reports';sort_table(10)">توضيحات</th>
                    </thead>
                    <tbody id="payment_table_body">
                        <?php
                            $sql = "SELECT * FROM balance_list";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>". $row["row"]. "</td>".
                                        "<td>". $row["ydate"]. "</td>".
                                        "<td>". $row["mdate"]. "</td>".
                                        "<td>". $row["ddate"]. "</td>".
                                        "<td>". number_format($row["total_asset"]). "</td>".
                                        "<td>". number_format($row["total_liability"]). "</td>".
                                        "<td>". number_format($row["balance"]). "</td>".
                                        "<td>". number_format($row["bank_profit"]). "</td>".
                                        "<td>". number_format($row["account_balance"]). "</td>".
                                        "<td>". number_format($row["balance_diff"]). "</td>".
                                        "<td>". $row["details"]. "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "";
                            }
                        ?>                    
                    </tbody>
                </table>
            </div>
        </div>
        <!-- ############################# -->
        <!-- Payments List Table-->
        <div    id="payment_list_table"
                style="display:none;">
            
            <div class="table_caption">
                <?php echo "ليست پرداخت ماهيانه اعضا"." درسال ".$year." ماه ".$month;?>
            </div>
            
            <div class="dashboard">
                <button  style="width:100%" onClick= "click_source='payments';tableToCSV()">
                ذخيره ليست   
                </button>
            </div>
            
            
            <table id="payment_table">
                <thead>
                    <th onclick="click_source='payments';sort_table(0)">شماره عضويت</th>
                    <th onclick="click_source='payments';sort_table(1)">نام خانوادگي</th>
                    <th onclick="click_source='payments';sort_table(2)">نام</th>
                    <th onclick="click_source='payments';sort_table(3)">شماره وام</th>
                    <th onclick="click_source='payments';sort_table(4)">تاريخ (سال)</th>
                    <th onclick="click_source='payments';sort_table(5)">تاريخ (ماه)</</th>
                    <th onclick="click_source='payments';sort_table(6)">مبلغ وام</th>
                    <th onclick="click_source='payments';sort_table(7)">پس انداز ماهيانه</th>
                    <th onclick="click_source='payments';sort_table(8)">مبلغ اقساط</th>
                    <th onclick="click_source='payments';sort_table(9)">جمع پرداختي</th>
                    <th onclick="click_source='payments';sort_table(10)">مانده بدهي</th>
                    <th onclick="click_source='payments';sort_table(11)">اقساط باقي مانده</th>
                    <th onclick="click_source='payments';sort_table(12)">مبلغ پس انداز</th>
                    <th onclick="click_source='payments';sort_table(13)">وضعيت قرعه کشي</th>
                    <th onclick="click_source='payments';sort_table(14)">سرعت بازپرداخت</th>
                </thead>
                <tbody id="payment_table_body">
                    <?php
                    
                    
                        
                        $list_name = "payment_list_".$year."_".(int)$month;
                        $sql = "SELECT * FROM $list_name";
                        try {
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
                                        "<td>". number_format($row["loan_price"]). "</td>".
                                        "<td>". number_format($row["monthly_deposit"]). "</td>".
                                        "<td>". number_format($row["installment_amount"]). "</td>".
                                        "<td>". number_format($row["total_pay"]). "</td>".
                                        "<td>". number_format($row["debt_left"]). "</td>".
                                        "<td>". $row["installment_left"]. "</td>".
                                        "<td>". number_format($row["total_deposit"]). "</td>".
                                        "<td>". $row["win_status"]. "</td>".
                                        "<td>". $row["return_pace"]. "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "";
                            }
                        }
                        catch(Exception $e){
                            echo "<tr>";
                            echo "<td colspan='15' style='text-align:center'>"."محاسبات اين ماه انجام نگرديده است". "</td>";
                            echo "</tr>";
                        }

                    ?>                    
                </tbody>
            </table>
        </div>
        
    <!-- ############################# -->
    <!-- JavaScript Code -->
    <script src="general_javascript.js"></script>
    <script type="text/javascript">
    
        // Toggle Reports/Payments Table Button Clicked
        function toggle_table(){
            var balance_display = document.getElementById('balance_list_table').style;
            var payment_display = document.getElementById('payment_list_table').style;
            if (balance_display.display === 'inline'){
                balance_display.display = 'none';
                payment_display.display = 'inline';
                document.getElementById('toggle_table_button').innerHTML = 'نمایش گردش حساب صندوق';
                document.getElementById('payment_calc_form').style.display = 'none';
                document.getElementById('payment_calc_form_button').innerHTML = 'محاسبه پرداخت ماهيانه';
                document.getElementById('bill_print_form').style.display = 'none';
                document.getElementById('bill_print_form_button').innerHTML = 'چاپ قبوض دوره';
                document.getElementById('report_print_form').style.display = 'none';
                document.getElementById('report_print_form_button').innerHTML = 'گزارش ماهيانه صندوق';
            }
            else{
                payment_display.display = 'none';
                balance_display.display = 'inline';
                document.getElementById('toggle_table_button').innerHTML = 'نمايش ليست پرداخت ماه جاري';
                document.getElementById('payment_calc_form').style.display = 'none';
                document.getElementById('payment_calc_form_button').innerHTML = 'محاسبه پرداخت ماهيانه';
                document.getElementById('bill_print_form').style.display = 'none';
                document.getElementById('bill_print_form_button').innerHTML = 'چاپ قبوض دوره';
                document.getElementById('report_print_form').style.display = 'none';
                document.getElementById('report_print_form_button').innerHTML = 'گزارش ماهيانه صندوق';
            }
        }
        
        // Payment Calculation Button Clicked
        function payment_calc(){
            var form_display = document.getElementById('payment_calc_form').style;                                          
            if (form_display.display === 'none'){
                form_display.display = 'inline-block';
                document.getElementById('payment_calc_form_button').innerHTML = 'بستن فرم';
                document.getElementById('bill_print_form').style.display = 'none';
                document.getElementById('bill_print_form_button').innerHTML = 'چاپ قبوض دوره';
                document.getElementById('report_print_form').style.display = 'none';
                document.getElementById('report_print_form_button').innerHTML = 'گزارش ماهيانه صندوق';
                document.getElementById('bank_profit').value  = '';
                document.getElementById('bank_profit').focus();
                document.getElementById('current_month_account_balance').value  = '';
            }
            else{
                document.getElementById('payment_calc_form_button').innerHTML = 'محاسبه پرداخت ماهيانه';
                form_display.display = 'none';
            }
        }
        
        // Bill Print Button Clicked 
        function bill_print(){
            var form_display = document.getElementById('bill_print_form').style;                                        
            if (form_display.display === 'none'){
                form_display.display = 'inline-block';
                document.getElementById('bill_print_form_button').innerHTML = 'بستن فرم';
                document.getElementById('payment_calc_form').style.display = 'none';
                document.getElementById('payment_calc_form_button').innerHTML = 'محاسبه پرداخت ماهيانه';
                document.getElementById('report_print_form').style.display = 'none';
                document.getElementById('report_print_form_button').innerHTML = 'گزارش ماهيانه صندوق';
            }
            else{
                document.getElementById('bill_print_form_button').innerHTML = 'چاپ قبوض دوره';
                form_display.display = 'none';
            }
        }
        // Report Print Button Clicked 
        function report_print(){
            var form_display = document.getElementById('report_print_form').style;                                        
            if (form_display.display === 'none'){
                form_display.display = 'inline-block';
                document.getElementById('report_print_form_button').innerHTML = 'بستن فرم';
                document.getElementById('bill_print_form').style.display = 'none';
                document.getElementById('bill_print_form_button').innerHTML = 'چاپ قبوض دوره';
                document.getElementById('payment_calc_form').style.display = 'none';
                document.getElementById('payment_calc_form_button').innerHTML = 'محاسبه پرداخت ماهيانه';
            }
            else{
                document.getElementById('report_print_form_button').innerHTML = 'گزارش ماهيانه صندوق';
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
