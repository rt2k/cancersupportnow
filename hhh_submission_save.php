<?php
ob_start();
date_default_timezone_set('America/Denver');
require_once('recaptcha/src/autoload.php');
require_once('getConnection.php');
require_once('captchaSecret.inc');

$nominatorName = $_POST['nominator_name'];
$nominatorTitle = $_POST['nominator_title'];
$nominatorOrganization = $_POST['nominator_organization'];
$nominatorDepartment = $_POST['nominator_department'];
$nominatorAddress = $_POST['nominator_address'];
$nominatorCity = $_POST['nominator_city'];
$nominatorState = $_POST['nominator_state'];
$nominatorZip = $_POST['nominator_zip'];
$nominatorPhone = $_POST['nominator_phone'];
$nominatorEmail = $_POST['nominator_email'];

$informationIds = '{';
$iids = array();
if (isset($_POST['info_csn'])) $iids[] = $_POST['info_csn'];
if (isset($_POST['info_volunteer'])) $iids[] = $_POST['info_volunteer'];
$informationIds .= implode(',', $iids);
$informationIds .='}';

// Generate nominator info for email
$nominatorInfo = "
    <fieldset><legend>Nominator Info</legend>
    <table width='100%' border='1' style='border-collapse: collapse'>
    <tr><td width='30%'>Name:</td><td>$nominatorName</td></tr>
    <tr><td>Title:</td><td>$nominatorTitle</td></tr>
    <tr><td>Organization:</td><td>$nominatorOrganization</td></tr>
    <tr><td>Department:</td><td>$nominatorDepartment</td></tr>
    <tr><td>Address:</td><td>$nominatorAddress</td></tr>
    <tr><td>City:</td><td>$nominatorCity</td></tr>
    <tr><td>State:</td><td>$nominatorState</td></tr>
    <tr><td>Zip:</td><td>$nominatorZip</td></tr>
    <tr><td>Phone:</td><td>$nominatorPhone</td></tr>
    <tr><td>Email:</td><td>$nominatorEmail</td></tr>";
if (sizeof($iids)) {
    $nominatorInfo .= "<tr><td colspan=2>I want info on " . 
        (in_array(1, $iids) ? '<u>Cancer Support Now</u>' : '') . ' ' .
        (in_array(2, $iids) ? '<u>Volunteer Opportunities</u>' : '') .
        '</td></tr>';
}
$nominatorInfo .= '</table></fieldset>';

$honoreeName = $_POST['honoree_name'];
$honoreeTitle = $_POST['honoree_title'];
$honoreeOrganization = $_POST['honoree_organization'];
$honoreeDepartment = $_POST['honoree_department'];
$honoreeAddress = $_POST['honoree_address'];
$honoreeCity = $_POST['honoree_city'];
$honoreeState = $_POST['honoree_state'];
$honoreeZip = $_POST['honoree_zip'];
$honoreePhone = $_POST['honoree_phone'];
$honoreeEmail = $_POST['honoree_email'];
$honoreeType = $_POST['honoree_type'];

// honoree info for email
$honoreeLabel = array('1'=>'Physician', '2'=>'Nurse', '3'=>'Other healthcare Provider', '4'=>'Business', '5'=>'Volunteer/Individual');
$honoreeInfo = "
    <fieldset><legend>Honoree Info</legend>
    <table width='100%' border='1' style='border-collapse: collapse'>
    <tr><td width='30%'>Name:</td><td>$honoreeName</td></tr>
    <tr><td>Title:</td><td>$honoreeTitle</td></tr>
    <tr><td>Organization:</td><td>$honoreeOrganization</td></tr>
    <tr><td>Department:</td><td>$honoreeDepartment</td></tr>
    <tr><td>Address:</td><td>$honoreeAddress</td></tr>
    <tr><td>City:</td><td>$honoreeCity</td></tr>
    <tr><td>State:</td><td>$honoreeState</td></tr>
    <tr><td>Zip:</td><td>$honoreeZip</td></tr>
    <tr><td>Phone:</td><td>$honoreePhone</td></tr>
    <tr><td>Email:</td><td>$honoreeEmail</td></tr>
    <tr><td colspan=2>Honoree is a ". $honoreeLabel[$honoreeType]. "</td></tr>
    </table></fieldset>";

