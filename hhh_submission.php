<p class="contentHeader">Hope and Healing Honors Submission</p>
<hr/>
<br/>

<form id='hhh_submission_form' class='submission_form' ction='hhh_submission_save.php' method='post'>
<!-- current year of event -->
<input type='hidden' value='2016' id='year' name='year' />

<p>Deadline for submission is Monday, August 15, 2016</p>
<p>Honorees will be invited to attend the event as the guests of Cancer Support Now.</p>

<fieldset>
<legend>Nominator Information</legend>
<table>
<tr><td class='required'>Name:</td><td><input type='text' id='nominator_name' name='nominator_name' maxlength='30'/></td></tr>
<tr><td>Title:</td><td><input type='text' id='nominator_title' name='nominator_title' maxlength='5'/></td></tr>
<tr><td>Organization:</td><td><input type='text' id='nominator_organization' name='nominator_organization' maxlength='50'/></td></tr>
<tr><td>Department:</td><td><input type='text' id='nominator_department' name='nominator_department' maxlength='20'/></td></tr>
<tr><td>Address:</td><td><input type='text' id='nominator_address' name='nominator_address' maxlength='100'/></td></tr>
<tr><td>City:</td><td><input type='text' id='nominator_city' name='nominator_city' maxlength='20'/></td></tr>
<tr><td>State:</td><td><input type='text' id='nominator_state' name='nominator_state' maxlength='20'/></td></tr>
<tr><td>Zip:</td><td><input type='text' id='nominator_zip' name='nominator_zip' maxlength='10'/></td></tr>
<tr><td class='required'>Phone:</td><td><input type='text' id='nominator_phone' name='nominator_phone' maxlength='15'/></td></tr>
<tr><td class='required'>Email:</td><td><input type='text' id='nominator_email' name='nominator_email' maxlength='60'/></td></tr>
<tr><td>Send me info on:</td><td>
    <input type='checkbox' id='info_csn' name='info_csn' value=1 /><label for='info_csn'>Cancer Support Now</label>
    <input type='checkbox' id='info_volunteer' name='info_volunteer' value=2 /><label for='info_volunteer'>Volunteer Opportunities</label>
    </td></tr>
</table>
</fieldset>

<br/>
<fieldset>
<legend>Honoree Information</legend>
<table>
<tr><td class='required'>Name:</td><td><input type='text' id='honoree_name' name='honoree_name' maxlength='30'/></td></tr>
<tr><td>Title:</td><td><input type='text' id='honoree_title' name='honoree_title' maxlength='5'/></td></tr>
<tr><td>Organization:</td><td><input type='text' id='honoree_organization' name='honoree_organization' maxlength='50'/></td></tr>
<tr><td>Department:</td><td><input type='text' id='honoree_department' name='honoree_department' maxlength='20'/></td></tr>
<tr><td>Address:</td><td><input type='text' id='honoree_address' name='honoree_address' maxlength='100'/></td></tr>
<tr><td>City:</td><td><input type='text' id='honoree_city' name='honoree_city' maxlength='20'/></td></tr>
<tr><td>State:</td><td><input type='text' id='honoree_state' name='honoree_state' maxlength='20'/></td></tr>
<tr><td>Zip:</td><td><input type='text' id='honoree_zip' name='honoree_zip' maxlength='10'/></td></tr>
<tr><td class='required'>Phone:</td><td><input type='text' id='honoree_phone' name='honoree_phone' maxlength='15'/></td></tr>
<tr><td class='required'>Email:</td><td><input type='text' id='honoree_email' name='honoree_email' maxlength='60'/></td></tr>
<tr><td class='required'>Honoree is a:</td><td>
    <input type='radio' id='honoree_type_1' name='honoree_type' value=1 /><label for='honoree_type_1'>Physician</label>
    <input type='radio' id='honoree_type_2' name='honoree_type' value=2 /><label for='honoree_type_2'>Nurse</label>
    <input type='radio' id='honoree_type_3' name='honoree_type' value=3 /><label for='honoree_type_3'>Other healthcare Provider</label>
    <input type='radio' id='honoree_type_4' name='honoree_type' value=4 /><label for='honoree_type_4'>Business</label>
    <input type='radio' id='honoree_type_5' name='honoree_type' value=5 /><label for='honoree_type_5'>Volunteer/Individual</label>
    </td></tr>
</table>
</fieldset>
<br/>
<label class='required'>A maximum 300 word narrative outlining specific examples that describe how the honoree contributed to the
needs of cancer survivors and their loved ones. Include why you feel they deserve the award. This summary may
be used for publication purposes.</label>
<textarea id='narrative' name='narrative' cols=120 rows=15 style='font-size: 15px'></textarea>
<br/>
Total word count: <span id="display_count">0</span> words. Words left: <span id="word_left">300</span>
<br/><br/>
<div class="g-recaptcha" data-sitekey="6LdLNyMTAAAAAJzqrPwMAlU6vW-1IaCiCmPNzMip"></div>
<br/>
<input type='button' value='Submit' onclick='submitForm();'/>
</form>

<div id='success_popup' style='display:none'>
<p>Thank you for your submission. You will receive a confirmation email soon. Please check your spam folder if you don't see it in the inbox.</p>
</div>

<script>
var wordLimit = 300;
$(document).ready(function() {
    $("#narrative").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > wordLimit) {
            // Split the string on first 300 words and rejoin on spaces
            var trimmed = $(this).val().split(/\s+/, wordLimit).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(wordLimit - words);
        }
    });
}); 

function validateForm() {
    var nominatorName = $.trim($('#nominator_name').val());
    var nominatorPhone = $.trim($('#nominator_phone').val());
    var nominatorEmail = $.trim($('#nominator_email').val());
    var honoreeName = $.trim($('#honoree_name').val());
    var honoreePhone = $.trim($('#honoree_phone').val());
    var honoreeEmail = $.trim($('#honoree_email').val());
    var honoreeType = $('input[name="honoree_type"]:checked').val();
    var narrative = $.trim($('#narrative').val());

    if (!nominatorName || !nominatorPhone || !nominatorEmail || !honoreeName || !honoreePhone || !honoreeEmail || !honoreeType || !narrative) {
        return false;
    }
    return true;
}

function submitForm() {
    if (!validateForm()) {
        alert("Please enter all required information.");
        return;
    }

    var formData = $('#hhh_submission_form').serialize();
    $.ajax({
        type: "POST",
        url: 'hhh_submission_save.php',
        data: formData,
        success: function(response){
            if (response === '1') {
                // clear honoree data
                $('input:text[id^=honoree]').val('');
                $('input[name=honoree_type]').prop('checked', false);
                $('#narrative').val('');
                $('#display_count').text('0');
                $('#word_left').text('300');
                $('#success_popup').dialog({
                    title: "Success",
                    minHeight: 200,
                    minWidth: 400,
                    draggable: false
                });
            } else if (response === '0') {
                alert("recaptcha not verified. Please prove you are not a robot.");
            } else {
                alert("There is a connection problem. Your submission is not successful. Please try again later.");
            }
            grecaptcha.reset();
        },
        fail: function() {
            alert("Submission failed. Please try again.");
        }
    });
}
</script>
