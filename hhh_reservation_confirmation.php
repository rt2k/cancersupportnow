<?php
if (!isset($_POST) || !isset($_POST['attendant_name'])) {
    header('location: index.php?gt=hhhreserve');
    exit;
}
?>

<p class="contentHeader">Hope and Healing Honors Reservation Confirmation</p>
<hr/>
<br/>

<?php
require_once('getConnection.php');
require_once('recaptcha/src/autoload.php');
date_default_timezone_set('America/Denver');

$attendantName = trim($_POST['attendant_name']);
$attendantTitle = $_POST['attendant_title'];
$attendantOrganization = $_POST['attendant_organization'];
$attendantDepartment = $_POST['attendant_department'];
$attendantAddress = $_POST['attendant_address'];
$attendantCity = $_POST['attendant_city'];
$attendantState = $_POST['attendant_state'];
$attendantZip = $_POST['attendant_zip'];
$attendantPhone = $_POST['attendant_phone'];
$attendantEmail = $_POST['attendant_email'];
$year = $_POST['year'];

$numTicket = null;
$numTable = null;
$donateAmount = null;

// parse attendant name to get first/last name
$tmp = explode(' ', $attendantName);
$firstName = $tmp[0];
$lastName = $tmp[sizeof($tmp) -1];

$willAttend = $_POST['confirm'];
$confirmation= '';
if ($willAttend == 'yes') {
    if (isset($_POST['ticket_base'])) {
        $numTicket = $_POST['num_ticket'];
    } 

    if (isset($_POST['table_base'])) {
        $numTable = $_POST['num_table'];
    }
    $totalAmount = $numTicket * $hhhTicketCost + $numTable * $hhhTableCost;
    $confirmation = '<p>Thank you for your reservation with ' . 
        ($numTicket ? "$numTicket ticket(s) at $" . $hhhTicketCost . ' each ' : '') . 
        ($numTable ? "$numTable table(s) of 9 at $" . $hhhTableCost . ' each' : '') . '!<br/> 
        You will receive a confirmation email soon. 
        Please check the spam folder if you don\'t see it in the inbox.</p>
        <p>Now you can make a total payment of $' . $totalAmount . ' via Paypal.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="business" value="WSAHQZR62XRT6">';
    if ($numTicket) $confirmation .= '
        <input type="hidden" name="item_name_1" value="hhh ticket">
        <input type="hidden" name="quantity_1" value="' . $numTicket . '">
        <input type="hidden" name="amount_1" value="' . $hhhTicketCost . '">';
    if ($numTable) $confirmation .= '
        <input type="hidden" name="item_name_2" value="hhh table of 9">
        <input type="hidden" name="quantity_2" value="' . $numTable . '">
        <input type="hidden" name="amount_2" value="' . $hhhTableCost . '">';
    $confirmation .= '
        <INPUT TYPE="hidden" NAME="first_name" VALUE="' . $firstName . '">
        <INPUT TYPE="hidden" NAME="last_name" VALUE="' . $lastName . '">
        <INPUT TYPE="hidden" NAME="address1" VALUE="' . $attendantAddress . '">
        <INPUT TYPE="hidden" NAME="city" VALUE="' . $attendantCity . '">
        <INPUT TYPE="hidden" NAME="state" VALUE="' . $attendantState. '">
        <INPUT TYPE="hidden" NAME="zip" VALUE="' . $attendantZip . '">
        <INPUT TYPE="hidden" NAME="lc" VALUE="US">
        <INPUT TYPE="hidden" NAME="email" VALUE="' . $attendantEmail . '">
        <INPUT TYPE="hidden" NAME="night_phone_a" VALUE="' . $attendantPhone . '">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>';
} else {
    $donateAmount = $_POST['donate_amount'];
    $confirmation = '<p>Thank you for considering donation! 
        You will receive a confirmation email soon. 
        Please check the spam folder if you don\'t see it in the inbox.</p>
        <p>Now you make a donation of $' . $donateAmount . ' via Paypal.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_donations">
        <input type="hidden" name="business" value="WSAHQZR62XRT6">
        <input type="hidden" name="item_name" value="Cancer Support Now Inc.">
        <input type="hidden" name="amount" value="' . $donateAmount . '">
        <INPUT TYPE="hidden" NAME="first_name" VALUE="' . $firstName . '">
        <INPUT TYPE="hidden" NAME="last_name" VALUE="' . $lastName . '">
        <INPUT TYPE="hidden" NAME="address1" VALUE="' . $attendantAddress . '">
        <INPUT TYPE="hidden" NAME="city" VALUE="' . $attendantCity . '">
        <INPUT TYPE="hidden" NAME="state" VALUE="' . $attendantState. '">
        <INPUT TYPE="hidden" NAME="zip" VALUE="' . $attendantZip . '">
        <INPUT TYPE="hidden" NAME="lc" VALUE="US">
        <INPUT TYPE="hidden" NAME="email" VALUE="' . $attendantEmail . '">
        <INPUT TYPE="hidden" NAME="night_phone_a" VALUE="' . $attendantPhone . '">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>';
}

