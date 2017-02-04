<p class='contentHeader'>Admin Login</p>
<hr/>
<br/>
<br/>
<br/>

<?php
    require_once('getConnection.php');
    $conn = getConnection();

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        if (!in_array($username, $adminList)) {
            print '<p class="warning">You are not admin. Login is not allowed.</p>';   
        } else {
            $password = $_POST['password'];
            $rs = pg_prepare($conn, 'getUser', "SELECT * FROM ccs.users WHERE username=$1");
            $rs = pg_execute($conn, 'getUser', array($username));
            $user = pg_fetch_assoc($rs);
            if (!$user || $user['password'] !== hash("sha512", $password . $user['salt'])) {
                print "<p class='warning'>username or password is not correct.</p>";
            } else {
                // Set session data
                if (!isset($_SESSION)) { session_start(); }
                $_SESSION['username'] = $username;
                print '<p>You are logged in successfully.</p>';
            }
        }
    } else {
?>

<div id='login'>
<form action='index.php?gt=login' method='post'>
<table>
    <tr><td>Username:</td><td><input type='text' id='username' name='username'/></td></tr>
    <tr><td>Password:</td><td><input type='password' id='password' name='password'/></td></tr>
    <tr><td></td><td align='right'><input type='submit' class='button' value='Login'/></td></tr>
</table>
</form>
</div>
<p>No account yet? <a href='index.php?gt=registration'>Register here</a>.</p>

<?php } ?>
