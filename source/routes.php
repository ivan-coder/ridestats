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
include "stats.php";
include "parse_route.php";

global $DB_PREFIX, $RS_URL;

$con = connect();
$order = "name";
$dir = "ASC";
	
if (isset($_REQUEST["order"])) $order = $_REQUEST["order"];
if (isset($_REQUEST["dir"])) $dir = $_REQUEST["dir"];

// select all routes
$names = mysql_query("SELECT routeID, name, description, km FROM ". $DB_PREFIX . "_routes");

// insert routes in a temp table
mysql_query("DELETE FROM ". $DB_PREFIX . "_temp_routes");
$km = 0;
$r_num = 0;
if ($names)
while($row = mysql_fetch_array($names))
{
$tt = mysql_query("SELECT tour_date FROM ". $DB_PREFIX . "_tours
					 WHERE routeID = " . $row['routeID'] . " ORDER BY tour_date DESC");
$tours = mysql_num_rows($tt);
$ll = mysql_fetch_array($tt);
$last = $ll['tour_date'];
									
mysql_query("INSERT INTO ". $DB_PREFIX . "_temp_routes (routeID, name, km, tours, last_tour)
				VALUES ('". $row['routeID'] ."', '". 
										$row['name'] ."', '". 
										$row['km'] ."', '". 
										$tours ."', '". 
										$last ."')");
$r_num++;
$km += $row['km'];
}
// Select from temp table in correct order
 $que = mysql_query(" SELECT routeID, name, km, tours, last_tour
											FROM ". $DB_PREFIX . "_temp_routes
											ORDER BY " . $order . " " . $dir);
disconnect($con);

//save results
$nm = 0;
if ($que)
while ($row = mysql_fetch_array($que)) {
	if ($row['last_tour'] != "0000-00-00") $lt = date("d/m/Y", strtotime( $row['last_tour'] ));
	$res['id'][$nm] = 		$row['routeID'];
	$res['km'][$nm]	=			$row['km'];
	$res['name'][$nm]	=		$row['name'];
	$res['tours'][$nm] =	$row['tours'];
	$res['last'][$nm]	=		$lt;
	$res['track'][$nm] =	file_exists("local_data/tracks/" . $row['routeID'] . ".kml");
	$nm++;
}
?>

<h1>Rutas</h1>
<p>Tenemos <?=$r_num;?> rutas registradas, que suman un total de <?=$km;?> kilometros.</p>

<?php echo'
<table width="500" border="0">
  <tr>
    <th  scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=routes&amp;order=name&amp;dir=asc">Ruta</a></div></th>
    <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=routes&amp;order=km&amp;dir=desc">Km</a></div></th>
    <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=routes&amp;order=tours&amp;dir=desc">Salidas</a></div></th>
    <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=routes&amp;order=last_tour&amp;dir=desc">&Uacute;ltima</a></div></th>
    <th scope="col"><div align="center">Track</div></th>
  </tr>';
?>
  
<?php for($i = 0; $i < $nm; $i++) {?>
	<tr>
		<td>
    	<div align="left">
      	<a href="index.php?action=route&amp;routeid=<?=$res['id'][$i];?>"> <?=$res['name'][$i];?></a>
      </div>
    </td>
		<td><div align="right"><?=$res['km'][$i];?></div></td>
		<td><div align="center"><?=$res['tours'][$i];?></div></td>
		<td><div align="center"><?=$res['last'][$i];?></div></td>
<?php 
	if ($res['track'][$i])
		echo'<td><div align="center"><a href="'.$RS_URL.'/index.php?action=route&amp;routeid=' . $res['id'][$i] . '"><img src="'.$RS_URL.'/themes/common/tick.png" alt="ok"  border="1px solid" /></a></div></td>';
	else
		echo '<td><div align="center">-</div></td>';
	echo '</tr>';
}?>
</table>