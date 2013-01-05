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
<?php
function delete()
{
	global $DB_PREFIX;
	echo('	<h2>Borrar usuario</h2>
      <p>El usuario ha sido borrado.</p>');
	$con = connect();
	mysql_query("LOCK TABLES ". $DB_PREFIX . "_users WRITE");
	mysql_query("DELETE FROM ". $DB_PREFIX . "_users WHERE userID = ' " . $_REQUEST['user_id'] . " '");
	mysql_query("UNLOCK TABLES");
	
	mysql_query("LOCK TABLES ". $DB_PREFIX . "_user_tours WRITE");	
	mysql_query("DELETE FROM ". $DB_PREFIX . "_user_tours WHERE userID = ' " . $_REQUEST['user_id'] . " '");
	mysql_query("UNLOCK TABLES");	
	disconnect($con);
}

if (isset ($_REQUEST['confirm'])) delete();
else
{
	echo('<h2>Borrar usuario</h2><p>¿Estás seguro de querer eliminar el socio?</p>');
	echo('<p><a href="'.$RS_URL.'index.php?action=delete_user&amp;user_id=' . $_REQUEST['user_id'] . '&confirm">S&iacute;, quiero eliminarlo junto con todos los datos relacionados con el socio.</a></p>');
}
?>
<p><a href="'.$RS_URL.'index.php?action=edit_user">Volver</a></p>