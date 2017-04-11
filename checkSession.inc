<?php

$duration = 30 * 60; // 30 minutes;

// expire the session after 30 min of inactivity
if (isset($_SESSION['expire']) && time() - $_SESSION['expire'] > 0) {
    session_unset();
    header('location: ' . $_SERVER['SCRIPT_NAME'] . '?gt=login&e');
    exit;
} else {
    $_SESSION['expire'] = time() + $duration;
}
