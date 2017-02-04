<?php
session_start();
require_once('../getConnection.php');

$conn = getConnection();
$id = $_POST['id'];
$user = $_SESSION['username'];

$query = "DELETE FROM ccs.csn_services WHERE id = $id;
    INSERT INTO ccs.csn_services_hist (id, notes, mod_date, mod_user)
    VALUES ($id, 'delete group', now(), '$user')";

$rs = pg_query($conn, $query);
if ($rs === false) {
    error_log("Failed query: $query");
    echo 0;
} else {
    echo 1;
}

pg_close($conn);
