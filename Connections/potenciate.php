<?php
error_reporting(0);
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_potenciate = "localhost";
$database_potenciate = "datalloy_potenciate";
$username_potenciate = "datalloy_est";
$password_potenciate = "8Iw@E";
$potenciate = mysql_pconnect($hostname_potenciate, $username_potenciate, $password_potenciate) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $potenciate);
?>