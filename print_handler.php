<?php
    // Starting Sesion Allows To Use Session Variables
    // ob_start() is Used To Be Able To Use header() Function
    ob_start();
    session_start();
    
?>

<?php
    // Include Jalali DateTime Librry
    require_once 'jdf.php';
    $now = explode('/', jdate('Y/m/d',time(),'','Asia/Tehran','en'));
    $year = $now[0];
    $month = $now[1];
    $day = $now[2];

    // Connect To The Database To Get The payment_list Data    
    require "db_connect.php";
            
    // First We Check Whether If There Is Any Posted Variable Through
    // Form Submittion Or Not
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //@@@@@@@@@@@@@@@@@@@@@@ Member Form Requests @@@@@@@@@@@@@@@@@@@@@@@@@@
        // We Got A POST Request
        if(isset($_POST['print_bills_submit'])){
            // Print Bills
            $bill_year = $_POST['print_bill_ydate'];
            $bill_month = $_POST['print_bill_mdate'];
            
            
            $list_name = "payment_list_".$bill_year."_".$bill_month;
            try {
                $sql = "SELECT * FROM $list_name";
                $result = $conn->query($sql);
                $list_exists = 1;
            }
            catch (Exception $e){
                $list_exists = 0;
            }    
            
            // Include the main TCPDF library (search for installation path).
            require_once('TCPDF/tcpdf.php');
            
            $A4_width = 210;
            $A4_height = 297;    
            $pageLayout = array($A4_width, $A4_height); //  or array($height, $width)
            
            // create new PDF document
            $pdf = new TCPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);
            
            // set document information
            $pdf->SetCreator("TCPDF");
            $pdf->SetAuthor('Babak Fakhim');
            //$pdf->SetTitle('TCPDF Example 018');
            //$pdf->SetSubject('TCPDF Tutorial');
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
            
            // Calculate Page Dimensions Considering The Margins
            $left_margin = 0;
            $right_miragin = 0;
            $top_margin = 0;
            // Find The Half And Quarter Of Page To Draw Separation Lines
            $half_w = $A4_width/2;
            $half_h = $A4_height/2;
            $quarter_h = $A4_height/4;
            // Start From Within 8mm Left Of The Page
            $spacer = 8;
            // Width Of The Section Of Loan/Member Data On Each Bill
            $section_width = 45;
            $height_offset = 8;
            // To Handle Loops Through Each Member
            $bill_index = 0;
            $bills_per_page = 8;
            
            // Setting Margins (LEFT, TOP, RIGHT);
            $pdf->SetMargins($left_margin, $top_margin, $right_miragin);
            
            // Set Off Auto Page Breaks
            $pdf->SetAutoPageBreak(false);
            
            // Set Image Scale Factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            
            // Set Some Language Dependent Data
            $lg = Array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'fa';
            $lg['w_page'] = 'page';
            
            // Set Some Language-Dependent Strings (Optional)
            $pdf->setLanguageArray($lg);
            
            // ---------------------------------------------------------
            
            // Set Font
            $titr 	= 'TCPDF/fonts/TitrBold.ttf';	// Font Path
            $nazanin = 'TCPDF/fonts/Nazanin.ttf';	// Font Path
            $font_size 	= 16;			// Font Size
            $titr_font 	= TCPDF_FONTS::addTTFfont($titr, 'TrueTypeUnicode');
            $nazanin_font 	= TCPDF_FONTS::addTTFfont($nazanin, 'TrueTypeUnicode');
            
            $pdf->SetFont($titr_font, '', $font_size, '', false);
            $pdf->SetFontSize(10);
            
            
            // Bill Separation Line Style (Dashed)
            $dashed_style = array(  'width' => 0.3, 
                                    'cap' => 'butt', 
                                    'join' => 'miter', 
                                    'dash' => '5,5',
                                    'color' => array(0, 0, 0));
            // Normal Line
            $normal_style = array(  'width' => 0.5, 
                                    'cap' => 'butt', 
                                    'join' => 'miter', 
                                    'dash' => 0, 
                                    'color' => array(0, 0, 255));
            
            // Disable The Page Header And Footer
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            
            
            // Check That If We Have Data In The Database, Start To Print
            if ($list_exists==1){
                if ($result->num_rows > 0) {
                    
                    while($row = $result->fetch_assoc()) {
                        if($bill_index % $bills_per_page == 0){
                            // 8 Bills Printed On A Page An We Need A New Page
                            echo "New Page"."<br>";
                            
                            // This Variable Will Step Up To $bills_per_page Value To 
                            // Determine The Bill's Location On Paper And Will Reset In
                            // Every New Page
                            $bill_locator = 0;
                            
                            // Add A New Page
                            $pdf->AddPage();
                            
                            // Set LTR Direction For english Translation
                            $pdf->setRTL(false);
        
                            // Draw The Bill Seperation Lines (8 Bills Per Page)
                            $pdf->Line( 0, $quarter_h, 
                                        $A4_width, $quarter_h, 
                                        $dashed_style);
                            $pdf->Line( 0, $half_h, 
                                        $A4_width, $half_h, 
                                        $dashed_style);
                            $pdf->Line( 0, 3*$quarter_h, 
                                        $A4_width, 3*$quarter_h, 
                                        $dashed_style);
                            $pdf->Line( $half_w, 0, 
                                        $half_w, $A4_height, 
                                        $dashed_style);
                        }    
         
                        $title = "صندوق پس انداز واقف (تاسیس 1359)  ".$bill_year."/".$bill_month;
                        $member =   "اطلاعات عضو"."<br>".
                                    "شماره عضویت:".$row['member_number']."<br>".
                                    "نام عضو: ".$row['first_name']."  ".$row['last_name']."<br>".
                                    "جمع پس انداز: ".number_format($row['total_deposit'])." ت "."<br>".
                                    "پس انداز این ماه: ".number_format($row['monthly_deposit'])."<br>".
                                    "سرعت بازپرداخت: ".$row['return_pace']."<br>".
                                    "وضعیت قرعه کشی: ".$row['win_status']."<br>";
                        $loan = "اطلاعات وام"."<br>".
                                "شماره وام: ".$row['loan_number']."<br>".
                                "تاریخ دریافت وام: ".$row['ydate']." / ".$row['mdate']."<br>".
                                "مبلغ وام: ".number_format($row['loan_price'])."<br>".
                                "مبلغ اقساط: ".number_format($row['installment_amount'])."<br>".
                                "مانده بدهی: ".number_format($row['debt_left'])."<br>".
                                "اقساط باقیمانده: ".$row['installment_left']."<br>";
                                
                        $total_pay = "جمع پرداختی: ".number_format($row['total_pay'])."ت";
                        $accountant_sign = "امضای حسابدار";
                        $teller_sign = "امضای صندوق دار";
                        
                        if ($bill_locator % 2 == 0){
                            //Adjust Font Size And Print Title
                            $pdf->SetFont($titr_font, '', $font_size, '', false);
                            $pdf->SetFontSize(15);
                            $pdf->Text( 7, 
                                        intdiv($bill_locator,2) * $quarter_h + $height_offset, 
                                        $title);
                            // Title Separation Line
                            $pdf->Line( 8, intdiv($bill_locator,2) * $quarter_h + 15, 
                                        100, intdiv($bill_locator,2) * $quarter_h + 15, 
                                        $normal_style);
                            //Adjust Font Size And Print Info
                            $pdf->SetFont($nazanin_font, '', $font_size, '', false);
                            $pdf->SetFontSize(12);
                            // Member Info
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                $spacer + $section_width , 
                                                2*$spacer + intdiv($bill_locator,2) * $quarter_h, 
                                                $member, 0, 0, 0, true, 'R', true);
                            // Loan Info
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                $spacer, 
                                                2*$spacer + intdiv($bill_locator,2) * $quarter_h, 
                                                $loan, 0, 0, 0, true, 'R', true);
                            // Teller Sign
                            $pdf->writeHTMLCell(30, 
                                                0, 
                                                $spacer, 
                                                63 + intdiv($bill_locator,2) * $quarter_h, 
                                                $teller_sign, 0, 0, 0, true, 'R', true);
                            // Accountant Sign
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                $spacer+$section_width, 
                                                63 + intdiv($bill_locator,2) * $quarter_h, 
                                                $accountant_sign, 0, 0, 0, true, 'R', true);
                            //Adjust Font Size And Print Total Pay
                            $pdf->SetFontSize(17);
                            $pdf->writeHTMLCell(60, 
                                                0, 
                                                21, 
                                                54 + intdiv($bill_locator,2) * $quarter_h, 
                                                $total_pay, 0, 0, 0, true, 'R', true);
                            $pdf->Line( 28, 
                                        intdiv($bill_locator,2) * $quarter_h + 61, 
                                        80, 
                                        intdiv($bill_locator,2) * $quarter_h + 61, 
                                        $normal_style);
                            $bill_locator +=1;
                            $bill_index +=1;
                        }else{
                            //Adjust Font Size And Print Title
                            $pdf->SetFont($titr_font, '', $font_size, '', false);
                            $pdf->SetFontSize(15);
                            $pdf->Text( 108, 
                                        intdiv($bill_locator,2) * $quarter_h + $height_offset, 
                                        $title);
                            // Title Separation Line
                            $pdf->Line( 110, 
                                        intdiv($bill_locator,2) * $quarter_h + 15, 
                                        203, 
                                        intdiv($bill_locator,2) * $quarter_h + 15, 
                                        $normal_style);
                            //Adjust Font Size And Print Info
                            $pdf->SetFont($nazanin_font, '', $font_size, '', false);
                            $pdf->SetFontSize(12);
                            // Member Info
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                2*$spacer+3*$section_width, 
                                                2*$spacer + intdiv($bill_locator,2) * $quarter_h, 
                                                $member, 0, 0, 0, true, 'R', true);
                            // Loan Info
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                2*$spacer+2*$section_width, 
                                                2*$spacer + intdiv($bill_locator,2) * $quarter_h, 
                                                $loan, 0, 0, 0, true, 'R', true);
                            // Teller Sign
                            $pdf->writeHTMLCell(30, 
                                                0, 
                                                2*$spacer+2*$section_width, 
                                                63 + intdiv($bill_locator,2) * $quarter_h, 
                                                $teller_sign, 0, 0, 0, true, 'R', true);
                            // Accountant Sign
                            $pdf->writeHTMLCell($section_width, 
                                                0, 
                                                2*$spacer+3*$section_width, 
                                                63 + intdiv($bill_locator,2) * $quarter_h, 
                                                $accountant_sign, 0, 0, 0, true, 'R', true);                    
                            //Adjust Font Size And Print Total Pay
                            $pdf->SetFontSize(17);
                            $pdf->writeHTMLCell(60, 
                                                0, 
                                                21+2*$section_width+$spacer, 
                                                54 + intdiv($bill_locator,2) * $quarter_h, 
                                                $total_pay, 0, 0, 0, true, 'R', true);
                            $pdf->Line( 126, 
                                        intdiv($bill_locator,2) * $quarter_h + 61, 
                                        177, 
                                        intdiv($bill_locator,2) * $quarter_h + 61, 
                                        $normal_style);
                            $bill_locator +=1;
                            $bill_index +=1;
                        }
                    }
                    //Close and output PDF document
                    ob_end_clean();
                    $pdf->Output('Payments_List_'.$bill_year.'_'.$bill_month, 'I');  
                    echo "here";
                } else {
                    // Add A New Page
                    $pdf->AddPage();
                    $pdf->SetFont($titr_font, '', $font_size, '', false);
                    $pdf->SetFontSize(17);
                    $pdf->Text(70, 20, 'لیست پرداخت خالی از اطلاعات است');
                    //Close and output PDF document
                    ob_end_clean();
                    $pdf->Output('Payments_List_'.$bill_year.'_'.$bill_month, 'I');  
                    
                }
            }else {
                // Add A New Page
                $pdf->AddPage();
                $pdf->SetFont($titr_font, '', $font_size, '', false);
                $pdf->SetFontSize(17);
                $pdf->Text(60, 20, 'لیست پرداختی این ماه محاسبه نشده است');
                //Close and output PDF document
                ob_end_clean();
                $pdf->Output('Payments_List_'.$bill_year.'_'.$bill_month, 'I');  
            }
        }elseif(isset($_POST['print_reports_submit'])){
            // Print Reports
            // Include the main TCPDF library (search for installation path).
            require_once('TCPDF/tcpdf.php');
            
            $A4_width = 297;
            $A4_height = 210;    
            $pageLayout = array($A4_width, $A4_height); //  or array($height, $width)
            
            // create new PDF document
            $pdf = new TCPDF('l', 'mm', $pageLayout, true, 'UTF-8', false);
            
            // set document information
            $pdf->SetCreator("TCPDF");
            $pdf->SetAuthor('Babak Fakhim');
            //$pdf->SetTitle('TCPDF Example 018');
            //$pdf->SetSubject('TCPDF Tutorial');
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
            
            // Calculate Page Dimensions Considering The Margins
            
            $left_margin = 0;
            $right_miragin = 0;
            $top_margin = 0;
            // Find The Half And Quarter Of Page To Draw Separation Lines
            $half_w = $A4_width/2;
            // Start From Within 8mm Left Of The Page
            $spacer = 12;
            $height_offset = 40;
            
            // Setting Margins (LEFT, TOP, RIGHT);
            $pdf->SetMargins($left_margin, $top_margin, $right_miragin);
            
            // Set Off Auto Page Breaks
            $pdf->SetAutoPageBreak(false);
            
            // Set Image Scale Factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            
            // Set Some Language Dependent Data
            $lg = Array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['a_meta_dir'] = 'rtl';
            $lg['a_meta_language'] = 'fa';
            $lg['w_page'] = 'page';
            
            // Set Some Language-Dependent Strings (Optional)
            $pdf->setLanguageArray($lg);
            
            // Disable The Page Header And Footer
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);            
            // ---------------------------------------------------------
            
            // Set Font
            $titr 	= 'TCPDF/fonts/TitrBold.ttf';	// Font Path
            $nazanin = 'TCPDF/fonts/Roya.ttf';	// Font Path
            $font_size 	= 16;			// Font Size
            $titr_font 	= TCPDF_FONTS::addTTFfont($titr, 'TrueTypeUnicode');
            $nazanin_font 	= TCPDF_FONTS::addTTFfont($nazanin, 'TrueTypeUnicode');
            
            $pdf->SetFont($titr_font, '', $font_size, '', false);
            $pdf->SetFontSize(10);

            // Bill Separation Line Style (Dashed)
            $dashed_style = array(  'width' => 0.3, 
                                    'cap' => 'butt', 
                                    'join' => 'miter', 
                                    'dash' => '5,5',
                                    'color' => array(0, 0, 0));
            // Normal Line
            $normal_style = array(  'width' => 0.8, 
                                    'cap' => 'butt', 
                                    'join' => 'miter', 
                                    'dash' => 0, 
                                    'color' => array(0, 0, 255));
            
            
            // Add A New Page
            $pdf->AddPage();
            
            // Set LTR Direction For english Translation
            $pdf->setRTL(false);

            // Draw The Bill Seperation Lines (8 Bills Per Page)
            $pdf->Line( $half_w, 0, 
                        $half_w, $A4_height, 
                        $dashed_style);
            $title = "بیلان سالانه صندوق پس انداز واقف  تاریخ: ".$year."/".$month;
            

            //Adjust Font Size And Print Title
            $pdf->SetFont($titr_font, '', $font_size, '', false);
            $pdf->SetFontSize(20);
            $pdf->Text( 10, 
                        8, 
                        $title);
            $pdf->Text( 152, 
                        8, 
                        $title);
            // Title Separation Line
            $pdf->Line( 0, 20, 
                        297, 20, 
                        $normal_style);
            // Draw Left Table Horizontal Lines
            $pdf->Line( 12, 50, 
                        142, 50, 
                        $normal_style);
            $pdf->Line( 12, 60, 
                        142, 60, 
                        $normal_style);
            $pdf->Line( 12, 70, 
                        142, 70, 
                        $normal_style);
            $pdf->Line( 12, 79, 
                        142, 79, 
                        $normal_style);
            $pdf->Line( 12, 89, 
                        142, 89, 
                        $normal_style);
            $pdf->Line( 12, 99, 
                        142, 99, 
                        $normal_style);
            $pdf->Line( 12, 109, 
                        142, 109, 
                        $normal_style);
            $pdf->Line( 12, 118, 
                        142, 118, 
                        $normal_style);
            $pdf->Line( 12, 128, 
                        142, 128, 
                        $normal_style);
            $pdf->Line( 12, 137, 
                        142, 137, 
                        $normal_style);
            $pdf->Line( 0, 20, 
                        297, 20, 
                        $normal_style);
            // Draw Right Table Horizontal Lines
            $pdf->Line( 12 + 13 + 130, 50, 
                        142 + 13 + 130, 50, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 60, 
                        142 + 13 + 130, 60, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 70, 
                        142 + 13 + 130, 70, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 79, 
                        142 + 13 + 130, 79, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 89, 
                        142 + 13 + 130, 89, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 99, 
                        142 + 13 + 130, 99, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 109, 
                        142 + 13 + 130, 109, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 118, 
                        142 + 13 + 130, 118, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 128, 
                        142 + 13 + 130, 128, 
                        $normal_style);
            $pdf->Line( 12 + 13 + 130, 137, 
                        142 + 13 + 130, 137, 
                        $normal_style);
            //Adjust Font Size And Print Info
            $pdf->SetFont($nazanin_font, '', $font_size, '', false);
            $pdf->SetFontSize(22);

            $report =   "تعداد اعضای صندوق: "."<br>".
                        "مبلغ پس انداز هر عضو: "."<br>".
                        "مبلغ پس انداز ماهیانه هر عضو: "."<br>".
                        "کل مبلغ پس انداز صندوق: "."<br>".
                        "مجموع مبالغ وام در اعضا: "."<br>".
                        "مجموع مبالغ کارمزد وام ها: "."<br>".
                        "مجموع مبلغ خرج صندوق: "."<br>".
                        "سود بانکی: "."<br>".
                        "موجودی کل صندوق تا ماه جاری: "."<br>".
                        "مجموع قبوض ماه جاری: "."<br>".
                        " افراد در صف انتظار وام (صفر): "."<br>";
            
            $data =     $_POST['member_count']."<br>".        
                        number_format($_POST['total_deposit'])."<br>".
                        number_format($_POST['monthly_deposit'])."<br>".
                        number_format($_POST['total_asset'])."<br>".
                        number_format($_POST['total_liability'])."<br>".
                        $_POST['loan_commision']."<br>".
                        number_format($_POST['total_expense'])."<br>".
                        number_format($_POST['bank_profit'])."<br>".
                        number_format($_POST['account_balance'])."<br>".
                        $_POST['total_bills_income']."<br>".
                        $_POST['loan_ready_member_count']."<br>";
                        
            // Reports
            $pdf->writeHTMLCell(90, 
                                0, 
                                $spacer + 40, 
                                $height_offset, 
                                $report, 1, 0, 0, true, 'R', true);
            $pdf->writeHTMLCell(90, 
                                0, 
                                $spacer + 40 + 13 + 130, 
                                $height_offset, 
                                $report, 1, 0, 0, true, 'R', true);
            $pdf->SetFontSize(22);
            // Date
            $pdf->writeHTMLCell(40, 
                                0, 
                                $spacer, 
                                $height_offset, 
                                $data, 1, 0, 0, true, 'L', true);
            $pdf->writeHTMLCell(40, 
                                0, 
                                $spacer + 13 + 130, 
                                $height_offset, 
                                $data, 1, 0, 0, true, 'L', true);
            
                                
            //Close and output PDF document
            ob_end_clean();
            $pdf->Output('Reports_List_'.$year.'_'.$month, 'I');  
        }
    }
    
            
    $conn -> close();
    //============================================================+
    // END OF FILE
    //============================================================+
    
?>