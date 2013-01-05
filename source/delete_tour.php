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
<h2>Borrar salida</h2>
<p>La salida ha sido borrada.</p>
<?php
  echo '<p><a href="'.$RS_URL.'index.php?action=edit_tour">Volver</a></p>';
  $con = connect();
  //mysql_query("LOCK TABLES ". $DB_PREFIX . "_routes WRITE");
  mysql_query("DELETE FROM ". $DB_PREFIX . "_user_tours WHERE tourID = '" . $_REQUEST['tour_id'] . "'");
  mysql_query("DELETE FROM ". $DB_PREFIX . "_tours WHERE tourID = ' " . $_REQUEST['tour_id'] . " '");
  //mysql_query("UNLOCK TABLES");
  disconnect($con);
?>