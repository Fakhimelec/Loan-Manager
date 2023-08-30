// #############################
// Global Variables -->
// click_source Variable Determines That The Pressed Button Is On Which Page
// Same Procedures Like Search Box Or Header Click Is Seperated According 
// To Page In Charge By This Methos 
let click_source = 'members'

// Persian Digits To English Digits Converter Function
const per2en = s => s.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
// We Have To Have The Month And Year Data Of Present Time Always (Persian Local Date)
// cur_year And cur_month Variables Hold The Present Year And Month
// cur_month_name Variable Holds Current Month Name In Persian
// cur_day Variable Holds Current Day
// cur_week_Day Variable Holds Current Day Name In Persian
var date = new Date()
let cur_year  = Number(per2en(date.toLocaleString('fa-IR', {year: "numeric"})))
let cur_month = Number(per2en(date.toLocaleString('fa-IR', {month: "numeric"})))
let cur_month_name = date.toLocaleString('fa-IR', {month: "long"})
let cur_day  = Number(per2en(date.toLocaleString('fa-IR', {day: "numeric"})))
let cur_week_day = date.toLocaleString('fa-IR', {weekday: 'long'})

// #############################
// Tab Close Code -->
function closeWin() {
  // Get Confirmation To Exit
  if (confirm("مایل به خروج از برنامه هستید ؟")) {
    close();
  }
}
// #############################
// Menu Bar Drop Code -->
function menu_drop(e) {

    var x = document.getElementById("topnav");
    var y = document.getElementById("disblock");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}    
        
// #############################
// Member Search Box Code -->

