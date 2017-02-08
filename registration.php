<p class='contentHeader'>Account Registration</p>
<hr/>
<br/>
<br/>
<br/>

<?php
    require_once('getConnection.php');

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $conn = getConnection();
        $username = trim($_POST['username']);

        // check if username exists
        $rs = pg_prepare($conn, 'check_username', "SELECT * FROM ccs.users WHERE username = $1");
        $rs = pg_execute($conn, 'check_username', array($username));
        $user = pg_fetch_assoc($rs);
        if ($user !== false) {
            print '<p class="warning">Username exists! Please choose another username.</p>';
        } else {
            $salt = hash("sha512", rand() . rand() . rand());
            $password = hash("sha512", $_POST['password'] . $salt);
            $param = array($username, $password, $_POST['name'], $salt, $_POST['email']);
            $rs = pg_prepare($conn, 'save_reg', 
                "INSERT INTO ccs.users (username, password, name, salt, email) 
                 VALUES ($1, $2, $3, $4, $5);");
            $rs = pg_execute($conn, 'save_reg', $param);
            pg_close($conn);
            if (!$rs) {
                error_log('Registration failed. ');
                print '<p class="warning">Registration Failed.</p>';
            } else {
                print '<p>Registration successful. You are now able to <a href="index.php?gt=login">login</a>.</p>';
            }
        }
    }
?>

<div id='registration'>
<form action='index.php?gt=registration' method='post' onsubmit='return validateForm();'>
<p>Note: <i>username should contain letters, numbers and _ only and not begin with a number.</i></p>
<table>
    <tr><td>Full Name:</td><td><input type='text' id='name' name='name'/></td></tr>
    <tr><td>Email Address:</td><td><input type='text' id='email' name='email'/></td></tr>
    <tr><td>Username:</td><td><input type='text' id='username' name='username'/></td></tr>
    <tr><td>Password:</td><td><input type='password' id='password' name='password'/></td></tr>
    <tr><td>Confirm Password:</td><td><input type='password' id='confirm_password' name='confirm_password'/></td></tr>
    <tr><td></td><td align='right'><input type='submit' class='button' value='Submit'/></td></tr>
</table>
</form>
</div>

<script>
function validateForm(){
    var username = $.trim($('#username').val());
    var password = $('#password').val();
    var password2 = $('#confirm_password').val();
    if (password !== password2) {
        alert('Password not match.');
        return false;
    }
    if (!/^[a-zA-Z_]+[a-zA-Z0-9_]*$/.test(username)) {
        alert('username should contain letters, numbers and _ only and not begin with a number.');
        return false;
    }
    return true;
}
</script>
