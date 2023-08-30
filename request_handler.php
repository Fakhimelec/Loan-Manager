<?php
    // Starting Sesion Allows To Use Session Variables
    // ob_start() is Used To Be Able To Use header() Function
    ob_start();
    session_start();
    
?>

<?php
    # Include;

    require_once 'jdf.php';
    $now = explode('/', jdate('Y/m/d',time(),'','Asia/Tehran','en'));
    $year = $now[0];
    $month = $now[1];
    $day = $now[2];
    
    // Connect To MySQL Database
    require "db_connect.php";
    
    // Requests Of All Forms On All Pages Are Redirected Here
    // Every Form Submit Is Distinguished By Checking $POST[] Value
    // And According To Which Form Submitted, Corresponding Action Is Taken
    // An "if-elseif" Condition Is Used To Capture The Submit Requests From Forms
    // And In Every Section, Corresponding Tables Will Be Updated
    
    // First We Check Whether If There Is Any Posted Variable Through
    // Form Submittion Or Not
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //@@@@@@@@@@@@@@@@@@@@@@ Member Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@
        // We Got A POST Request
        if(isset($_POST['member_add_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Add New Member Form Submitted
            // Adding A New Member To The member_list
            $_SESSION["message"] = "Member_Add";
    	    $sql = "INSERT INTO member_list 
                    VALUES ('".$_POST['new_member_num']."',
                            '".$_POST['new_member_prefix']."',
                            '".$_POST['new_member_lname']."',
                            '".$_POST['new_member_fname']."',
                            '".$_POST['new_member_id']."',
                            '".$_POST['new_member_deposit']."',
                            '".$_SESSION['new_member_monthly_deposit']."',
                            0,
                            0,
                            1,
                            '".$_POST['new_member_detail']."')";
            $conn->query($sql);
            
            // According To New Member's Total Deposit Value, The Total Asset Is Changed
            // And We Should Add A Line To The balance_list And Update The Total Asset
            $deposit_change = number_format($_POST['new_member_deposit']);
            // Get The Last Value Of Total Assets And Add it By New Member's Deposit
            $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_asset = $row['total_asset'];
            $new_total_asset = $total_asset + $_POST['new_member_deposit'];
            $details =  "افزایش مبلغ پس انداز و درصد از بابت عضو جدید  ".
                        $_POST['new_member_prefix']."  ".
                        $_POST['new_member_fname']." ".
                        $_POST['new_member_lname']." به میزان ".
                        $deposit_change;
    	    $sql = "INSERT INTO balance_list 
                    VALUES (DEFAULT,
                            '".$year."',
                            '".$month."',
                            '".$day."',
                            '".$new_total_asset."',
                            '".$row['total_liability']."',
                            '".$new_total_asset - $row['total_liability']."',
                            0,
                            0,
                            0,
                            '".$details."')";
            $conn->query($sql);
            
            header("Location: members.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['member_delete_submit'])){
            ////////////////////////////////////////////////////////////////////
            if (!empty($_POST['delete_all_member_check'])){
                // Delete All Members Form Submitted
                $_SESSION["message"] = "Member_Delete_All";
                
                // Before Deleting All Members, We Should Calculate The Change
                // In Total Assets Value By Multiplying total_deposit Value
                // Of A Member By Total Number (Count) Of Members In member_list
                
                // Get The Total Number Of Members From member_list
                $sql = "SELECT COUNT(*) AS 'member_number' FROM member_list";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $member_count = $row['member_number'];
                // Get Total Deposit Value By Fetching Data Of Last Member
                $sql = "SELECT * FROM member_list ORDER BY member_number DESC LIMIT 1" ;
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_deposit = $row['total_deposit'];
                $deposit_change = number_format($member_count * $total_deposit);
                
                // Get The Last Value Of Total Assets And Subtract it By $deposit_change
                $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_asset = $row['total_asset'];
                $new_total_asset = $total_asset - ($member_count * $total_deposit);
                $details =  "کاهش مبلغ پس انداز و درصد از بابت حذف تمامی اعضای صندوق   ".
                            " به میزان ".
                            $deposit_change;
        	    $sql = "INSERT INTO balance_list 
                        VALUES (DEFAULT,
                                '".$year."',
                                '".$month."',
                                '".$day."',
                                '".$new_total_asset."',
                                '".$row['total_liability']."',
                                '".$new_total_asset - $row['total_liability']."',
                                0,
                                0,
                                0,
                                '".$details."')";
                $conn->query($sql);                
                
                // Delete All Members From member_list
                $sql = "DELETE FROM member_list";
                $conn->query($sql);
                
                // Delete All Loans From loan_list
                $sql = "DELETE FROM loan_list";
                $conn->query($sql);
                
                // Delete All Members From lottery_list
                $sql = "DELETE FROM lottery_list";
                $conn->query($sql);
                
                header("Location: members.php");
                
            }else{
                // Delete Single Member Form Submitted
                $_SESSION["message"] = "Member_Delete";
                
                // We Have To Get Member Info To Update The balance_list Before
                // Deleting The Member From member_list
                // According To Deleted Member's Total Deposit Value, The Total Asset Is Changed
                // And We Should Add A Line To The balance_list And Update The Total Asset
                $sql = "SELECT * FROM member_list 
                        WHERE member_number = ".$_POST['/delete_member_num'];
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $prefix = $row['prefix'];
                $lname = $row['last_name'];
                $fname = $row['first_name'];
                $total_deposit = $row['total_deposit'];
                $deposit_change = number_format($total_deposit);
                

                // Get The Last Value Of Total Assets And Subtract it By New Member's Deposit
                $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_asset = (int)$row['total_asset'];
                $new_total_asset = $total_asset - $total_deposit;
                $details =  "کاهش مبلغ پس انداز و درصد از بابت حذف عضو  ".
                            $prefix."  ".
                            $lname." ".
                            $fname." به میزان ".
                            $deposit_change;
        	    $sql = "INSERT INTO balance_list 
                        VALUES (DEFAULT,
                                '".$year."',
                                '".$month."',
                                '".$day."',
                                '".$new_total_asset."',
                                '".$row['total_liability']."',
                                '".$new_total_asset - $row['total_liability']."',
                                0,
                                0,
                                0,
                                '".$details."')";
                $conn->query($sql);
                
                // Delete Member From member_list
                $sql = "DELETE FROM member_list 
                        WHERE member_number = ".$_POST['/delete_member_num'];
                $conn->query($sql);
                // Delete Member From loan_list If Exists
                $sql = "DELETE FROM loan_list
                        WHERE member_number = ".$_POST['/delete_member_num'];
                $conn->query($sql);
                // Delete Member From lottery_list If Exists
                $sql = "DELETE FROM lottery_list
                        WHERE member_number = ".$_POST['/delete_member_num'];
                $conn->query($sql);
                
                header("Location: members.php");
            }
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['return_pace_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Return Pace Change Of Member Form Submitted
            $_SESSION["message"] = "Return Pace Changed";
            $sql = "UPDATE member_list 
                    SET return_pace=".$_POST['return_pace'].
                    " WHERE member_number=".$_POST['/return_pace_member_num'];
                    echo
            $conn->query($sql);
            
            header("Location: members.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['monthly_deposit_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Monthly Deposit Of Members Form Submitted
            $_SESSION["message"] = "Monthly_Deposit_Change";
            $sql = "UPDATE member_list 
                    SET monthly_deposit=".$_POST['monthly_deposit'];
            $conn->query($sql);
            
            header("Location: members.php");
            ////////////////////////////////////////////////////////////////////
        //@@@@@@@@@@@@@@@@@@@@@@@ Loan Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@@        
        }elseif(isset($_POST['loan_add_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Add New Loan Form Submitted
            $_SESSION["message"] =  "Loan_Add";
            // First We Have To Get The Corresponding Member's Info
            $sql = "SELECT * FROM member_list WHERE member_number=".$_POST['/loan_member_num'];
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $prefix = $row['prefix'];
            $lname = str_replace("ي", "ي", $row['last_name']);
            $fname = str_replace("ي", "ي", $row['first_name']);
            // The We Should Calculate Some Of Loan Parameters
            $loan_price = $_POST['new_loan_price'];
            $debt_left = $_POST['new_loan_debt_left'];
            $installment_amount = $loan_price / $_POST['new_loan_installment_count'];
            $commision_amount = $_POST['new_loan_price'] * $_POST['new_loan_commision_rate'] * 0.01;
            $installment_left = ($debt_left/$loan_price) * $_POST['new_loan_installment_count'] ;
            $sql = "INSERT INTO loan_list 
                    VALUES ('".$_POST['/loan_member_num']."',
                            '".$lname."',
                            '".$fname."',
                            '".$_POST['new_loan_num']."',
                            '".$_POST['new_loan_ydate']."',
                            '".$_POST['new_loan_mdate']."',
                            '".$_POST['new_loan_ddate']."',
                            '".$loan_price."',
                            '".$_POST['new_loan_installment_count']."',
                            '".$installment_amount."',
                            '".$_POST['new_loan_commision_rate']."',
                            '".$commision_amount."',
                            '".$debt_left."',
                            '".$installment_left."',
                            '".$_POST['new_loan_detail']."')";
            $conn->query($sql);
            // At Last, We Need To Update The Member List and 
            // Change The Loan Status Of Corresponfing Member
            $sql = "UPDATE member_list 
                    SET loan_status = 1".
                    " WHERE member_number=".$_POST['/loan_member_num'];
            $conn->query($sql);

            // According To New Loan's Commision Rate Value, The Total Asset Is Changed
            // And We Should Add A Line To The balance_list And Update The Total Asset
            $deposit_change = number_format($commision_amount);
            // Get The Last Value Of Total Assets And Add it By $commision_amount
            $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_asset = (int)$row['total_asset'];
            $new_total_asset = $total_asset + $commision_amount;
            $details =  "افزایش مبلغ پس انداز و درصد از بابت کمیسیون وام اعطا شده به    ".
                        $prefix."  ".
                        $fname." ".
                        $lname." به میزان ".
                        $deposit_change;
    	    $sql = "INSERT INTO balance_list 
                    VALUES (DEFAULT,
                            '".$_POST['new_loan_ydate']."',
                            '".$_POST['new_loan_mdate']."',
                            '".$_POST['new_loan_ddate']."',
                            '".$new_total_asset."',
                            '".$row['total_liability']."',
                            '".$new_total_asset - $row['total_liability']."',
                            0,
                            0,
                            0,
                            '".$details."')";
            $conn->query($sql);
            
            header("Location: loans.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['loan_delete_submit'])){
            ////////////////////////////////////////////////////////////////////
            if (!empty($_POST['delete_all_loan_check'])){
                // Delete All Loans Form Submitted
                $_SESSION["message"] =  "Loan_Delete_All";
                $sql = "DELETE FROM loan_list";
                $conn->query($sql);
                $sql = "UPDATE member_list SET loan_status = 0";
                $conn->query($sql);
                
                header("Location: loans.php");
            }else{
                // Delete Single Loan Form Submitted
                $_SESSION["message"] =  "Loan_Delete";
                
                // If A Loan Is Added By Mistake Or Needed To Be Deleted 
                // Imidiately After Benig Added, The Newly Added Commision Rate 
                // Value Should Be Deleted. In Order To Get This The Only Way 
                // May Be To Check The Loan ydate And mdate And If It's Added In 
                // Recent Month, Then Deleting It Will Subtract A commision_rate
                // Value From Total Assets
                
                // Find The Loan's ydate And mdate
                $sql = "SELECT * FROM loan_list 
                        WHERE loan_number = ".$_POST['/delete_loan_num'];
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $member_num = $row['member_number'];
                $commision_amount = $row['commision_amount'];
                $lname = $row['last_name'];
                $fname = $row['first_name'];
                $lname = $row['last_name'];
                $ydate = $row['ydate'];
                $mdate = $row['mdate'];
                if($ydate==$year && $mdate==$month){
                    // According To Deleted Loan's Commision Rate Value, The Total 
                    // Asset Is Changed And We Should Add A Line To The balance_list 
                    // To Update The Total Asset
                    $deposit_change = number_format($commision_amount);
                    // Get The Last Value Of Total Assets And Subtract It By $commision_amount
                    $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $total_asset = (int)$row['total_asset'];
                    $new_total_asset = $total_asset - $commision_amount;
                    $details =  "کاهش مبلغ پس انداز و درصد از بابت کمیسیون وام حذف شده متعلق به    ".
                                $fname." ".
                                $lname." به میزان ".
                                $deposit_change;
            	    $sql = "INSERT INTO balance_list 
                            VALUES (DEFAULT,
                                    '".$year."',
                                    '".$month."',
                                    '".$day."',
                                    '".$new_total_asset."',
                                    '".$row['total_liability']."',
                                    '".$new_total_asset - $row['total_liability']."',
                                    0,
                                    0,
                                    0,
                                    '".$details."')";
                    $conn->query($sql);                    
                }
                
                // Change The loan_status Parameter In member_list
                $sql = "UPDATE member_list 
                        SET loan_status = 0".
                        " WHERE member_number = $member_num";
                $conn->query($sql);
                
                // Delete Loan From loan_list
                $sql = "DELETE FROM loan_list
                        WHERE loan_number = ".$_POST['/delete_loan_num'];
                $conn->query($sql);
                
                header("Location: loans.php");
            }
            ////////////////////////////////////////////////////////////////////
        //@@@@@@@@@@@@@@@@@@@@@ Expense Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@
        }elseif(isset($_POST['expense_add_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Add New Expense Form Submitted
            $_SESSION["message"] = "Expense_Add";
            // First We Have To Get The Total Expense
            $sql = "SELECT * FROM expense_list ORDER BY expense_number DESC LIMIT 1" ;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_expense = $row['total_expense'] + $_POST['new_expense_price'];
    	    $sql = "INSERT INTO expense_list 
                    VALUES ('".$_POST['new_expense_num']."',
                            '".$_POST['new_expense_name']."',
                            '".$_POST['new_expense_price']."',
                            '".$_POST['new_expense_ydate']."',
                            '".$_POST['new_expense_mdate']."',
                            '".$_POST['new_expense_ddate']."',
                            '".$total_expense."',
                            '".$_POST['new_expense_detail']."')";
            $conn->query($sql);

            // According To New Expense Value, The Total Liability Is Changed And
            // We Should Add A Line To The balance_list And Update The Total Liability
            $liability_change = number_format($_POST['new_expense_price']);
            // Get The Last Value Of Total Assets And Add it By $commision_amount
            $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_asset = $row['total_asset'];
            $total_liability = $row['total_liability'];
            $new_total_liability = $total_liability + $_POST['new_expense_price'];
            $details =  "افزایش مبلغ وام و خرج صندوق از بابت هزینه ی جدید برای  ".
                        $_POST['new_expense_name']."  ".
                        " به میزان ".
                        $liability_change;
    	    $sql = "INSERT INTO balance_list 
                    VALUES (DEFAULT,
                            '".$year."',
                            '".$month."',
                            '".$day."',
                            '".$row['total_asset']."',
                            '".$new_total_liability."',
                            '".$total_asset - $new_total_liability."',
                            0,
                            0,
                            0,
                            '".$details."')";
            $conn->query($sql);
            
            header("Location: expenses.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['expense_delete_submit'])){
            ////////////////////////////////////////////////////////////////////
            if (!empty($_POST['delete_all_expense_check'])){
                // Delete All Expenses Form Submitted
                $_SESSION["message"] = "Expense_Delete_All";

                $sql = "DELETE FROM expense_list";
                $conn->query($sql);
                
                header("Location: expenses.php");
            }else{
                // Delete Single Expense Form Submitted
                $_SESSION["message"] = "Expense_Delete";
                
                // When We Delete An Expense From expense_list, The total_liability 
                // Value In balance_list Should Be Updated As Well 
                
                // Find The Corresponding Expense Data From expense_list
                $sql = "SELECT * FROM expense_list 
                        WHERE expense_number = ".$_POST['/delete_expense_num'];
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $expense_num = $row['expense_number'];
                $expense_name = $row['expense_name'];
                $expense_price = $row['expense_price'];
                
                // According To Deleted Expense Price Value, The Total Liability 
                // Is Changed And We Should Add A Line To The balance_list To 
                // Update The Total Liability
                $liability_change = number_format($expense_price);
                // Get The Last Value Of Total Liability And Subtract It By $expense_price
                $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_asset = $row['total_asset'];
                $total_liability = $row['total_liability'];
                $new_total_liability = $total_liability - $expense_price;
                $details =  "کاهش مبلغ وام و خرج صندوق از بابت هزینه ی جدید برای  ".
                            $expense_name."  ".
                            " به میزان ".
                            $liability_change;
        	    $sql = "INSERT INTO balance_list 
                        VALUES (DEFAULT,
                                '".$year."',
                                '".$month."',
                                '".$day."',
                                '".$row['total_asset']."',
                                '".$new_total_liability."',
                                '".$total_asset - $new_total_liability."',
                                0,
                                0,
                                0,
                                '".$details."')";
                $conn->query($sql);                   

                // Delete Expense From expense_list
                $sql = "DELETE FROM expense_list
                        WHERE expense_number = ".$_POST['/delete_expense_num'];
                $conn->query($sql);
                
                header("Location: expenses.php");
            }
            ////////////////////////////////////////////////////////////////////
        //@@@@@@@@@@@@@@@@@@@@@ Lottery Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@
        }elseif(isset($_POST['manual_winner_add_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Add New Winner Manual Form Submitted
            $_SESSION["message"] = "Manual_Winner_Add";
            // Find The Corresponding Member
            $member_num = $_POST['/manual_member_num'];
            $sql = "SELECT * FROM member_list 
                    WHERE member_number = ".$member_num;
                    
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $lname = $row['last_name'];
            $fname = $row['first_name'];
    	    $sql = "INSERT INTO lottery_list 
                    VALUES ('".$_POST['/manual_member_num']."',
                            '".$lname."',
                            '".$fname."',
                            '".$_POST['new_winner_ydate']."',
                            '".$_POST['new_winner_mdate']."',
                            '".$_POST['new_winner_ddate']."',
                            '".$_POST['new_manual_winner_detail']."')";
            $conn->query($sql);  
            // Change The win_status Parameter
            $sql = "UPDATE member_list 
                    SET win_status = 1".
                    " WHERE member_number = $member_num";
            $conn->query($sql);  
            
            header("Location: lottery.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['auto_winner_add_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Add New Winner Automatic Form Submitted
            $_SESSION["message"] = "Automatic_Winner_Add";
            // Find The Corresponding Member
            $member_num = $_POST['/auto_member_num'];
            $sql = "SELECT * FROM member_list 
                    WHERE member_number = ".$member_num;
      
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $lname = $row['last_name'];
            $fname = $row['first_name'];
    	    $sql = "INSERT INTO lottery_list 
                    VALUES ('".$_POST['/auto_member_num']."',
                            '".$lname."',
                            '".$fname."',
                            '".$_POST['new_winner_ydate']."',
                            '".$_POST['new_winner_mdate']."',
                            '".$_POST['new_winner_ddate']."',
                            '".$_POST['new_auto_winner_detail']."')";
            $conn->query($sql);  
            
            // Change The win_status Parameter
            $sql = "UPDATE member_list 
                    SET win_status = 1".
                    " WHERE member_number = $member_num";
            $conn->query($sql);   
            
            header("Location: lottery.php");
            ////////////////////////////////////////////////////////////////////
        }elseif(isset($_POST['winner_delete_submit'])){
            ////////////////////////////////////////////////////////////////////
            if (!empty($_POST['delete_all_winner_check'])){
                // Delete All Winners Form Submitted
                $_SESSION["message"] = "Winner_Delete_All";
                
                $sql = "DELETE FROM lottery_list";
                $conn->query($sql);
                
                $sql = "UPDATE member_list SET win_status = 0";
                $conn->query($sql);
                
                header("Location: lottery.php");
            }else{
                // Delete Single Winner Form Submitted
                $_SESSION["message"] = "Winner_Delete";
                
                // Find The Corresponding Member
                $delete_winner_num = $_POST['/delete_winner_num'];
                
                // Change The win_status Parameter In member_list
                $sql = "UPDATE member_list 
                        SET loan_status = 0".
                        " WHERE member_number = $delete_winner_num";
                $conn->query($sql);
                
                // Delete Winner From lottery_list
                $sql = "DELETE FROM lottery_list
                        WHERE member_number = ".$delete_winner_num;
                $conn->query($sql);
                
                header("Location: lottery.php");
            }
            
            ////////////////////////////////////////////////////////////////////
        //@@@@@@@@@@@@@@@@@@@@@@ Report Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@
        }elseif(isset($_POST['payment_calc_submit'])){
            ////////////////////////////////////////////////////////////////////
            // Payment Calculation Form Submitted
            $_SESSION["message"] = "Payment_Calc";
            // Before Anything, We Should Check Wether If We Have Calculated 
            // The Payments Before Or Not. For This We Check The Database To see
            // If There Is A Payment List File For Corresponding Month Or Not
            // ($table_exist = 0) --> Table Does Not Exist
            
            $list_name = "payment_list_".$_POST['payment_ydate']."_".$_POST['payment_mdate'];
            $sql = "DROP TABLE IF EXISTS $list_name";
            $conn->query($sql);
            $sql = "CREATE TABLE $list_name (
                    member_number INT(7) UNSIGNED NOT NULL,
                    last_name VARCHAR(30) NOT NULL,
                    first_name VARCHAR(30) NOT NULL,
                    loan_number INT(7) UNSIGNED NOT NULL,
                    ydate INT(4) UNSIGNED NOT NULL,
                    mdate INT(2) UNSIGNED NOT NULL,
                    loan_price INT(10) UNSIGNED NOT NULL,
                    monthly_deposit INT(10) UNSIGNED NOT NULL,
                    installment_amount INT(10) UNSIGNED NOT NULL,
                    total_pay INT(10) UNSIGNED NOT NULL,
                    debt_left INT(10) UNSIGNED NOT NULL,
                    installment_left INT(2) UNSIGNED NOT NULL,
                    total_deposit INT(10) UNSIGNED NOT NULL,
                    win_status BOOL DEFAULT 0 NOT NULL,
                    return_pace INT(2) DEFAULT 1 NOT NULL
                    )";
            $conn->query($sql);
            // We Need Some Variables To Store The Total Calculated Amounts
            // For Later Use In Updating balance_list
            $monthly_asset = 0;
            $monthly_liability = 0;
            $this_month_bank_profit = $_POST['bank_profit'];
            $this_month_account_balance = $_POST['this_month_account_balance'];
            // First We Should Load The Members From member_list And Then Start 
            // To Calculate Payments Individually According To Whether The 
            // Member Has An Active Loan Or Not
            $sql = "SELECT * FROM member_list";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // New Payment List Created And Calculated
                $calculated = 1;
                while($member = $result->fetch_assoc()) {
                    $member_number = $member['member_number'];
                    $lname = $member['last_name'];
                    $fname = $member['first_name'];
                    $total_deposit = $member['total_deposit'];
                    $monthly_deposit = $member['monthly_deposit'];
                    $deposit_pay = $monthly_deposit;
                    $win_status = $member['win_status'];
                    $return_pace = $member['return_pace'];
                    // Calculation Will Be Difference For Members With Or Without Loan
                    if($member['loan_status']){
                        $sql = "SELECT * FROM loan_list  WHERE member_number = ".$member_number;
                        $loan_data = $conn->query($sql);
                        $loan = $loan_data->fetch_assoc();
                        $loan_number = $loan['loan_number'];
                        $loan_price = $loan['loan_price'];
                        $loan_ydate = $loan['ydate'];
                        $loan_mdate = $loan['mdate'];
                        $debt_left = $loan['debt_left'];
                        $installment_amount = $loan['installment_amount'];
                        $installment_left = $loan['installment_left'];
                        if (($debt_left - $installment_amount * $return_pace)<($installment_amount)){
                            $loan_pay = $debt_left;
                            $debt_left = 0;
                            $installment_left = 0;
                        }else{
                            $loan_pay = $installment_amount * $return_pace;
                            $installment_left -= $return_pace;
                            $debt_left -= $loan_pay;
                        }
                        if ($debt_left == 0){
                            // Delete Loan From loan_list
                            $sql = "DELETE FROM loan_list
                                    WHERE loan_number = ".$loan_number;
                            $conn->query($sql);
                            
                            // Change The loan_status Parameter In member_list
                            $sql = "UPDATE member_list 
                                    SET loan_status = 0, return_pace = 1
                                    WHERE member_number = $member_number";
                            $conn->query($sql);
                                    
                        }
                        $total_pay = $deposit_pay + $loan_pay;
                        
                        // Update The debt_left And installment_left Values In loan_list
                        $sql = "UPDATE loan_list SET 
                                debt_left = $debt_left,
                                installment_left = $installment_left 
                                WHERE member_number = $member_number";
                        $conn->query($sql);     
                        // Updating The Total Assets And Total Liabilities Doing Calculations Of Current Month
                        $monthly_asset += $deposit_pay;
                        $monthly_liability += $debt_left;                     
                    }else{
                        $loan_number = 000;
                        $loan_price = 000;
                        $debt_left = 000;
                        $installment_left = 000;
                        $total_pay = $deposit_pay;
                        $monthly_asset += $deposit_pay;
                    }
                    // Increase The Total Deposit Of Each Member By Amount Of 
                    // Monthly Deposit
                    $total_deposit += $monthly_deposit;
                    $sql = "UPDATE member_list 
                                    SET total_deposit = $total_deposit".
                                    " WHERE member_number = $member_number";
                    $conn->query($sql); 
                            
            	    $sql = "INSERT INTO $list_name 
                            VALUES ('".$member_number."',
                                    '".$lname."',
                                    '".$fname."',
                                    '".$loan_number."',
                                    '".$loan_ydate."',
                                    '".$loan_mdate."',
                                    '".$loan_price."',
                                    '".$monthly_deposit."',
                                    '".$installment_amount."',
                                    '".$total_pay."',
                                    '".$debt_left."',
                                    '".$installment_left."',
                                    '".$total_deposit."',
                                    '".$win_status."',
                                    '".$return_pace."')";
                    $conn->query($sql);
                                         
                }
            } else {
                echo " No Members In Member List";
            }
            if ($calculated){
                // The Values Of Total Asset And Total Liability And Balance
                // Should Be Updated In balance_list and We Need To Add A New Line
                
                $sql = "SELECT * FROM expense_list ORDER BY expense_number DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_expense = $row['total_expense'];
                
                $sql = "SELECT * FROM balance_list ORDER BY row DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $total_asset = $row['total_asset'];
                $total_liability = $row['total_liability'];
                $new_total_asset = $total_asset + $monthly_asset;
                $new_total_liability = $monthly_liability + $total_expense;
                $new_balance = $new_total_asset - $new_total_liability;
                $balance_diff = $this_month_account_balance - ($new_balance + $this_month_bank_profit);
                $details =  " محاسبه پرداخت های ماهیانه افزایش پس انداز و درصد به میزان ".
                            $monthly_asset." و افزایش مبلغ وام و خرج به میزان ".
                            $monthly_liability." (مجموع هزینه صندوق : ".
                            $total_expense." ) ";
                            

        	    $sql = "INSERT INTO balance_list 
                        VALUES (DEFAULT,
                                '".$_POST['payment_ydate']."',
                                '".$_POST['payment_mdate']."',
                                1,
                                '".$new_total_asset."',
                                '".$new_total_liability."',
                                '".$new_balance."',
                                '".$this_month_bank_profit."',
                                '".$this_month_account_balance."',
                                '".$balance_diff."',
                                '".$details."')";  
                $conn->query($sql);

                ################################################################
                ######################## BackUp Lists ##########################
                $delimiter = ",";
                // Back Up Updated Member List In DataBaseBackUp Folder
                
                $sql = "SELECT * FROM member_list";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    // File Name Of The List To Be Saved
                    $filename = __DIR__ . "/DataBaseBackUp/".
                                $_POST['payment_ydate']."_".
                                $_POST['payment_mdate']."_Members.txt";
                    // Open A New File As Writable/Readable
                    $file = fopen($filename, 'w+');
                    // Specify Field Names (Keys)
                    $fields = array('Member Number', 'Prefix', 'Last Name', 
                                    'First Name', 'ID Num', 'Total Deposit', 
                                    'Monthly Deposit', 'Loan Status', 
                                    'Win Status', 'Return Pace', 'Details');
                    // Write Keys To The First Line O File
                    fputs($file, implode($delimiter, $fields)."\n");
                    
                    // Loop Through mySQL Database And Write Each Record To A New Line
                    while($row = $result->fetch_assoc()){ 
                        $lineData = array(  $row['member_number'], 
                                            $row['prefix'], 
                                            $row['last_name'], 
                                            $row['first_name'], 
                                            $row['id_num'], 
                                            $row['total_deposit'], 
                                            $row['monthly_deposit'], 
                                            $row['loan_status'], 
                                            $row['win_status'], 
                                            $row['return_pace'], 
                                            $row['details']); 
                        fputs($file, implode($delimiter, $lineData));
                    }
                    // The List File To Be Saved Should Be Cleaned Off Of Unwanted NewLines
    	            file_put_contents("$filename",implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
                    // Move back to beginning of file 
                    fseek($file, 0);
                    // Close Opened File
                    fclose($file);
                }
                
                #+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // Back Up Updated Loan List In DataBaseBackUp Folder
                
                $sql = "SELECT * FROM loan_list";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    // File Name Of The List To Be Saved
                    $filename = __DIR__ . "/DataBaseBackUp/".
                                $_POST['payment_ydate']."_".
                                $_POST['payment_mdate']."_Loans.txt";
                    // Open A New File As Writable/Readable
                    $file = fopen($filename, 'w+');
                    // Specify Field Names (Keys)
                    $fields = array('Member Number','Last Name', 'First Name', 
                                    'Loan Number', 'Year Date',  'Month Date',
                                    'Day Date', 'Loan Price', 'Installment Count',
                                    'Installment Amount', 'Commision Rate', 
                                    'Commision Ammount', 'Debt Left', 
                                    'Installment Left', 'Details');
                    // Write Keys To The First Line O File
                    fputs($file, implode($delimiter, $fields)."\n");
                    // Loop Through mySQL Database And Write Each Record To A New Line
                    while($row = $result->fetch_assoc()){ 
                        $row_counter++;
                        $lineData = array(  $row['member_number'],  
                                            $row['last_name'], 
                                            $row['first_name'], 
                                            $row['loan_number'], 
                                            $row['ydate'], 
                                            $row['mdate'], 
                                            $row['ddate'], 
                                            $row['installment_count'], 
                                            $row['installment_amount'],
                                            $row['commision_rate'], 
                                            $row['commision_ammount'], 
                                            $row['debt_left'], 
                                            $row['installment_left'], 
                                            $row['details']); 
                        fputs($file, implode($delimiter, $lineData)."\n");
                    }
                    // The List File To Be Saved Should Be Cleaned Off Of Unwanted NewLines
    	            file_put_contents("$filename",implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
                    // Move back to beginning of file 
                    fseek($file, 0);
                    // Close Opened File
                    fclose($file);
                }
                #+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // Back Up Updated Expense List In DataBaseBackUp Folder
                
                $sql = "SELECT * FROM expense_list";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    // File Name Of The List To Be Saved
                    $filename = __DIR__ . "/DataBaseBackUp/".
                                $_POST['payment_ydate']."_".
                                $_POST['payment_mdate']."_Expenses.txt";
                    // Open A New File As Writable/Readable
                    $file = fopen($filename, 'w+');
                    // Specify Field Names (Keys)
                    $fields = array('Expense Number','Expense Name', 'Expense Price', 
                                    'Year Date',  'Month Date', 'Day Date', 
                                    'Total Expense', 'Details');
                    // Write Keys To The First Line O File
                    fputs($file, implode($delimiter, $fields)."\n");
                    // Loop Through mySQL Database And Write Each Record To A New Line
                    while($row = $result->fetch_assoc()){ 
                        $lineData = array(  $row['expense_number'],  
                                            $row['expense_name'], 
                                            $row['expense_price'],
                                            $row['ydate'], 
                                            $row['mdate'], 
                                            $row['ddate'], 
                                            $row['total_expense'], 
                                            $row['details']); 
                        fputs($file, implode($delimiter, $lineData));
                    }
                    // The List File To Be Saved Should Be Cleaned Off Of Unwanted NewLines
    	            file_put_contents("$filename",implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
                    // Move back to beginning of file 
                    fseek($file, 0);
                    // Close Opened File
                    fclose($file);
                } 
                #+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // Back Up Updated Lottery List In DataBaseBackUp Folder
                
                $sql = "SELECT * FROM lottery_list";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    // File Name Of The List To Be Saved
                    $filename = __DIR__ . "/DataBaseBackUp/".
                                $_POST['payment_ydate']."_".
                                $_POST['payment_mdate']."_Lottery.txt";
                    // Open A New File As Writable/Readable
                    $file = fopen($filename, 'w+');
                    // Specify Field Names (Keys)
                    $fields = array('Member Number','Last Name', 'First Name', 
                                    'Year Date',  'Month Date', 'Day Date', 
                                    'Details');
                    // Write Keys To The First Line O File
                    fputs($file, implode($delimiter, $fields)."\n");
                    // Loop Through mySQL Database And Write Each Record To A New Line
                    while($row = $result->fetch_assoc()){ 
                        $lineData = array(  $row['member_number'],  
                                            $row['last_name'], 
                                            $row['first_name'],
                                            $row['ydate'], 
                                            $row['mdate'], 
                                            $row['ddate'],
                                            $row['details']); 
                        fputs($file, implode($delimiter, $lineData)."\n");
                    }
                    // The List File To Be Saved Should Be Cleaned Off Of Unwanted NewLines
    	            file_put_contents("$filename",implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
                    // Move back to beginning of file 
                    fseek($file, 0);
                    // Close Opened File
                    fclose($file);
                } 
                #+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // Back Up Updated Balance List In DataBaseBackUp Folder
                
                $sql = "SELECT * FROM balance_list";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    // File Name Of The List To Be Saved
                    $filename = __DIR__ . "/DataBaseBackUp/".
                                $_POST['payment_ydate']."_".
                                $_POST['payment_mdate']."_Balance.txt";
                    // Open A New File As Writable/Readable
                    $file = fopen($filename, 'w+');
                    // Specify Field Names (Keys)
                    $fields = array('Row', 'Year Date',  'Month Date', 'Day Date', 
                                    'Total Asset',  'Total Liability', 'Balance', 
                                    'Bank Profit',  'Account Balance', 
                                    'Balance Diff', 'Details');
                    // Write Keys To The First Line O File
                    fputs($file, implode($delimiter, $fields)."\n");
                    // Loop Through mySQL Database And Write Each Record To A New Line
                    while($row = $result->fetch_assoc()){ 
                        $lineData = array(  $row['row'],  
                                            $row['ydate'], 
                                            $row['mdate'], 
                                            $row['ddate'],
                                            $row['total_asset'], 
                                            $row['total_liability'],
                                            $row['balance'], 
                                            $row['bank_profit'],
                                            $row['account_balance'], 
                                            $row['balance_diff'],
                                            $row['details']); 
                        fputs($file, implode($delimiter, $lineData)."\n");
                    }
                    // The List File To Be Saved Should Be Cleaned Off Of Unwanted NewLines
    	            file_put_contents("$filename",implode(PHP_EOL, file("$filename", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
                    // Move back to beginning of file 
                    fseek($file, 0);
                    // Close Opened File
                    fclose($file);
                }
                // Change DBLoder.php And Modify Date Of BackUp To Recent Change
                $filename = __DIR__ . "/DataBaseBackUp/DBLoader.php";
                $str = "$year = ";
                $file = file_get_contents($filename);
                if(strpos($file, $searchfor)) 
                {
                   echo "String found";
                }
            }
            
            ####################################################################
            
            header("Location: reports.php");
            ////////////////////////////////////////////////////////////////////
        }
        
        else{
            echo "";
        }
    }
    //
    $conn->close();
?>