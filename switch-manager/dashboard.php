<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script>
        function togglePort(portId, action) {
            fetch(`toggle_port.php?port_id=${portId}&action=${action}`)
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to toggle port');
                    }
                });
        }

        function updatePorts() {
            fetch('update_ports.php')
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to update ports');
                    }
                });
        }
    </script>
</head>
<body>
    <h1>Port Management</h1>
    <button onclick="updatePorts()">Update Ports</button>
    <table>
        <thead>
            <tr>
                <th>Port Number</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include 'db.php';

                $query = "SELECT * FROM ports";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['port_number']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>";
                    if ($row['status'] === 'open') {
                        echo "<button onclick=\"togglePort({$row['id']}, 'close')\">Close</button>";
                    } else {
                        echo "<button onclick=\"togglePort({$row['id']}, 'open')\">Open</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
