<?php
    include './connection.php';

    function update_ifstatus($conn, $switchip, $csvpath) {
        $conn->query("UPDATE portas SET ip=NULL");

        exec("bash /var/www/switch-manager/scripts/ifstatus.sh $switchip");
        $updatestmt = $conn->prepare("UPDATE portas SET status=? WHERE porta=?");
        $open = fopen($csvpath, 'r');
        $data = fgetcsv($open, 1000, ",");
        while(($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            $porta = (int)$data[0];
            $status = (int)$data[1];
            $updatestmt->bind_param("ii", $status, $porta);
            $updatestmt->execute();
        }
        fclose($open);
    }

    function update_ifip($conn, $switchip, $cvspath) {
        $conn->query("UPDATE portas SET ip=NULL");

        exec("bash /var/www/switch-manager/scripts/ipscript.sh $switchip");

        $updatestmt = $conn->prepare("UPDATE portas SET ip=? WHERE porta=?");
        $open = fopen($cvspath, 'r');
        $data = fgetcsv($open, 1000, ",");
        while(($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            $ip = $data[0];
            $porta = (int)$data[1];
            echo "$ip:$porta <br>";
            $updatestmt->bind_param("si", $ip, $porta);
            $updatestmt->execute();
        }
        fclose($open);
    }
?>