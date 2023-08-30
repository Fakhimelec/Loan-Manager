<?php 

    session_start(); 
    ob_start();
    
?>

<?php
    # Include db_connect
    require "db_connect.php";
    # MySQLi Object Oriented Way
    
    if (isset($_POST['uname']) && isset($_POST['password'])) {
        
            function validate($data){
        
               $data = trim($data);
        
               $data = stripslashes($data);
        
               $data = htmlspecialchars($data);
        
               return $data;
        
            }
    
        $uname = strtolower(validate($_POST['uname']));
    
        $pass = strtolower(validate($_POST['password']));
    
        if (empty($uname)) {

            header("Location: login.php?error=نام کاربری را وارد کنيد");
            
            $conn->close();
            
            exit();
    
        }else if(empty($pass)){

            header("Location: login.php?error=کلمه عبور را وارد کنيد");
            
            $conn->close();
            
            exit();
    
        }else{

            $sql = "SELECT * FROM user_list WHERE username='$uname' AND password='$pass'";
        
            $result = $conn->query($sql);
            

            if ($result->num_rows > 0) {
                
                $row = $result->fetch_assoc();
                
                if ($row['username'] === $uname && $row['password'] === $pass) {
                    
                    echo "<br>";
                    echo $row['first_name']." ".$row['last_name']." وارد شد ";
    
                    $_SESSION['first_name'] = str_replace("ی", "ي", $row['first_name']);
    
                    $_SESSION['last_name'] = str_replace("ی", "ي", $row['last_name']);
                    
                    $_SESSION['user_logged_in'] = true;
                    
                    header("Location: main.php");
                    
                    $conn->close();
                    
                    exit;
    
                }else{

                    header("Location: login.php?error=نام کاربری يا رمز عبور اشتباه وارد شده است ");
                    
                    $conn->close();
                    
                    exit();
    
                }
    
            }else{

                header("Location: login.php?error=نام کاربری يا رمز عبور اشتباه وارد شده است ");
                
                $conn->close();
                
                exit();
    
            }
    
        }
    
    }else{

        header("Location: login.php");
        
        $conn->close();
        
        exit();
    
    }    
?>