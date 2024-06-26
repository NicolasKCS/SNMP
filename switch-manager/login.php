<?php
session_start();
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM administradores WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = TRUE;
        header("Location: dashboard.php");
    } else {
        header("Location: index.php");
    }

    $stmt->close();
}
?>