$narrative = $_POST['narrative'];
$year = $_POST['year'];

$captchaResponse = $_POST['g-recaptcha-response'];

$recaptcha = new \ReCaptcha\ReCaptcha($captchaSecret);
$resp = $recaptcha->verify($captchaResponse, $_SERVER['REMOTE_ADDR']);
$honoreeId = -1;
if ($resp->isSuccess()) {
    // verified! save info.
    $conn = getConnection();
    if (!$conn) {
        error_log("Can't establish connection to database.");
        print 'Connection error.';
        exit;
    }
    // save honoree info
    $rs = pg_prepare($conn, 'save_honoree', 
        "INSERT INTO ccs.honoree (
            name, 
            title, 
            organization, 
            department, 
            address, 
            city, 
            state, 
            zip, 
            phone, 
            email, 
            honoree_type_id, 
            narrative
        ) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12) RETURNING id;");
    $param = array(
        $honoreeName,
        $honoreeTitle,
        $honoreeOrganization,
        $honoreeDepartment,
        $honoreeAddress,
        $honoreeCity,
        $honoreeState,
        $honoreeZip,
        $honoreePhone,
        $honoreeEmail,
        $honoreeType, 
        $narrative
    );
    $rs = pg_execute($conn, 'save_honoree', $param);
    if ($rs === false) {
        pg_close($conn);
        error_log("Failed saving honoree.");
        print "Honoree query error.";
        exit;
    }
    $row = pg_fetch_row($rs);
    $honoreeId = $row[0];

    // save nominator info
    $rs = pg_prepare($conn, 'save_nominator', 
        "INSERT INTO ccs.nominator (
        name, 
        title, 
        organization, 
        department, 
        address, 
        city, 
        state, 
        zip, 
        phone, 
        email, 
        information_type_ids
    ) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11) RETURNING id;");
    $param = array(
        $nominatorName,
        $nominatorTitle,
        $nominatorOrganization,
        $nominatorDepartment,
        $nominatorAddress,
        $nominatorCity,
        $nominatorState,
        $nominatorZip,
        $nominatorPhone,
        $nominatorEmail,
        $informationIds
    );
    $rs2 = pg_execute($conn, 'save_nominator', $param);
    if ($rs2 === false) {
        pg_close($conn);
        error_log("Failed saving nominator.");
        print "Nominator query error.";
        exit;
    }
    $row = pg_fetch_row($rs2);
    $nominatorId = $row[0];

    // insert into table ccs.hope_healing_honor_event
    $currentTime = date("Y-m-d H:i:s", time());
    $rs = pg_prepare($conn, 'save_event', 
        "INSERT INTO ccs.hope_healing_honor_event (year, nominator_id, honoree_id, submission_time)
         VALUES ($1,$2,$3,$4);");
    $params = array($year, $nominatorId, $honoreeId, $currentTime);

    $rs3 = pg_execute($conn, 'save_event', $params);
    if ($rs === false) {
        pg_close($conn);
        error_log("Failed adding event.");
        print "hhh event query error.";
        exit;
    }

    pg_close($conn);
    // send confirmation email to user
    $subject = "Thank you for your submission";
    $message = "<html><body>Dear $nominatorName,<br/><br/>
        Thank you for your nomination. Here is the information you submitted to us.<br/><br/>
        $nominatorInfo<br/><br/>
        $honoreeInfo<br/><br/>
        And what you wrote about your honoree:<br/>
        <i>$narrative</i>
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
    mail(strip_tags($nominatorEmail), $subject, $message, $header);

    print '1';
} else {
    $errors = $resp->getErrorCodes();
    error_log(json_encode($errors));
    print '0';
}
/*
// escape quotes (' ") and ( \ )
function sanitizePostData(&$post) {
    foreach ($post as $key => $val) {
        // slash should be escaped first as escaping quotes adding slash
        $newVal = str_replace('\\', '\\\\', $val);
        $newVal = str_replace("'", "\'", $newVal);
        $newVal = str_replace('"', '\"', $newVal);
        $post[$key] = $newVal;
    }
}*/
