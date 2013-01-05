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
include "connect.php";
include "stats.php";
include "parse_route.php";
global $DB_PREFIX;
?>

	  <h1>Administraci&oacute;n</h1>
	  <h1>Zona Rutas</h1>
	  <p>Desde esta p&aacute;gina, puedes editar, crear o borrar las rutas.</p>
    
<?php
// Route used before. Warning user about it.
function warning($id)
{
	global $DB_PREFIX;
	$res = mysql_query("SELECT tourID FROM ". $DB_PREFIX . "_tours WHERE routeID = '" . $id . "'");	
	if ($res && mysql_num_rows($res))
		echo('<h3>Cuidado! Esta ruta ya se ha hecho en <strong>' . mysql_num_rows($res) . '</strong> salida/s. Modificar o borrar esta ruta afectar&aacute; a las estad&iacute;sticas de los usuarios que hayan hecho la/s salida/s!</h3>');
}
// Editing route...
if (isset($_REQUEST['route_id']))
{
	$con = connect();
	$routes = mysql_query("SELECT name, routeID, description, km
		 FROM ". $DB_PREFIX . "_routes
		 WHERE routeID = '" . $_REQUEST['route_id'] . "'
		 ORDER BY name ASC");
	$route = mysql_fetch_array($routes);
	
	warning($route['routeID']);
	
	
	disconnect($con);
	echo('<h2>Editando ruta ' . $route['name'] . '</h2>');
	echo('<p><form method="post" action="'.$RS_URL.'index.php?action=save_route&amp;route_id=' . $_REQUEST['route_id'] . '">');
	echo('<p>Nombre:<input name="routename" size="50" type="text" value = "' . $route['name'] . ' " /></p>');
	echo('<p>Kilómetros:<input name="km" size="3" type="text" value = "' . $route['km'] . ' " /></p>');
	echo('<p>ID:<input name="route_id" type="text" readonly="readonly" size="3" value = "' . $route['routeID'] . ' " /></p>');
	echo('<p>Descripción:</p><p><textarea name="description" cols="60" rows="10">' . $route['description'] . '</textarea></p>');
	
	echo('<input type="submit" value="Guardar" name="save" /></form></p>');
	
	echo('<h2>Subir track</h2>');
	echo('<form action="'.$RS_URL.'index.php?action=upload_track&amp;route_id=' . $_REQUEST['route_id'] . '" method="post"
		enctype="multipart/form-data">
		<p>
		Track (kml):
		<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Subir track" />
		</p>
		</form>');
	// Show delete route link
	echo('<p align="right"><a href="'.$RS_URL.'index.php?action=delete_route&amp;route_id=' . $route['routeID'] . '">Borrar esta ruta</a></p>');
}
else
{
	// Editar
	echo('<h2>Editar ruta</h2>');
	echo('<p>Elige en la lista el nombre de la ruta que quieres editar y haz click sobre el botón &quot;Editar&quot;.</p>');
	echo('<p><form method="post" action="'.$RS_URL.'index.php?action=edit_route">Elegir ruta <select name="route_id">');
	$con = connect();
	$routes = mysql_query("SELECT name, routeID
				 FROM ". $DB_PREFIX . "_routes ORDER BY name ASC");
	while ($route = mysql_fetch_array($routes))
	{
		echo('<option value = "' . $route['routeID'] . '">' . $route['name'] . '</option>');
	}
	disconnect($con);
	echo('</select> <input type="submit" value="Editar" /></form></p>');
		  
	// New route
	echo('<h2>Añadir nueva ruta</h2>');
	echo('<p>Pulsa sobre el botón &quot;Crear Ruta&quot; para crear la ruta y poder editar sus detalles.</p>');
	echo('<p><form method="post" action="'.$RS_URL.'index.php?action=new_route">');
	echo('<input type="submit" value="Crear Ruta" /></form></p>');
}		
?>