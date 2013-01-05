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
<?php
include "connect.php";
include "stats.php";
include "parse_route.php";

global $DB_PREFIX;
?>
<h1>Administraci&oacute;n</h1>
<h2>Nueva ruta</h2>
<?php
	$newRoute = 'Nueva Ruta';
	$con = connect();
	mysql_query("LOCK TABLES ". $DB_PREFIX . "_routes WRITE");
	mysql_query("INSERT INTO ". $DB_PREFIX . "_routes (name) VALUES ('" . $newRoute . "')");
	mysql_query("UNLOCK TABLES");
	$routes = mysql_query("SELECT routeID FROM ". $DB_PREFIX . "_routes WHERE name = '" . $newRoute . "'");
	$route = $routes ? mysql_fetch_array($routes) : '0';
	disconnect($con);
	echo('<p>' . $newRoute . ' ha sido añadida.</p>');
	echo('<p>Ahora puedes <a href="'.$RS_URL.'index.php?action=edit_route&amp;route_id=' . $route['routeID'] . '">editarla</a></p>');
?>  