<?php
session_start();
include('../checkSession.inc');

require_once('../getConnection.php');

$user = $_SESSION['username'];
$id = $_POST['id'];
$fileName = sanitizeFileName($_FILES['newFlyer']['name']);

// check if the file name available
if (file_exists('../pdfs/' . $fileName)) {
    $pfix = time();
    exec("mv ../pdfs/$fileName ../pdfs/$fileName-$pfix");
}

if (!$_FILES['newFlyer']['error']) {
    move_uploaded_file($_FILES['newFlyer']['tmp_name'], '../pdfs/' . $fileName);
    // Update database
    $conn = getConnection();
    $query = "UPDATE ccs.csn_services SET flyer_file_name = '$fileName' WHERE id = $id;
        INSERT INTO ccs.csn_services_hist (id, flyer_file_name, mod_date, mod_user)
        VALUES ($id, '$fileName', now(), '$user');";
    $rs = pg_query($conn, $query);
    pg_close($conn);
    if (!$rs) {
        error_log('Query failed: ' . $query);
        echo 'Error uploading flyer!';
        exit;
    }
    echo 1;
} else {
    echo 'Error uploading flyer!';
}

/**
 * Only letter, number and underscore are allowed (except .pdf)
 */
function sanitizeFileName($name) {
    $n = basename($name, '.pdf');
    return preg_replace('/[^a-zA-Z0-9]/', '_', $n) . '.pdf';
}
