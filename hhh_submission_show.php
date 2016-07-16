<p class="contentHeader">Hope and Healing Honors Submissions</p>
<hr/>
<br/>

<?php
// Check admin permission
if (!in_array($_SESSION['username'], $adminList)) {
    Print '<p class="warning">Permission Denied!</p>';
    exit;
}

require_once('getConnection.php');

$conn = getConnection();

$query = "SELECT * FROM ccs.hhh_event_2016_vw ORDER BY submission_time DESC";
$rs = pg_query($conn, $query);
while($row = pg_fetch_assoc($rs)) {
    printSubmission($row);
}

function printSubmission($row) {
    $infoText = '';
    $info = array();
    if (strpos($row['information_type_ids'], '1')) {
        $info[] = 'Cancer Support Now';
    }
    if (strpos($row['information_type_ids'], '2')) {
        $info[] = 'Volunteer Opportunities';
    }
    if (sizeof($info)) {
        $infoText = "Request Info: " . implode(' & ', $info);
    }

    print '<fieldset><legend>Submission ' . $row['submission_time'] . '</legend>';
    print '<table id="hhh_submissions" width="100%">
        <tr><th width="10%"></th><th width="45%" align="left">Nominator</th><th align="left">Honoree</th></tr>
        <tr><td>Name:</td><td>' . $row['nominator_name'] . '</td><td>' . $row['honoree_name'] . '</td></tr>
        <tr><td>Title:</td><td>' . $row['nominator_title'] . '</td><td>' . $row['honoree_title'] . '<td></tr>
        <tr><td>Organization:</td><td>' . $row['nominator_organization'] . '</td><td>' . $row['honoree_organization'] . '</td></tr>
        <tr><td>Department:</td><td>' . $row['nominator_department'] . '</td><td>' . $row['honoree_department'] . '</td></tr>
        <tr><td>Address:</td><td>' . $row['nominator_address'] . '</td><td>' . $row['honoree_address'] . '</td></tr>
        <tr><td>City:</td><td>' . $row['nominator_city'] . '</td><td>' . $row['honoree_city'] . '</td></tr>
        <tr><td>State:</td><td>' . $row['nominator_state'] . '</td><td>' . $row['honoree_state'] . '</td></tr>
        <tr><td>Zip:</td><td>' . $row['nominator_zip'] . '</td><td>' . $row['honoree_zip'] . '</td></tr>
        <tr><td>Phone:</td><td>' . $row['nominator_phone'] . '</td><td>' . $row['honoree_phone'] . '</td></tr>
        <tr><td>Email:</td><td>' . $row['nominator_email'] . '</td><td>' . $row['honoree_email'] . '</td></tr>
        <tr><td>Other:</td><td>' . $infoText . '</td><td>' . $row['honoree_type'] . '</td></tr>
        <tr><td>Narrative:</td><td colspan=2>' . $row['narrative'] . '</td></tr>
        </table>';
    print '</fieldset>';
    print '<br/>';
}
?>

<script>
$(document).ready(function(){
    $('#hhh_submissions tr').on('mouseover', function(){
        $(this).css('background-color', '#CEE3F6');
    });
    $('#hhh_submissions tr').on('mouseout', function(){
       $(this).css('background-color', 'transparent'); 
    });
});
</script>
