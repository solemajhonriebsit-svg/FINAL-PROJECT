IT LABORATORY WEBSITE  (Exercises 1 to 8)
Student: Jhonrie Solema | Information Technology | Muntinlupa City
==========================================================================

WHAT IS INCLUDED
--------------------------------------------------------------------------
Exercise 1  Personal profile page ................. Profile section
Exercise 2  PHP greeting + JavaScript live clock .. PHP Activities
Exercise 3  PHP GET method ($_GET) ................ PHP Activities
Exercise 4  PHP POST method ($_POST) .............. PHP Activities
Exercise 5  PHP and JSON labs 1-5 ................. JSON Labs
Exercise 6  XMLHttpRequest ........................ AJAX
Exercise 7  Fetch API (GET and POST) .............. API Testing
Exercise 8  XML document (student_data.xml) ....... XML

No database is required.


HOW TO RUN WITH XAMPP
--------------------------------------------------------------------------
1. Download and install XAMPP (https://www.apachefriends.org). Choose a
   version that includes PHP 7.4 or newer (PHP 8.x recommended).

2. Copy the whole "it-laboratory" folder into XAMPP's htdocs folder:
      Windows : C:\xampp\htdocs\it-laboratory
      macOS   : /Applications/XAMPP/htdocs/it-laboratory
      Linux   : /opt/lampp/htdocs/it-laboratory

3. Open the XAMPP Control Panel and click START next to "Apache".
   (MySQL is not needed.)

4. Open your browser and go to:
      http://localhost/it-laboratory/

IMPORTANT: always open the site through http://localhost/... Do not open
index.php by double-clicking it. PHP and the XML/Fetch features only work
when served by Apache.

If port 80 is busy, change Apache's port in XAMPP (Config > httpd.conf) or
stop the program using it (Skype, IIS, etc.), then use
http://localhost:PORT/it-laboratory/ with your new port number.


FEATURES THAT NEED AN INTERNET CONNECTION
--------------------------------------------------------------------------
* Exercise 6 (XMLHttpRequest) and Exercise 7 (Fetch GET and POST) call
  https://jsonplaceholder.typicode.com. Without internet they show a
  friendly network-error message.
* JSONPlaceholder only SIMULATES creating a post; nothing is permanently
  saved on its server.

Everything else (Exercises 1-5 and 8) works offline on localhost.


PROJECT STRUCTURE
--------------------------------------------------------------------------
it-laboratory/
  index.php            Main page with all eight exercises
  student_data.xml     XML data (Exercise 8)
  assets/
    style.css          Dark navy theme, blue and mint accents, responsive
    script.js          Clock, XHR, Fetch, AJAX, JSON and XML interactions
  includes/
    header.php         Page head and navigation bar
    footer.php         Footer and script loading
    functions.php      Shared helpers (escaping, validation, JSON output)
  actions/
    greeting.php       Exercise 2 handler (included by index.php)
    get-demo.php       Exercise 3 handler (included by index.php)
    post-demo.php      Exercise 4 handler (included by index.php)
    json-api.php       Lab 3 endpoint: returns a user profile as JSON
    json-input.php     Lab 4 endpoint: reads JSON from php://input
    ajax-handler.php   Lab 5 endpoint: receives form data via AJAX
    xml-reader.php     Exercise 8 optional PHP route (SimpleXML -> JSON)


QUICK TEST CHECKLIST
--------------------------------------------------------------------------
Greeting   : submit an empty name (error), then a real name (greeting).
GET        : submit the form and check the ?lab=get&student_name=... URL.
POST       : leave a field empty (error), then submit all fields.
JSON Lab 3 : request user 1, then user 99 (404 error shown).
JSON Lab 4 : send valid data, then click "Send invalid JSON".
JSON Lab 5 : submit with and without valid data; note the loading message.
AJAX       : click "Load Post Data" (internet required).
API Testing: run the Fetch GET and POST demos (internet required).
XML        : load with JavaScript and with PHP; IDs show as 001 and 002.
           : rename student_data.xml temporarily to see the error message.


TROUBLESHOOTING
--------------------------------------------------------------------------
* Blank page or raw PHP code  -> Apache/PHP is not running or the file was
                                 opened directly instead of via localhost.
* 404 Not Found               -> the folder must be named "it-laboratory"
                                 and sit directly inside htdocs.
* XML table shows an error    -> make sure student_data.xml is in the project
                                 root, next to index.php.
