<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    echo 'unauthorized';
    exit;
}

$port_id = $_GET['port_id'];
$action = $_GET['action'];

$stmt = $conn->prepare("SELECT port_number, status FROM ports WHERE id = ?");
$stmt->bind_param("i", $port_id);
$stmt->execute();
$result = $stmt->get_result();
$port = $result->fetch_assoc();
$stmt->close();

if (!$port) {
    echo 'error';
    exit;
}

$new_status = ($action == 'open') ? 1 : 2;

// SNMP settings
$host = 'your_switch_ip';
$community = 'public';
require 'vendor/autoload.php';
use SNMP\Snmp;

$snmp = new Snmp(SNMP::VERSION_2C, $host, $community);

try {
    $snmp->set('1.3.6.1.2.1.2.2.1.7.' . $port['port_number'], 'i', $new_status);

    $new_status_str = ($new_status == 1) ? 'open' : 'closed';
    $stmt = $conn->prepare("UPDATE ports SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status_str, $port_id);
    $stmt->execute();
    $stmt->close();

    echo 'success';
} catch (Exception $e) {
    echo 'error';
}

$conn->close();
?>
