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
<script src="source/datepicker/datepicker.js" type="text/javascript"></script>
<link href="source/datepicker/style.css" rel="stylesheet" type="text/css" />
	  <h1>Administraci&oacute;n</h1>
	  <h1>Zona Salidas</h1>
	  <p>Desde esta p&aacute;gina, puedes editar, crear o borrar las salidas.</p>
	  <h2>Añadir nueva salida</h2>
	  <p>Elige la fecha y haz click en el bot&oacute;n &quot;a&ntilde;adir salida&quot; para crear una nueva salida. A continuaci&oacute;n tendr&aacute;s la opci&oacute;n de editar sus detalles, como el destino y los asistentes.</p>
	  <?php
// Editing tour...
if (isset($_REQUEST['tour_id']))
{
	$con = connect();
	$tours = mysql_query("SELECT tourID, tour_date, routeID, seasonID
		 FROM ". $DB_PREFIX . "_tours
		 WHERE tourID = '" . $_REQUEST['tour_id'] . "' ORDER BY tour_date DESC");
	$tour = mysql_fetch_array($tours);
	echo('<h2>Editando salida del ' . $tour['tour_date'] . '</h2>');
	// Instructions
	echo('<p>Pulsa sobre la fecha para cambiar la fecha de la salida. Elige el destino de la salida. Selecciona o deselecciona las casillas para marcar los asistentes que fueron a esta salida. Finalmente, pulsa "guardar" para confirmar tus modificaciones</p>');
	echo('<p><form method="post" action="'.$RS_URL.'index.php?action=save_tour&amp;tour_id=' . $_REQUEST['tour_id'] . '">');
	echo('<p>ID:<input name="tour_id" type="text" readonly="readonly" value = "' . $tour['tourID'] . ' " /></p>');
	////////////////////////////////////////////////////////////////////////////////////////
	echo('<p>Fecha (AAAA-MM-DD): ');
	echo('<input name="tour_date" value = "' . $tour['tour_date'] . ' ">');
	echo('<input type=button value="Elegir" onClick="displayDatePicker');
	echo("('tour_date')");
	echo(';"></p>');
	////////////////////////////////////////////////////////////////////////////////////////

	/**
		Shows route selection
	*/
	echo('<p>Destino: <select name="route_id">');
	$routes = mysql_query("SELECT name, routeID
				 FROM ". $DB_PREFIX . "_routes ORDER BY name ASC");
	while ($route = mysql_fetch_array($routes))
	{
		echo('<option value = "' . $route['routeID'] . '"' );
		if ($route['routeID'] == $tour['routeID'])
		{
			echo(' selected');
		}
		echo ('>' . $route['name'] . '</option>');
	}
	echo('</select></p>');
	
	/**
		Shows Season selection
	*/
	echo('<p>Temporada: <select name="season_id">');
	$seasons = mysql_query("SELECT description, ID
				 FROM ". $DB_PREFIX . "_seasons ORDER BY ID ASC");
	while ($season = mysql_fetch_array($seasons))
	{
		echo('<option value = "' . $season['ID'] . '"' );
		if ($season['ID'] == $tour['seasonID'])
		{
			echo(' selected');
		}
		echo ('>' . $season['description'] . '</option>');
	}
	echo('</select></p>');
	
	// list users
	echo('<h2>Socios asistentes</h2>');
	$users = mysql_query("SELECT name, userID
						  FROM ". $DB_PREFIX . "_users ORDER BY name ASC");
	while ($user = mysql_fetch_array($users))
	{
		echo('<p><label><input type="checkbox" name="user_id' . $user['userID'] . '" ');
		$tourists = mysql_query("SELECT *
								 FROM ". $DB_PREFIX . "_user_tours
								 WHERE tourID = '" . $tour['tourID'] . "'"
								 . "AND userID = '" . $user['userID'] . "'");
		if (mysql_num_rows($tourists) >= 1)
		{
			echo ('checked');
		}
		echo('/>' . $user['name']);
		echo('</label></p>');
	}
	echo('<input type="submit" value="Guardar" name="save" /></form></p>');
	// Show delete tour link
	echo('<p align="right"><a href="'.$RS_URL.'index.php?action=delete_tour&amp;tour_id=' . $tour['tourID'] . '">Borrar esta salida</a></p>');
	disconnect($con);
}
else
{
	// Editar
	echo('<form method="post" action="'.$RS_URL.'index.php?action=new_tour"><p>Fecha (AAAA-MM-DD): ');
	echo('<input name="new_tour_date">');
	echo('<input type=button value="Elegir" onClick="displayDatePicker');
	echo("('new_tour_date')");
	echo(';"></p><p>');
	echo('<input type="submit" value="Añadir Salida" /></form></p>');
	?>
	  <h2>Editar salida</h2>
	  <p>Elige la salida de la lista que quieras editar o borrar, y pulsa sobre el bot&oacute;n &quot;Editar salida&quot; para confirmar tu selecci&oacute;n.</p>
	  <?php
echo('<p><form method="post" action="'.$RS_URL.'index.php?action=edit_tour"><p>Elegir salida: <select name="tour_id">');
$con = connect();
$tours = mysql_query("SELECT tour_date, tourID, routeID
		 FROM ". $DB_PREFIX . "_tours
		 ORDER BY tour_date DESC");
while ($tour = mysql_fetch_array($tours))
{
$names = mysql_query("SELECT name FROM ". $DB_PREFIX . "_routes WHERE routeID = '" . $tour['routeID'] 
						. "' ORDER BY name ASC");
$name = mysql_fetch_array($names);
echo('<option value = "' . $tour['tourID'] . '">' . $tour['tour_date'] . ' - ' . $name['name'] . '</option>');
}
disconnect($con);
echo('</select></p><input type="submit" value="Editar Salida" /></form></p>');
}
?>