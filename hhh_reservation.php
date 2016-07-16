<p class="contentHeader">Hope and Healing Honors Reservation</p>
<hr/>
<br/>
<div style='text-align:center;'><img src='images/hhh.png' /></div>
<br/>
<center><p style='font-size:17pt;'>Please submit reservations on or no later than September 12, 2016</p></center>
<br/>
<div style='margin-left: 300px; font-size:14pt;'>
<form class='submission_form' action='index.php?gt=hhhreserveconf' method='post' onsubmit='return validateForm();'>
<input type='hidden' id='year' name='year' value='2016'/>
<table style='font-size:14pt;'>
<tr><td class='required'>Name:</td><td><input type='text' id='attendant_name' name='attendant_name' maxlength='29'/></td></tr>
<tr><td>Title:</td><td><input type='text' id='attendant_title' name='attendant_title' maxlength='10'/></td></tr>
<tr><td>Organization:</td><td><input type='text' id='attendant_organization' name='attendant_organization' maxlength='49' /></td></tr>
<tr><td>Department:</td><td><input type='text' id='attendant_department' name='attendant_department' maxlength='19'/></td></tr>
<tr><td>Address:</td><td><input type='text' id='attendant_address' name='attendant_address' maxlength='100'/></td></tr>
<tr><td>City:</td><td><input type='text' id='attendant_city' name='attendant_city' maxlength='20'/></td></tr>
<tr><td>State:</td><td><input type='text' id='attendant_state' name='attendant_state' maxlength='2'/></td></tr>
<tr><td>Zip:</td><td><input type='text' id='attendant_zip' name='attendant_zip' maxlength='5'/></td></tr>
<tr><td class='required'>Phone:</td><td><input type='text' id='attendant_phone' name='attendant_phone' maxlength='15'/></td></tr>
<tr><td class='required'>Email:</td><td><input type='text' id='attendant_email' name='attendant_email' maxlength='60'/></td></tr>
</table>
<br/>
<label class='required'>Please check one:</label><label style='font-size:small'><i>(You can make payment or donation after submission)</i></label><br/>
<input type='radio' id='confirm_yes' name='confirm' value='yes'/>
<label>Yes, I will be attending the Hope and Healing Awards</label><br/>
<div style='margin-left:30px;'>
<input type='checkbox' id='ticket_base' name='ticket_base' disabled/>
<label>Please reserve <input type='text' size='5' disabled id='num_ticket' name='num_ticket'/> tickets at $65 each.</label><br/>
<input type='checkbox' id='table_base' name='table_base'  disabled/>
<label>Please reserve <input type='text' size='5' disabled id='num_table' name='num_table'/> tables of 9 at $575 each.</label><br/>
</div>
<input type='radio' id='confirm_no' name='confirm' value='no'/>
<label>No, I cannot attend. Please accept a donation of $<input type='text' disabled size='8' id='donate_amount' name='donate_amount'/></label>
<br/>
<br/>
<div class="g-recaptcha" data-sitekey="6LdLNyMTAAAAAJzqrPwMAlU6vW-1IaCiCmPNzMip"></div>
<br/>
<input type='submit' value='Submit'/>
</form>
</div>

<script>
$(document).ready(function() {
    // after user submit the reservation and click `go back`, the disabled field in effect
    // should enable them if appropriate.
    var reservation = $('input[name="confirm"]:checked').val();
    if (reservation == 'no') {
        $('#donate_amount').prop('disabled', false);
    } else {
        if ($('#ticket_base').is(':checked')) {
            $('#ticket_base').prop('disabled', false);
            $('#num_ticket').prop('disabled', false);
        }
        if ($('#table_base').is(':checked')) {
            $('#table_base').prop('disabled', false);
            $('#num_table').prop('disabled', false);
        }
    }
});

// toggle reservation/donation
$('input[name="confirm"]').on('click', function(){
    if (this.value == 'yes') {
        $('input[type="checkbox"]').prop('disabled', false);
        if ($('#ticket_base').is(':checked')) {
            $('#num_ticket').prop('disabled', false);
        }
        if ($('#table_base').is(':checked')) {
            $('#num_table').prop('disabled', false);
        }

        $('#donate_amount').prop('disabled', 'disabled');
    } else {
        $('input[type="checkbox"], #num_ticket, #num_table').prop('disabled', 'disabled');
        $('#donate_amount').prop('disabled', false);
    }
});

$('#ticket_base').on('change', function() {
    if (this.checked) {
        $('#num_ticket').prop('disabled', false);
    } else {
        $('#num_ticket').prop('disabled', 'disabled');
    }
});

$('#table_base').on('change', function() {
    if (this.checked) {
        $('#num_table').prop('disabled', false);
    } else {
        $('#num_table').prop('disabled', 'disabled');
    }
});

function validateForm() {
    var message = '';
    var attendantName = $.trim($('#attendant_name').val());
    var attendantPhone = $.trim($('#attendant_phone').val());
    var attendantEmail = $.trim($('#attendant_email').val());
    if (!attendantName) {
        message += "name is required\n";
    }
    if (!attendantPhone) {
        message += "phone is required\n";
    }
    if (!attendantEmail) {
        message += "email is required\n";
    }

    var reservation = $('input[name="confirm"]:checked').val();
    if (reservation == 'no') {
        var donateAmount = $('#donate_amount').val();
        if (!$.isNumeric(donateAmount)) {
            message += "donation amount must be a number\n";
        }
    } else {
        var ticketChecked = $('#ticket_base').is(':checked');
        var tableChecked = $('#table_base').is(':checked');
        var numTicket = $('#num_ticket').val();
        var numTable = $('#num_table').val();

        if (!ticketChecked && !tableChecked) {
            message += "number of reservation is required\n";
        }
        if (ticketChecked && !($.isNumeric(numTicket) && Math.floor(numTicket) == numTicket)) {
            message += "number of ticket must be a number\n";
        }
        if (tableChecked && !($.isNumeric(numTable) && Math.floor(numTable) == numTalbe)) {
            message += "number of table must be a number\n";
        }
    }
        
    if ( message.length ) {
        alert(message);
        return false;
    }
    return true;
}
</script>
