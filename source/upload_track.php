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
include "source/parse_route.php";

global $DB_PREFIX;
?>
<h1>Administraci&oacute;n</h1>
<?php
$len = strlen($_FILES["file"]["name"]);
$ext = substr($_FILES["file"]["name"], $len-3, $len);
$ext = strtolower($ext);
if ( ($ext == "kml") && ($_FILES["file"]["size"] < (512*1024)) )
{
if ($_FILES["file"]["error"] > 0)
{
echo "<p>Error: " . $_FILES["file"]["error"] . "</p>";
}
else
{
move_uploaded_file($_FILES["file"]["tmp_name"],
  "local_data/tracks/" . $_REQUEST['route_id'] . ".kml");

// Extract altitude data to database for graph generation
$coor = getCoorArray("local_data/tracks/" . $_REQUEST['route_id'] . ".kml");
$con = connect();
mysql_query("DELETE FROM ". $DB_PREFIX . "_kml_" . $_REQUEST['route_id']);
$cn=0;
foreach ($coor as $c)
{
  //min and max
  $temp = explode(",", $c);
  $table = "". $DB_PREFIX . "_kml_" . $_REQUEST['route_id'];
  mysql_query("CREATE TABLE " . $table  . "
         (
         pointID int(16),
         alt int(16)
         )");
  mysql_query('INSERT INTO ' . $table . ' (pointID, alt)
          VALUES (' . $cn . ', ' . $temp[2] . ')');
          $cn++;
}
disconnect($con);
/*
echo "Upload: " . $_FILES["file"]["name"] . "<br />";
echo "Type: " . $_FILES["file"]["type"] . "<br />";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
echo "Stored in: " . "tracks/" . $_REQUEST['user_id'] . ".jpg";
*/		    
  echo('
<h2>Track actualizado</h2>
  <p>El track ha sido actualizado. &iquest;Qu&eacute; deseas hacer ahora?</p>
  <p><a href="'.$RS_URL.'index.php?action=edit_route&amp;route_id=' . $_REQUEST['route_id'] . '">Volver a la ficha de la ruta</a></p>
<p><a href="'.$RS_URL.'index.php?action=edit_route">Editar otra ruta</a></p>');
}
}
else
{
echo ('<p>Tipo de archivo: ' . $_FILES["file"]["type"] . ' </p><p>Archivo no valido. Comprueba que subes un track con la extensión .kml y que el archivo no excede los 512KB</p>
    <p><a href="'.$RS_URL.'index.php?action=edit_route&amp;route_id=' . $_REQUEST['route_id'] . '">Volver a la ficha de la ruta</a></p>');
}
?>	  