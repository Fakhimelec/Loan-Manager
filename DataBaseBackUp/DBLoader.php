<?php 

session_start(); 
ob_start();

?>
<!-- Connecting To Database And Create Tables-->
<!-- Table Names Are : -->
<!-- • member_list-->
<!-- • Loan_list-->
<!-- • expense_list-->
<!-- • lottery_list-->
<!-- • balance_list-->

<!-- Connecting To Database And Create Tables-->
<!-- Connecting To Database And Create Tables-->


<?php
    # MySQLi Object Oriented Way
    $year = 1402;
    $month = 6;
    $date = "$year"."_"."$month"."_";
    $servername = "localhost";
    $username = "pcbexper_mainuser";
    $password = "&D@ofw(k1[{E";
    $dbname = "pcbexper_db";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        echo "Connection Error";
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully"."<br>";
    ############################################################################
    # Changing Database Collation To Persian To Be Able To Put In Persian Text
    $sql = "ALTER DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_persian_ci";
    
    if ($conn->query($sql) === TRUE) {
      echo "Database Collation Changed To Persian successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }
    
    ############################################################################
    # SQL To Create Table (member_list)
    
    $sql = "DROP TABLE IF EXISTS member_list";
    if ($conn->query($sql) === TRUE) {
      echo "Member List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }    
    
    $sql = "CREATE TABLE member_list (
    member_number INT(7) UNSIGNED NOT NULL,
    prefix VARCHAR(10) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    id_num VARCHAR(50) DEFAULT 0 NOT NULL,
    total_deposit INT(10) UNSIGNED NOT NULL,
    monthly_deposit INT(10) UNSIGNED NOT NULL,
    loan_status BOOL DEFAULT 0 NOT NULL,
    win_status BOOL DEFAULT 0 NOT NULL,
    return_pace INT(2) DEFAULT 1 NOT NULL,
    details TEXT NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Member List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }
    ############################################################################
    ############################################################################
    # SQL To Create Table (loan_list)
    
    $sql = "DROP TABLE IF EXISTS loan_list";
    if ($conn->query($sql) === TRUE) {
      echo "Loan List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }    
    
    $sql = "CREATE TABLE loan_list (
    member_number INT(7) UNSIGNED NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    loan_number INT(7) UNSIGNED NOT NULL,
    ydate INT(4) UNSIGNED NOT NULL,
    mdate INT(2) UNSIGNED NOT NULL,
    ddate INT(2) UNSIGNED NOT NULL,
    loan_price INT(10) UNSIGNED NOT NULL,
    installment_count INT(2) UNSIGNED NOT NULL,
    installment_amount INT(10) UNSIGNED NOT NULL,
    commision_rate FLOAT(1) UNSIGNED NOT NULL,
    commision_amount INT(10) UNSIGNED NOT NULL,
    debt_left INT(10) UNSIGNED NOT NULL,
    installment_left INT(2) UNSIGNED NOT NULL,
    details TEXT NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Loan List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }
    ############################################################################
    ############################################################################
    # SQL To Create Table (expense_list)
    
    $sql = "DROP TABLE IF EXISTS expense_list";
    if ($conn->query($sql) === TRUE) {
      echo "Expense List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }    
    
    $sql = "CREATE TABLE expense_list (
    expense_number INT(7) UNSIGNED NOT NULL,
    expense_name VARCHAR(100) NOT NULL,
    expense_price INT(10) UNSIGNED NOT NULL,
    ydate INT(4) UNSIGNED NOT NULL,
    mdate INT(2) UNSIGNED NOT NULL,
    ddate INT(2) UNSIGNED NOT NULL,
    total_expense INT(10) UNSIGNED NOT NULL,
    details TEXT NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Expense List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }
    ############################################################################
    ############################################################################
    # SQL To Create Table (lottery_list)
    
    $sql = "DROP TABLE IF EXISTS lottery_list";
    if ($conn->query($sql) === TRUE) {
      echo "Lottery List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }    
    
    $sql = "CREATE TABLE lottery_list (
    member_number INT(7) UNSIGNED NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    ydate INT(4) UNSIGNED NOT NULL,
    mdate INT(2) UNSIGNED NOT NULL,
    ddate INT(2) UNSIGNED NOT NULL,
    details TEXT NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Lottery List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }   
    ############################################################################
    ############################################################################
    # SQL To Create Table (balance_list)
    
    $sql = "DROP TABLE IF EXISTS balance_list";
    if ($conn->query($sql) === TRUE) {
      echo "Balance List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }    
    
    $sql = "CREATE TABLE balance_list (
    row INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ydate INT(4) UNSIGNED NOT NULL,
    mdate INT(2) UNSIGNED NOT NULL,
    ddate INT(2) UNSIGNED NOT NULL,
    total_asset INT(12) UNSIGNED NOT NULL,
    total_liability INT(12) UNSIGNED NOT NULL,
    balance INT(12) UNSIGNED NOT NULL,
    bank_profit INT(12) UNSIGNED NOT NULL,
    account_balance INT(12) UNSIGNED NOT NULL,
    balance_diff INT(12) SIGNED NOT NULL,
    details TEXT NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Balance List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }
    ############################################################################
    ############################################################################
    # SQL To Create Table (user_list)
    
    $sql = "DROP TABLE IF EXISTS user_list";
    if ($conn->query($sql) === TRUE) {
      echo "User List Table Dropped Successfully" . "<br>";
    } else {
      echo "Error creating collation: " . $conn->error . "<br>";
    }

    $sql = "CREATE TABLE user_list (
    id int NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL, 
    PRIMARY KEY (id)
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "User List Table created successfully" . "<br>";
    } else {
      echo "Error creating table: " . $conn->error . "<br>";
    }
    ############################################################################   
    $conn->close(); 

    echo "All Databases Are Created"."<br>";
    echo "Members List (member_list)"."<br>";
    echo "Loans List (loan_list)"."<br>";
    echo "Expenses List (expense_list)"."<br>";
    echo "Lottery List (lottery_list)"."<br>";
    echo "Balance List (balance_list)"."<br>";
    echo "Users List (user_list)"."<br>";
    echo "<br>";
