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
  <h2>Salida actualizada</h2>
  <?php
  if (isset($_REQUEST['tour_id']) && $_REQUEST['tour_id'] > 0)
  {
    $con = connect();
    //mysql_query("LOCK TABLES ". $DB_PREFIX . "_tours WRITE");
    //mysql_query("LOCK TABLES ". $DB_PREFIX . "_user_tours WRITE");
    // save date & routeID
    mysql_query("	UPDATE ". $DB_PREFIX . "_tours
                SET tour_date = '" . $_REQUEST['tour_date'] . "'
                ,routeID = '" . $_REQUEST['route_id'] . "'
				,seasonID = '" . $_REQUEST['season_id'] . "'
                WHERE tourID = '" . $_REQUEST['tour_id'] . "'");
    // Reselects users. First deletes all users in tour to avoid duplications 
    // and the reselects they only once.
    mysql_query("DELETE FROM ". $DB_PREFIX . "_user_tours WHERE tourID = '" . $_REQUEST['tour_id'] . "'");
    $users = mysql_query("SELECT userID FROM ". $DB_PREFIX . "_users");
    while($user = mysql_fetch_array($users))
    {
      $str = sprintf("user_id%d", $user['userID']);
      if (isset($_REQUEST[$str]))
      {					
        mysql_query("INSERT INTO ". $DB_PREFIX . "_user_tours (userID, tourID)" .
        "VALUES ('" . $user['userID'] . "', '" . $_REQUEST['tour_id'] . "')");
      }
    }
    //mysql_query("UNLOCK TABLES");
    disconnect($con);
    echo('<p>La información ha sido guardada.</p>');
  }			
?>      
  <p>Qu&eacute; deseas hacer a continuaci&oacute;n?</p>
  <?php
  echo('<p><a href="'.$RS_URL.'index.php?action=edit_tour&amp;tour_id=' . $_REQUEST['tour_id'] . '">Editar otra vez la salida</a></p>');
?>
  <p><a href="'.$RS_URL.'index.php?action=edit_tour">Editar otra salida</a></p>
  <p>Usa el men&uacute; de navegaci&oacute;n a la izquierda para ir a otro sitio.</p>
 