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
?>
      <h2>Borrar ruta</h2>
      <?php

function delete()
{
	global $DB_PREFIX;
	echo ('<p>La ruta ha sido borrada.</p>');
	$con = connect();
	// mysql_query("LOCK TABLES ". $DB_PREFIX . "_routes WRITE");
	
	// Deleting a route also means delete tours with the route id. And it also means to delete every user_tour where route is the route we are trying to delete. This function performs all these actions.
	$_A_Tours; // Array of tours
	$_A_Tours = mysql_query("SELECT tourID FROM ". $DB_PREFIX . "_tours WHERE routeID = ' " . $_REQUEST['route_id'] . " '");
	
	// deletes user_tours
	while($_rowTour = mysql_fetch_array($_A_Tours))
	{
		$_A_Users = mysql_query("DELETE FROM ". $DB_PREFIX . "_user_tours WHERE tourID = '" . $_rowTour['tourID'] . "'");
	}
	
	// deletes tours
	mysql_query("DELETE FROM ". $DB_PREFIX . "_tours WHERE routeID = '" . $_REQUEST['route_id'] . "'");
	
	// deletes route itself
	mysql_query("DELETE FROM ". $DB_PREFIX . "_routes WHERE routeID = '" . $_REQUEST['route_id'] . "'");
	
	// mysql_query("UNLOCK TABLES");
	disconnect($con);
}

if (isset ($_REQUEST['confirm'])) delete();
else
{
	echo('<p>¿Estás seguro de querer eliminar la ruta?</p>');
	echo('<p><a href="'.$RS_URL.'index.php?action=delete_route&amp;route_id=' . $_REQUEST['route_id'] . '&confirm">S&iacute;, quiero eliminarla junto con todos los datos relacionados con ella.</a></p>');
}
?>