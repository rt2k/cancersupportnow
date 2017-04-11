<?php
session_start();
include('../checkSession.inc');

require_once('../getConnection.php');

$conn = getConnection();
$id = $_POST['id'];
$user = $_SESSION['username'];

$query = "UPDATE ccs.csn_services SET flyer_file_name = null WHERE id = $id;
    INSERT INTO ccs.csn_services_hist (id, notes, mod_date, mod_user)
    VALUES ($id, 'delete flyer', now(), '$user');";

$rs = pg_query($conn, $query);
if ($rs === false) {
    error_log("Failed query: $query");
    echo 0;
} else {
    echo 1;
}

pg_close($conn);