?>

<!-- Start To Fill Tables Using Files In The Path-->
<?php
    $servername = "localhost";
    $username = "pcbexper_mainuser";
    $password = "&D@ofw(k1[{E";
    $dbname = "pcbexper_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->query("SET character_set_results=utf8;");
    $conn->query("SET character_set_client=utf8;");
    $conn->query("SET character_set_connection=utf8;");
    $conn->query("SET character_set_database=utf8;");
    $conn->query("SET character_set_server=utf8;");


#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()


    echo "Member List Filling Started"."<br>";
    $list = $date."Members.txt";
    $db = fopen($list,'r');

    # Pass The First Row Which Are Header Values
    if (!feof($db)){
        $getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	echo $explodeLine[0]."<br>";
    }
    
    # This Is A Row Counter To Echo Each Row Of Loaded Text File
    $counter = 0;
    
    # Loop Through Loaded Text File And Parse Every Line In Variables And
    # Put Every Row Of File Into A New Record Of Table One By One
    while (!feof($db)) 
    {
    	$getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	
    	echo $explodeLine[$counter]."<br>";
    	$counter+= 1;
    	
    	list($member_number,
    	    $prefix,
    	    $last_name,
    	    $first_name,
            $id_num,
    	    $total_deposit,
    	    $monthly_deposit,
            $loan_status,
            $win_status,
    	    $return_pace,
    	    $details) = $explodeLine;
    	    
	    $sql = "INSERT INTO member_list 
                VALUES ('".$member_number."',
                        '".$prefix."',
                        '".$last_name."',
                        '".$first_name."',
                        '".$id_num."',
                        '".$total_deposit."',
                        '".$monthly_deposit."',
                        '".$loan_status."',
                        '".$win_status."',
                        '".$return_pace."',
                        '".$details."')";
    
        # Put In Data Into Table Record Using MySQL Query
        if ($conn->query($sql) === TRUE) {
          echo "$sql  =====> Record Created Successfully"."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error."<br>";
        }
    }    
    
    # I Don't Know Why The return_pace Column Is Read Wrong (1 Is Read 7 !!!)
    # So I Have To Fix This By Using Update Query On That Column Of Table
