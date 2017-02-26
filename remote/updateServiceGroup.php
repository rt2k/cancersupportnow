<?php
session_start();
require_once('../getConnection.php');

$title = $_POST['group_title'];
$description = $_POST['group_desc'];
$user = $_SESSION['username'];
$id = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
}

$conn = getConnection();
if ($id) {
    // do update
    $query = "UPDATE ccs.csn_services SET title = $1, description = $2 WHERE id = $id;";
} else {
    // do insert
    $query = "INSERT INTO ccs.csn_services (title, description) VALUES ($1, $2) RETURNING id;";
}

$rs = pg_query_params($conn, $query, array($title, $description));
if (!$rs) {
    error_log('Query failed: ' . $query . '<title>:' . $title . '<description>:' . $description);
    exit;
} else {
    if (!$id) {
        $row = pg_fetch_assoc($rs);
        $id = $row['id'];
    }
    $query = "INSERT INTO ccs.csn_services_hist (id, title, description, mod_date, mod_user)
        VALUES ($1, $2, $3, now(), $4);";
    $rs = pg_query_params($conn, $query, array($id, $title, $description, $user));
    if (!$rs) {
        error_log('Adding new CSN service to hist table failed');
    }
}

header('Location: ../index.php?gt=support');
