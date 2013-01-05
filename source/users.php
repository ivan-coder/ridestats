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
?>

<h1>Socios</h1>
<?php
// * Save data in a temp table
// *********************************************************

$con = connect();
$users = mysql_query("SELECT userID, name, join_date
            FROM ". $DB_PREFIX . "_users");

mysql_query("DELETE FROM ". $DB_PREFIX . "_temp_users");
if ($users)
while($row = mysql_fetch_array($users))
{
	$tours_res = getTours($row['userID']);			  	
	$res = mysql_query("INSERT INTO ". $DB_PREFIX . "_temp_users (userID, name, tours, km, join_date)
				VALUES ('". $row['userID'] ."', '". 
							 $row['name'] ."', '". 
							 mysql_num_rows($tours_res) ."', '". 
							 getKilometers($tours_res) ."', '". 
							 $row['join_date'] ."')");
}

// * Formulate query to obtain table data in order
// *********************************************************
$query = "SELECT userID, name, tours, km, join_date
      FROM ". $DB_PREFIX . "_temp_users";

if (isset($_REQUEST["name"]))
{
  $query = sprintf("%s %s", $query, "ORDER BY name ASC");
}
else if (isset($_REQUEST["km"]))
{
  $query = sprintf("%s %s", $query, "ORDER BY km DESC, name ASC");
}
else if (isset($_REQUEST["tours"]))
{
  $query = sprintf("%s %s", $query, "ORDER BY tours DESC, name ASC");
}
else if (isset($_REQUEST["join_date"]))
{
  $query = sprintf("%s %s", $query, "ORDER BY join_date ASC, name ASC");
}
else
{
  // default
  $query = sprintf("%s %s", $query, "ORDER BY km DESC, name ASC");
}

// * Fecth and display data
// *********************************************************
$users_data = mysql_query($query);

if ($users_data)
	echo('<p>' . mysql_num_rows($users_data) . ' usuarios registrados.</p>');
else
	echo('<p>Aún no hay usuarios registrados!</p>');
?>
<?php echo'
<table width="500" border="0">
  <tr>
    <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=users&name">Socio</a></div></th>
  <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=users&tours">Salidas</a></div></th>
      <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=users&km">KM´s</a></div></th>
      <th scope="col"><div align="center"><a href="'.$RS_URL.'/index.php?action=users&join_date">Miembro desde</a></div></th>
  </tr>';
?>
	
	
  
<?php
if ($users_data)
while ($row = mysql_fetch_array($users_data))
{
  echo('<tr>');
  echo('<td><div align="center"><a href="'.$RS_URL.'/index.php?action=user&amp;userid=' . $row['userID'] . '">' . $row['name'] . '</a></div></td>');
  echo('<td><div align="center">' . $row['tours'] . '</div></td>');
  echo('<td><div align="center">' . $row['km'] . '</div></td>');
  echo('<td><div align="center">' . date("d m Y", strtotime($row['join_date'])) . '</div></td>');
  echo('</tr>');
}
disconnect($con);
echo('</table>');
echo('<h2>Gráfica socios-kilómetros</h2>');
include_once 'ofc-library/open_flash_chart_object.php';
open_flash_chart_object( 540, 540, 'graph_user_km.php', false);
?>