// #############################
// Member Search Box Code -->
function search_member() {
  var input, filter, table, tr, td, i, txtValue;

  // input Variable Will Hold The Text Written Inside The Search Box
  input = document.getElementById("search_box_input");
  // filter Variable Will Change It To Uppercase, Works For English
  filter = input.value.toUpperCase();
  // table Variable Is A Handle To Table Object
  switch(click_source) {
    case 'members':
      table = document.getElementById("member_table");
      break;
    case 'loans':
      table = document.getElementById("loan_table");
      break;
    case 'expenses':
      table = document.getElementById("expense_table");
      break;
    case 'lottery':
      table = document.getElementById("lottery_table");
      break;
    case 'reports':
      table = document.getElementById("balance_table");
      break;
    case 'payments':
      table = document.getElementById("payment_table");
      break;
  }
  
  // tr Variable Is The Total Rows Of The Members Table
  tr = table.getElementsByTagName("tr");
  // Here We Start To Strob All Rows Of The Members Table To See If 
  // There Is Similarity Between Search Box Text And Row Values Of
  // Column 0 and 1 which Corresponds To Name and Family Name
  for (i = 0; i < tr.length; i++) {
    // ts_fname Variable Holds The Column 0 Value (Name)
    ts_fname = tr[i].getElementsByTagName("td")[2];
    // ts_lname Variable Holds The Column 1 Value (Family Name)
    ts_lname = tr[i].getElementsByTagName("td")[3];
    // Comparing Search Box Text And Text Contents Of Both Name and Family Name Columns
    if (ts_fname || ts_lname) {
      txtValue_name = ts_fname.textContent || ts_fname.innerText;
      txtValue_family = ts_lname.textContent || ts_lname.innerText;
      // Removing The Row That Don't Have A Match With The Search Box Text
      if ((txtValue_name.toUpperCase().indexOf(filter) > -1) || 
          (txtValue_family.toUpperCase().indexOf(filter) > -1)) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function search_box_get_focus() {
  // Delete The Text Inside The Search Box When It Gets Focus
  document.getElementById("search_box_input").value = "";
}

function search_box_lose_focus() {
  // Rewrite The Text Inside The Search Box When It Loses Focus
  document.getElementById("search_box_input").value = "جست و جوی اعضا";
}

// #############################
//  Sort Columns When Header Is Clicked Code -->

//alert(x.innerHTML + " & " + y.innerHTML);
function sort_table(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  switch(click_source) {
    case 'members':
      table = document.getElementById("member_table");
      break;
    case 'loans':
      table = document.getElementById("loan_table");
      break;
    case 'expenses':
      table = document.getElementById("expense_table");
      break;
    case 'lottery':
      table = document.getElementById("lottery_table");
      break;
    case 'reports':
      table = document.getElementById("balance_table");
      break;
    case 'payments':
      table = document.getElementById("payment_table");
      break;
  }
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

// #############################
//  Input Textbox Validation Code -->

function num_validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  // Prevent Value Input If It's Not A Digit And If It Is More Than 3Digit Number
  if( !regex.test(key)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function persian_text_validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[گچپژیلفقهموک ء-ي 0-9]+$|\./;
  // Prevent Value Input If It's Not A Digit And If It Is More Than 3Digit Number
  if( !regex.test(key)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

// #############################
//  Saving And Loading Data To/From CSV File
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%
// Saving Part
function tableToCSV() {
  // Variable to store the final csv data
  var csv_data = [];

  // Get each row data
  var rows = document.getElementsByTagName('tr');
  for (var i = 0; i < rows.length; i++) {

    // Get each column data
    var cols = rows[i].querySelectorAll('td,th');

    // Stores each csv row data
    var csvrow = [];
    for (var j = 0; j < cols.length; j++) {

      // Get the text data of each cell of
      // a row and push it to csvrow
      csvrow.push(cols[j].innerHTML);
    }

    // Combine each column value with comma
    csv_data.push(csvrow.join(","));
  }
  // combine each row data with new line character
  csv_data = csv_data.join('\n');
  
  // Call this function to download csv file 
  
  downloadCSVFile(csv_data);

  /* We will use this function later to download
  the data in a csv file downloadCSVFile(csv_data);
  */
}
function downloadCSVFile(csv_data) {
  
  // Create CSV file object and feed our
  // csv_data into it
  CSVFile = new Blob([csv_data], { type: "text/csv; charset=utf-8;" });

  // Create to temporary link to initiate
  // download process
  var temp_link = document.createElement('a');
  // Added For Supporting UTF-8 Encoding On Excel
  var universal_BOM = "\uFEFF";

  // Download csv file
  switch(click_source) {
    case 'members':
      temp_link.download = "Member_List.csv";
      break;
    case 'loans':
      temp_link.download = "Loan_List.csv";
      break;
    case 'expenses':
      temp_link.download = "Expense_List.csv";
      break;
    case 'lottery':
      temp_link.download = "Lottery_List.csv";
      break;
    case 'reports':
      temp_link.download = "Reports_List.csv";
      break;
    case 'payments':
      temp_link.download = "Payments_List.csv";
      break;
    }
  
  temp_link.href= 'data:text/csv; charset=utf-8,' + encodeURIComponent(universal_BOM + csv_data);

  var url = window.URL.createObjectURL(CSVFile);
  
  // This link should not be displayed
  temp_link.style.display = "none";
  document.body.appendChild(temp_link);

  // Automatically click the link to trigger download
  temp_link.click();
  document.body.removeChild(temp_link);
}


        function show_member(str) {
            var arg = str.split("/");
            var member_num = arg[0];
            var source = arg[1];
            if (str == "") {
                document.getElementById("info").innerHTML = "";
                return;
            } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("info").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET","db_check.php?source=" + source + "&" + 
                                        "member_number=" + member_num,true);
            xmlhttp.send();
            }
        }    
        function show_loan(str) {
            var arg = str.split("/");
            var loan_num = arg[0];
            var source = arg[1];
            if (str == "") {
                document.getElementById("info").innerHTML = "";
                return;
            } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("info").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET","db_check.php?source=" + source + "&" + 
                                        "loan_number=" + loan_num,true);
            xmlhttp.send();
            }
        } 
        function show_expense(str) {
            var arg = str.split("/");
            var expense_num = arg[0];
            var source = arg[1];
            if (str == "") {
                document.getElementById("info").innerHTML = "";
                return;
            } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("info").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET","db_check.php?source=" + source + "&" + 
                                        "expense_number=" + expense_num,true);
            xmlhttp.send();
            }
        }  