<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    echo 'unauthorized';
    exit;
}

require 'vendor/autoload.php'; // Assuming you are using Composer to manage dependencies
use SNMP\Snmp;

// SNMP settings
$host = 'your_switch_ip';
$community = 'public';
$snmp = new Snmp(SNMP::VERSION_2C, $host, $community);

// SNMP OIDs for ports
$ifIndexOID = '1.3.6.1.2.1.2.2.1.1';
$ifDescrOID = '1.3.6.1.2.1.2.2.1.2';
$ifOperStatusOID = '1.3.6.1.2.1.2.2.1.8';

try {
    $indexes = $snmp->walk($ifIndexOID);
    $descriptions = $snmp->walk($ifDescrOID);
    $statuses = $snmp->walk($ifOperStatusOID);

    // Clear existing entries
    $conn->query("TRUNCATE TABLE ports");

    foreach ($indexes as $index) {
        $portNumber = $descriptions[$ifDescrOID . '.' . $index];
        $status = $statuses[$ifOperStatusOID . '.' . $index] == 1 ? 'open' : 'closed';

        $stmt = $conn->prepare("INSERT INTO ports (port_number, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $portNumber, $status);
        $stmt->execute();
        $stmt->close();
    }

    echo 'success';
} catch (Exception $e) {
    echo 'error';
}

$conn->close();
?>
