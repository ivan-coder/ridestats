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
	  <h1>Administraci&oacute;n</h1>
	  <h2>Nueva salida</h2>
	  <?php
if (isset($_REQUEST['new_tour_date']) && $_REQUEST['new_tour_date'] == "")
{
	echo('<p>Debes introducir una fecha antes de hacer click en "Añadir salida"</p>');
	echo('<p><a href="edit_tour.php">Volver</a></p>');
}
else if(isset($_REQUEST['new_tour_date']))
{
	$con = connect();
	mysql_query("LOCK TABLES ". $DB_PREFIX . "_tours WRITE");
	$date = date( 'Y-m-d', $_REQUEST['new_tour_date'] );
	mysql_query("INSERT INTO ". $DB_PREFIX . "_tours (tour_date) VALUES ('" . $_REQUEST['new_tour_date'] . "')");
	mysql_query("UNLOCK TABLES");
	$res = mysql_query("SELECT LAST_INSERT_ID()");
	$tourID = mysql_fetch_row($res);
	disconnect($con);
	echo('<p>La nueva salida del ' . $_REQUEST['new_tour_date'] . ' ha sido añadida.</p>');
	echo('<p>Ahora puedes <a href="'.$RS_URL.'index.php?action=edit_tour&amp;tour_id=' . $tourID[0] . '">editarla</a></p>');
}			
?>  