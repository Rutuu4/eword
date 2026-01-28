<?php
include("../../../database.php");

if (!isset($_POST['id'])) {
    echo "INVALID";
    exit;
}

$id = intval($_POST['id']);

// Only allow FAILED → QUEUED
$check = $conn2->query("SELECT status FROM wp_messages WHERE id = $id");
$row = $check->fetch_assoc();

if ($row['status'] !== 'failed') {
    echo "NOT_ALLOWED";
    exit;
}

$update = $conn2->query("
    UPDATE wp_messages 
    SET status = 'queued', updated_at = NOW() 
    WHERE id = $id
");

echo $update ? "SUCCESS" : "ERROR";