/*    if ($conn->query("UPDATE member_list SET return_pace = 1") === TRUE) {
      echo "Return Pace Column Updated Successfully"."<br>";
    } else {
      echo "Error: " . "<br>" . $conn->error."<br>";
    } */ 
    
    echo "Member List Filling Finnished"."<br>";

    
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()



    echo "Loan List Filling Started"."<br>";
    $list = $date."Loans.txt";
    $db = fopen($list,'r');

    # Pass The First Row Which Are Header Values
    if (!feof($db)){
        $getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	echo $explodeLine[0]."<br>";
    }
    
    # This Is A Row Counter To Echo Each Row Of Loaded Text File
    $counter = 0;
    
    # Loop Through Loaded Text File And Parse Every Line In Variables And
    # Put Every Row Of File Into A New Record Of Table One By One
    while (!feof($db)) 
    {
    	$getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	
    	echo $explodeLine[$counter]."<br>";
    	$counter+= 1;
    	
    	list($member_number,
    	    $last_name,
            $first_name,
            $loan_number,
            $ydate,
            $mdate,
            $ddate,
            $loan_price,
            $installment_count,
            $installment_amount,
            $commision_rate,
            $commision_amount,
            $debt_left,
            $installment_left,
            $details) = $explodeLine;
    	    
	    $sql = "INSERT INTO loan_list 
                VALUES ('".$member_number."',
                        '".$last_name."',
                        '".$first_name."',
                        '".$loan_number."',
                        '".$ydate."',
                        '".$mdate."',
                        '".$ddate."',
                        '".$loan_price."',
                        '".$installment_count."',
                        '".$installment_amount."',
                        '".$commision_rate."',
                        '".$commision_amount."',
                        '".$debt_left."',
                        '".$installment_left."',
                        '".$details."')";
        
        # Put In Data Into Table Record Using MySQL Query
        if ($conn->query($sql) === TRUE) {
          echo "$sql  =====> Record Created Successfully"."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error."<br>";
        }
    }    
    
    # I Don't Know Why The commision_rate Column Is Read Wrong (1 Is Read 0.9 !!!)
    # So I Have To Fix This By Using Update Query On That Column Of Table
    if ($conn->query("UPDATE loan_list SET commision_rate = 1") === TRUE) {
      echo "Commision Rate Column Updated Successfully"."<br>";
    } else {
      echo "Error: " . "<br>" . $conn->error."<br>";
    }    
    
    echo "Loan List Filling Finnished"."<br>";


#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()



    echo "Expense List Filling Started"."<br>";
    $list = $date."Expenses.txt";
    $db = fopen($list,'r');

    # Pass The First Row Which Are Header Values
    if (!feof($db)){
        $getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	echo $explodeLine[0]."<br>";
    }
    
    # This Is A Row Counter To Echo Each Row Of Loaded Text File
    $counter = 0;
    
    # Loop Through Loaded Text File And Parse Every Line In Variables And
    # Put Every Row Of File Into A New Record Of Table One By One
    while (!feof($db)) 
    {
    	$getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	
    	echo $explodeLine[$counter]."<br>";
    	$counter+= 1;
    	
    	list($expense_number,
            $expense_name,
            $expense_price,
            $ydate,
            $mdate,
            $ddate,
            $total_expense,
            $details) = $explodeLine;
    	    
	    $sql = "INSERT INTO expense_list 
                VALUES ('".$expense_number."',
                        '".$expense_name."',
                        '".$expense_price."',
                        '".$ydate."',
                        '".$mdate."',
                        '".$ddate."',
                        '".$total_expense."',
                        '".$details."')";
        
        # Put In Data Into Table Record Using MySQL Query
        if ($conn->query($sql) === TRUE) {
          echo "$sql  =====> Record Created Successfully"."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error."<br>";
        }
    }    
    
