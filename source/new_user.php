<?php
/**
		Ride Stats - Statistics system for non-competitive associations.
    Copyright (C) 2008 - Iván Sánchez Luque

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
	if (!isset($_COOKIE['user']))
	{
		header("Location: login_error.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
include "connect.php";
include "stats.php";
include "parse_route.php";

global $DB_PREFIX;
?>
<h2>Nuevo socio</h2>
<?php
  if (isset($_REQUEST['new_user']))
  {
    $con = connect();
    mysql_query("LOCK TABLES ". $DB_PREFIX . "_users WRITE");
    $date = date("Y-m-d");
    mysql_query("INSERT INTO ". $DB_PREFIX . "_users (name, join_date) VALUES ('" . $_REQUEST['new_user'] . "', 
          '" . $date . "')");
    $users = mysql_query("SELECT userID FROM ". $DB_PREFIX . "_users WHERE name = '" . $_REQUEST['new_user'] . "'");
    $user = mysql_fetch_array($users);
    mysql_query("UNLOCK TABLES");
    disconnect($con);
    echo('<p>El nuevo socio ' . $_REQUEST['new_user'] . ' ha sido añadido.</p>');
    echo('<p>Ahora puedes <a href="'.$RS_URL.'index.php?action=edit_user&amp;user_id=' . $user['userID'] . '">editarlo</a></p>');
  }			
?>  