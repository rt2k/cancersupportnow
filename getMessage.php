<?php

$name = $_POST['name'];
$email = $_POST['email'];

$message = 'From: ' . $name . "\n" .
    'Email: ' . $email . "\n" .
    'Message: ' . "\n" . $_POST['message'];

mail('rt2k101@gmail.com,dryflysandy@comcast.net,jeraldcross@comcast.net', 
    'User Message From Cancersupportnow.org',
    $message);
