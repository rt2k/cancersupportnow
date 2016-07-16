<p class='contentHeader'>Contact Information</p>
<hr/>
<div id='contactAddress'>
Cancer Support Now, Inc.<br/>
PO Box 37338<br/>
Albuquerque, NM 87176<br/><br/>
</div>
Helpline Tel: 505-255-0405, 855-955-3500<br/><br/>
Email: <a href='mailto:info@cancersupportnow.org'>info@cancersupportnow.org</a>
<br/><br/>
Facebook: <a href="https://www.facebook.com/cancersupportnow">https://www.facebook.com/cancersupportnow</a>
<br/><br/>
<!--p>For any website technical issue, <a href='mailto:rt2k101@gmail.com'>email webmaster</a>.</p-->
<p>If you experience any website technical issue, send a message to webmaster.</p>

<label>Your Name:</label><br/>
<input type='text' id='name' name='name' size='40'/><br/><br/>
<label>Your Email:</label><br/>
<input type='text' id='email' name='email' size='40'/><br/><br/>
<label>Message:</label><br/>
<textarea id='message' name='message' cols='35' rows='10'></textarea><br/>
<input type='button' id='sendbtn' value='Send' onclick='sendMessage();'/>
<input type='button' value='Clear' onclick='clearMessage();'/>


<script>
function sendMessage() {
    var name = $('#name').val().trim();
    var email = $('#email').val().trim();
    var message = $('#message').val().trim();
    if (!name || !email || !message) {
        alert('Please enter all required information.');
        return;
    }

    var url = 'getMessage.php';
    
    $('body').css('cursor', 'wait');
    $('#sendbtn').prop('disabled', true);

    $.post(url,{
        name: name,
        email: email,
        message: message
    })
    .done(function(){
        clearMessage();
        $('body').css('cursor', 'default');
        $('#sendbtn').prop('disabled', false);
        alert('Message sent successfully.');
    }); 
}

function clearMessage() {
    $('#name').val('');
    $('#email').val('');
    $('#message').val('');
}

</script>
