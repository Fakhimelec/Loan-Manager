 <?php
 
    session_start();
    ob_start();

?>

<?php
    # Connect To MySQL Database
    require "db_connect.php";
            
    if($_GET['source'] == "delete_member_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM member_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        echo $fname."  ".$lname;
        exit();
    }elseif($_GET['source'] == "return_pace_member_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM member_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        $return_pace = (int)$row['return_pace'];
        echo $fname."  ".$lname." --> سرعت بازپرداخت : ".$return_pace. " قسط در ماه";
        exit();
    }elseif($_GET['source'] == "loan_member_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM member_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        echo $fname."  ".$lname;
        exit();
    }elseif($_GET['source'] == "delete_loan_num"){
        $loan_num = $_GET['loan_number'];
        $sql = "SELECT * FROM loan_list WHERE loan_number= $loan_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        $ydate = $row['ydate'];
        $mdate = $row['mdate'];
        $price = number_format($row['loan_price']);
        $debt = number_format($row['debt_left']);
        
        echo "وام متعلق به  ".$fname."  ".$lname.
        " در تاريخ : ".$mdate." / ".$ydate.
        " به مبلغ ".$price.
        " تومان با مانده بدهي ".$debt." تومان مي باشد";
        exit();
    }elseif($_GET['source'] == "delete_expense_num"){
        $expense_num = $_GET['expense_number'];
        $sql = "SELECT * FROM expense_list WHERE expense_number= $expense_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $ex_name = str_replace("ی", "ي", $row['expense_name']); 
        $price = number_format($row['expense_price']);
        $ydate = $row['ydate'];
        $mdate = $row['mdate'];
        $ddate = $row['ddate'];
        
        echo "هزينه مربوط به  ".$ex_name.
        " با مبلغ  ".$price.
        " در تاريخ ".$ddate." / ".$mdate." / ".$ydate.
        " مي باشد";
        exit();
    }elseif($_GET['source'] == "manual_member_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM member_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        
        echo $fname."  ".$lname;
        exit();
    }elseif($_GET['source'] == "auto_member_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM member_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        
        echo $fname."  ".$lname;
        exit();
    }elseif($_GET['source'] == "delete_winner_num"){
        $member_num = $_GET['member_number'];
        $sql = "SELECT * FROM lottery_list WHERE member_number= $member_num";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $fname = str_replace("ی", "ي", $row['first_name']);
        $lname = str_replace("ی", "ي", $row['last_name']);
        $prize = number_format($row['details']);
        $ydate = $row['ydate'];
        $mdate = $row['mdate'];
        $ddate = $row['ddate'];
        
        echo $fname."  ".$lname."   ".
             $ddate." / ".$mdate." / ".$ydate."   ".
             $prize;
        exit();
    }
?> 