<p class="contentHeader">Hope and Healing Honors Reservations</p>
<hr/>
<br/>

<?php
// Check admin permission
if (!in_array($_SESSION['username'], $adminList)) {
    Print '<p class="warning">Permission Denied!</p>';
    exit;
}

require('getConnection.php');
$conn = getConnection();

print '<p id="summary"></p>';

$query = "SELECT * FROM ccs.hhh_reservation_2016_vw ORDER BY submission_time DESC;";
$rs = pg_query($conn, $query);
pg_close($conn);

$totalDonation = 0;
$totalTicket = 0;
$totalTable = 0;
while($row = pg_fetch_assoc($rs)) {
    printReservation($row);
}

function printReservation(&$row) {
    global $totalDonation, $totalTicket, $totalTable;
    if ($row['donate_amount']) $head = 'Donation';
    else $head = 'Reservation';
    print '<fieldset><legend>' . $head . ' ' . $row['submission_time'] . '</legend>';
    print '<table id="hhh_reservations" width="100%">
        <tr><td width="30%">Name:</td><td>' . $row['name'] . '</td></tr>
        <tr><td>Title:</td><td>' . $row['title'] . '</td></tr>
        <tr><td>Organization:</td><td>' . $row['organization'] . '</td></tr>
        <tr><td>Department:</td><td>' . $row['department'] . '</td></tr>
        <tr><td>Address:</td><td>' . $row['address'] . '</td></tr>
        <tr><td>City:</td><td>' . $row['city'] . '</td></tr>
        <tr><td>State:</td><td>' . $row['state'] . '</td></tr>
        <tr><td>Zip:</td><td>' . $row['zip'] . '</td></tr>
        <tr><td>Phone:</td><td>' . $row['phone'] . '</td></tr>
        <tr><td>Email:</td><td>' . $row['email'] . '</td></tr>';
    if ($row['donate_amount']) {
        print '<tr><td>Donation:</td><td>$' . $row['donate_amount'] . '</td></tr>';
        $totalDonation += $row['donate_amount'];
    } else {
        if ($row['num_ticket']) {
            print '<tr><td>Tickets:</td><td>' . $row['num_ticket'] . '</td></tr>';
            $totalTicket += $row['num_ticket'];
        }
        if ($row['num_table']) {
            print '<tr><td>Tables:</td><td>' . $row['num_table'] . '</td></tr>';
            $totalTable += $row['num_table'];
        }
    }

    print '</table></fieldset>';
    print '<br/>';
}

?>

<script>
$(document).ready(function(){
    var summary = 'Total donation amount: $<?php echo $totalDonation;?><br/>'
                + 'Total number of ticket: <?php echo $totalTicket;?><br/>'
                + 'Total number of table: <?php echo $totalTable;?><br/>'
                + 'Total donation and payment: $<?php echo ($totalDonation + $totalTicket * $hhhTicketCost + $totalTable * $hhhTableCost);?>';
    $('#summary').html(summary);
    $('#hhh_reservations tr').on('mouseover', function(){
        $(this).css('background-color', '#CEE3F6');
    });
    $('#hhh_reservations tr').on('mouseout', function(){
        $(this).css('background-color', 'transparent'); 
    });
});
</script>

