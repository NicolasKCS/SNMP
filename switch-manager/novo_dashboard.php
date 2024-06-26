<?php
$servername = "localhost";
$username = "switchmanager";
$password = "SwitchmanagerUdesc123";
$dbname = "switchmanagerdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $new_status = $_POST['status'] == 1 ? 2 : 1; // Toggle status between 1 and 2

    $update_sql = "UPDATE your_table_name SET status = $new_status WHERE id = $id";
    if ($conn->query($update_sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT id, port, sala, status FROM your_table_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display MySQL Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>MySQL Table Data</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Port</th>
            <th>Sala</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["port"] . "</td>";
                echo "<td>" . $row["sala"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>";
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='hidden' name='status' value='" . $row["status"] . "'>";
                echo "<input type='submit' name='update_status' value='Toggle Status'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No results found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