// construct message for email to user
$attendantInfo = "
    <table width='100%' border='1' style='border-collapse: collapse'>
    <tr><td width='30%'>Name:</td><td>$attendantName</td></tr>
    <tr><td>Title:</td><td>$attendantTitle</td></tr>
    <tr><td>Organization:</td><td>$attendantOrganization</td></tr>
    <tr><td>Department:</td><td>$attendantDepartment</td></tr>
    <tr><td>Address:</td><td>$attendantAddress</td></tr>
    <tr><td>City:</td><td>$attendantCity</td></tr>
    <tr><td>State:</td><td>$attendantState</td></tr>
    <tr><td>Zip:</td><td>$attendantZip</td></tr>
    <tr><td>Phone:</td><td>$attendantPhone</td></tr>
    <tr><td>Email:</td><td>$attendantEmail</td></tr>";
    
if ($willAttend == 'yes') {
    $attendantInfo .= "<tr><td>Number of Tickets:</td><td>$numTicket</td></tr>
        <tr><td>Number of tables:</td><td>$numTable</td></tr>";
} else {
    $attendantInfo .= "<tr><td>Donation:</td><td>$" . $donateAmount . "</td></tr>";
}
$attendantInfo .= '</table>';
// end of message

$currentTime = date("Y-m-d H:i:s", time());

$captchaResponse = $_POST['g-recaptcha-response'];

$recaptcha = new \ReCaptcha\ReCaptcha($captchaSecret);
$resp = $recaptcha->verify($captchaResponse, $_SERVER['REMOTE_ADDR']);
if ($resp->isSuccess()) {
    // verified! save info.
    $conn = getConnection();
    if (!$conn) {
        error_log('Cannot establish connection to database.');
        print 'There is a network problem. Please try again later.';
        exit;
    }

    $rs = pg_prepare($conn, 'add_reservation', "INSERT INTO ccs.hhh_reservation (
    name,title,organization,department,address,city,state,zip,phone,email,num_ticket,num_table,donate_amount,year,submission_time)
    VALUES ($1, $2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15);");
    $param = array(
        $attendantName,
        $attendantTitle,
        $attendantOrganization,
        $attendantDepartment,
        $attendantAddress,
        $attendantCity,
        $attendantState,
        $attendantZip,
        $attendantPhone,
        $attendantEmail,
        $numTicket,
        $numTable,
        $donateAmount,
        $year,
        $currentTime);

    $rs = pg_execute($conn, 'add_reservation', $param);
    if ($rs === false) {
        error_log("reservation insertion query failed.");
        print "Sorry. There is an error. Your submission is not saved. Please call us with the number in the <contact> page.<br/><br/>";
    } else {
        // Send email to user
        $subject = 'Thank you for your submission';
        $message = "Dear $attendantName,<br/><br/>
            Thank you for your submission. Here is the information you submitted to us.<br/><br/>
            $attendantInfo
            <br/><br/><br/>
            Cancer Support Now, Inc.<br/>
            PO Box 37338<br/>
            Albuquerque, NM 87176<br/><br/>

            Helpline Tel: 505-255-0405, 855-955-3500<br/>
            Email: info@cancersupportnow.org <br/>
            Facebook: https://www.facebook.com/cancersupportnow
            </body></html>";
        $header = "From: CSN<info@cancersupportnow.org>\r\n";
        $header .= "BCC: rt2k101@gmail.com\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail(strip_tags($attendantEmail), $subject, $message, $header);

        print $confirmation;
    }
} else {
    print "recaptcha not passed. Please prove you are not a robot before submission.<br/><br/>";
}
