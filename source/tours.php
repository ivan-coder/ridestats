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

// Select default order method
$order = "tour_date";
if (isset($_REQUEST["order"]))
{
	$order = $_REQUEST["order"];
}
$dir = desc;
if (isset($_REQUEST["dir"]))
{
	$dir = $_REQUEST["dir"];
}

$con = connect();			
/*
$tour = mysql_query("SELECT tourID, tour_date, routeID
					 					 FROM ". $DB_PREFIX . "_tours");
	*/
$tour = getSeasonTours();
// Copy to a temp table
mysql_query("DELETE FROM ". $DB_PREFIX . "_temp_tours");
if ($tour)
while ($row = mysql_fetch_array($tour))
{			
	$route_res = mysql_query(	"SELECT routeID, name
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
					 FROM ". $DB_PREFIX . "_temp_tours
					 ORDER BY " . $order . " " . $dir);

$total_tours = ($tour) ? mysql_num_rows($tour) : 0;
$total_km = ($tour) ? getKilometers($tour) : 0;
disconnect($con);?>
    
<h1>Últimas Salidas</h1>
<p><?=$total_tours?> salidas realizadas, que suman un total de <?=$total_km?>Km.</p>
<table width="500" border="0">
  <tr>
    <th scope="col"><div align="center"><a href="<?php echo $RS_URL; ?>/index.php?action=tours&amp;order=tour_date&amp;dir=desc">Fecha</a></div></th>
		  <th scope="col"><div align="center"><a href="<?php echo $RS_URL; ?>/index.php?action=tours&amp;order=name&amp;dir=asc">Ruta</a></div></th>
		  <th scope="col"><div align="center"><a href="<?php echo $RS_URL; ?>/index.php?action=tours&amp;order=users&amp;dir=desc">Asistentes</a></div></th>
    </tr>
  <?php if ($tour) while ($row = mysql_fetch_array($tour)){?>
  <tr>
    <td><div align="left"><a href="<?php echo $RS_URL; ?>/index.php?action=tour&amp;tourid=<?=$row['tourID']?>"><?=$row['tour_date']?></a></div></td>
		  <td><div align="left"><a href="<?php echo $RS_URL; ?>/index.php?action=route&amp;routeid=<?=$row['routeID']?>"><?=$row['name']?></a></div></td>
		  <td><div align="center"><?=$row['users']?></div></td>
	  </tr>
  <?php } ?>
</table>