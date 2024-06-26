#!/bin/bash

ip=$1
OID="1.3.6.1.2.1.4.20"
SNMP="snmptable -c public -v1 $ip $OID"

$SNMP | 
sed 1,2d | 
sed -e 's/\s\+/,/g' -e 's/^,//' | 
cut -d, -f1,2 > "/var/www/switch-manager/log/iptable.csv"
