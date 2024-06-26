#!/bin/bash

ip=$1
OID="1.3.6.1.2.1.2.2.1.7"
SNMP="snmpwalk -v1 -c public $ip $OID"

$SNMP |
sed -e '1 i ifIndex,ifAdminStatus' |
sed -e 's/IF-MIB::ifAdminStatus.//g' -e 's/\s=\s\+/,/' -e 's/INTEGER:\s\+\(up\|down\)(//' -e 's/)//' > "/var/www/switch-manager/log/iftable.csv"