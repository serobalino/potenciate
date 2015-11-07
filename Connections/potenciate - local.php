<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_potenciate = "localhost";
$database_potenciate = "potenciate";
$username_potenciate = "root";
$password_potenciate = "";
$potenciate = mysql_pconnect($hostname_potenciate, $username_potenciate, $password_potenciate) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $potenciate);
?>