/*    # I Don't Know Why The commision_rate Column Is Read Wrong (1 Is Read 0.9 !!!)
    # So I Have To Fix This By Using Update Query On That Column Of Table
    if ($conn->query("UPDATE loan_list SET commision_rate = 1") === TRUE) {
      echo "Commision Rate Column Updated Successfully"."<br>";
    } else {
      echo "Error: " . "<br>" . $conn->error."<br>";
    }  */  
    
    echo "Expense List Filling Finnished"."<br>";
    
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()



    echo "Balance List Filling Started"."<br>";
    $list = $date."Balance.txt";
    $db = fopen($list,'r');

    # Pass The First Row Which Are Header Values
    if (!feof($db)){
        $getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	echo $explodeLine[0]."<br>";
    }
    
    # This Is A Row Counter To Echo Each Row Of Loaded Text File
    $counter = 0;
    
    # Loop Through Loaded Text File And Parse Every Line In Variables And
    # Put Every Row Of File Into A New Record Of Table One By One
    while (!feof($db)) 
    {
    	$getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	
    	echo $explodeLine[$counter]."<br>";
    	$counter+= 1;
    	
    	list($row,
            $ydate,
            $mdate,
            $ddate,
            $total_asset,
            $total_liability,
            $balance,
            $bank_profit,
            $account_balance,
            $balance_diff,
            $details) = $explodeLine;
    	    
	    $sql = "INSERT INTO balance_list 
                VALUES ('".$row."',
                        '".$ydate."',
                        '".$mdate."',
                        '".$ddate."',
                        '".$total_asset."',
                        '".$total_liability."',
                        '".$balance."',
                        '".$bank_profit."',
                        '".$account_balance."',
                        '".$balance_diff."',
                        '".$details."')";
        
        # Put In Data Into Table Record Using MySQL Query
        if ($conn->query($sql) === TRUE) {
          echo "$sql  =====> Record Created Successfully"."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error."<br>";
        }
    }    
    
    echo "Balance List Filling Finnished"."<br>"; 
    
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()



    echo "Lottery List Filling Started"."<br>";
    $list = $date."Lottery.txt";
    $db = fopen($list,'r');

    # Pass The First Row Which Are Header Values
    if (!feof($db)){
        $getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	echo $explodeLine[0]."<br>";
    }
    
    # This Is A Row Counter To Echo Each Row Of Loaded Text File
    $counter = 0;
    
    # Loop Through Loaded Text File And Parse Every Line In Variables And
    # Put Every Row Of File Into A New Record Of Table One By One
    while (!feof($db)) 
    {
    	$getTextLine = fgets($db);
    	$explodeLine = explode(",",$getTextLine);
    	
    	echo $explodeLine[$counter]."<br>";
    	$counter+= 1;
    	
    	list($member_number,
            $last_name,
            $first_name,
            $ydate,
            $mdate,
            $ddate,
            $details) = $explodeLine;
    	    
	    $sql = "INSERT INTO lottery_list 
                VALUES ('".$member_number."',
                        '".$last_name."',
                        '".$first_name."',
                        '".$ydate."',
                        '".$mdate."',
                        '".$ddate."',
                        '".$details."')";
        
        # Put In Data Into Table Record Using MySQL Query
        if ($conn->query($sql) === TRUE) {
          echo "$sql  =====> Record Created Successfully"."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error."<br>";
        }
    }    

    echo "Lottery List Filling Finnished"."<br>";

#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
#()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()



    echo "User List Filling Started"."<br>";

    $sql = "INSERT INTO user_list
            VALUES (DEFAULT,
                    'fakhim', 
                    '123', 
                    'بابک',
                    'فخیم')";
    # Put In Data Into Table Record Using MySQL Query
    if ($conn->query($sql) === TRUE) {
      echo "$sql  =====> Record Created Successfully"."<br>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error."<br>";
    }
    
    $sql = "INSERT INTO user_list 
            VALUES (DEFAULT,
                    'beheshti', 
                    '123', 
                    'حسن',
                    'بهشتی')";
    
    # Put In Data Into Table Record Using MySQL Query
    if ($conn->query($sql) === TRUE) {
      echo "$sql  =====> Record Created Successfully"."<br>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error."<br>";
    }
    
    $sql = "INSERT INTO user_list 
            VALUES (DEFAULT,
                    'ebrahimian', 
                    '123', 
                    'علی',
                    'ابراهیمیان')";
    
    # Put In Data Into Table Record Using MySQL Query
    if ($conn->query($sql) === TRUE) {
      echo "$sql  =====> Record Created Successfully"."<br>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error."<br>";
    }
    echo "User List Filling Finnished"."<br>";
    
    $conn->close();
    
    echo "All Tables Are Filled"."<br>";
    
    header("Location: ../login.php")
    
?> 
