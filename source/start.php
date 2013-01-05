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
include "source/connect.php";
include "source/stats.php";
include "source/parse_route.php";

global $DB_PREFIX, $GROUP_NAME;

$con = connect();
$res = mysql_query("SELECT value FROM ". $DB_PREFIX . "_settings WHERE name = 'maintainment_mode'");
$row = mysql_fetch_array($res);
if ($row['value'])
	header("Location: maintainment.php");
disconnect($con);
?>

	<h2>Estad&iacute;sticas <?=$GROUP_NAME?></h2>

<?php

/////////////////////////////////////////////////////////////////////////////////
// Display last tours info
// Copy to a temp table
function toursBrief()
{
	global $DB_PREFIX;
		
	/*$tour = mysql_query("SELECT tourID, tour_date, routeID
						 FROM ". $DB_PREFIX . "_tours");*/
	$tour = getSeasonTours();
	mysql_query("DELETE FROM ". $DB_PREFIX . "_temp_tours");
		
	// If there's no tours, go back
	if (!$tour) return;
	
	// Fetch tours
	while ($row = mysql_fetch_array($tour))
	{			
		$route_res = mysql_query("SELECT routeID, name
						 FROM ". $DB_PREFIX . "_routes
						 WHERE routeID = '" . $row['routeID'] . "'");
		$route = mysql_fetch_array($route_res);
		
		$member_res = mysql_query("SELECT userID
						 FROM ". $DB_PREFIX . "_user_tours
						 WHERE tourID = '" . $row['tourID'] . "'");
		$member_num = mysql_num_rows($member_res);
		
		mysql_query("INSERT INTO ". $DB_PREFIX . "_temp_tours (tourID, routeID, tour_date, name, users)
							VALUES ('". $row['tourID'] ."', '". 
									   $route['routeID'] ."', '". 
									   $row['tour_date'] ."', '". 
									   $route['name'] ."', '". 
									   $member_num."')");
	}
	$tour = mysql_query("SELECT tourID, routeID, tour_date, name, users
							 FROM ". $DB_PREFIX . "_temp_tours");
	// draw general stats		
	 echo ('<p>Llevamos hechas <strong>' . mysql_num_rows($tour) . '</strong> salidas que suman <strong>' . getKilometers($tour) . '</strong> kilómetros.</p>');
 
	// draw general kilometers
	$res = getSeasonUserTours();
	$km = getKilometers($res);
	echo('<p>Si sumamos la distancia que lleva recorrida cada socio, entre todos hemos hecho ya <strong>' . $km . '</strong> kilómetros!</p>');
}

$con = connect();
toursBrief();

// get last tour date
echo('<h2>&Uacute;ltimas salidas</h2>');

/////////////////////////////////////////////////////////////////////////////////
// Show last tours data
$tours = mysql_query("SELECT tour_date, routeID, tourID FROM ". $DB_PREFIX . "_tours WHERE " . filterSeasonQuery() . " ORDER BY tour_date DESC, tourID DESC");
if ($tours)
{
	for ($n = 0; $n < 3; $n++)
	{
		$last_tour = mysql_fetch_array($tours);
		if (!$last_tour) break;
		$mysqldate = $last_tour['tour_date'];
		$phpdate = strtotime( $mysqldate );
		$str_last_date = dateWhenWasString($phpdate);
		$routes = mysql_query("SELECT name from ". $DB_PREFIX . "_routes WHERE routeID = '". $last_tour['routeID'] ."'");
		$route = mysql_fetch_array($routes);
		$date = date("d/m/Y", $phpdate);
					
		// echoes route brief
		echo('<p><strong>' . $date . ' ' . $route['name'] . '</strong></p>');
		echo('<p>La <a href="'.$RS_URL.'index.php?action=tour&amp;tourid=' . $last_tour['tourID'] 
				. '">salida de ' . $str_last_date . '</a>, fue por la ruta de ');
		echo_route($last_tour['routeID']);
		echo('. ');
		echo_users($last_tour['tourID']);
		echo('</p>');
	}
}
else
{
	echo '<p>No hay salidas, por ahora!</p>';
}

/////////////////////////////////////////////////////////////////////////////////
// Show last update
$lu = mysql_query("SELECT value FROM ". $DB_PREFIX . "_dates WHERE name = 'last_update'");
$update = mysql_fetch_array($lu);
$date = date("d/m/Y H:i:s", strtotime($update['value']));
echo('<p align="right">&Uacute;ltima actualizaci&oacute;n: <strong>' . $date . '</strong></p>'); 
 
disconnect($con);
 ?> 