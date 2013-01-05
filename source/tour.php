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
include "connect.php";
include "source/stats.php";

global $DB_PREFIX, $RS_URL;

if (isset($_REQUEST['tourid']))
{
	$id = ($_REQUEST['tourid']);
}
$con = connect();

$tour = mysql_query("SELECT tour_date, routeID
						FROM ". $DB_PREFIX . "_tours
						WHERE tourID = '" . $id . "'");
$tour_record = mysql_fetch_array($tour);
$date = date("d m Y", strtotime( $tour_record['tour_date'] ));

// gets route name and distance
$route = getRouteName($tour_record['routeID']);
$distance = getRouteDistance($tour_record['routeID']);

// get users names in this tour
$users = getUsersNames($id);

// Get number of users in a tour
$usersNumber = getUsersNumber($id);

disconnect($con);
?>

<h1>Detalle de la salida</h1>
<h2>General</h2>
<p>Fecha: <strong><?=$date?></strong></p>
<p>Ruta: <a href="<?php echo $RS_URL; ?>/index.php?action=route&amp;routeid=<?=$tour_record['routeID']?>"><?=$route?>, <?=$distance?>Km</a></p>
<h2>¿Quien asistió?</h2>
<p>Los <strong><?=$usersNumber?></strong> socios que asistieron a la salida fueron: <?=$users?>
</p>

<?php
if (isset($_COOKIE['user']))
{
	echo('<p></p><p align="right"><a href="'.$RS_URL.'/index.php?action=edit_tour&amp;tour_id=' . $id . '">Editar esta salida</a></p>');
}
?>