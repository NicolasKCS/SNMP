<?php
    function blockport($ip, ...$ports) {
        $portParam = "";
        foreach($ports as $port) {
            $portParam = $portParam . " $port";
        }
        
        exec("bash /var/www/switch-manager/scripts/blockport.sh $ip block $portParam");
    }
?>