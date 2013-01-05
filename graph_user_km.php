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
// Own code starts here

include ('source/connect.php');
include ('source/stats.php');

global $DB_PREFIX;

$con = connect();

mysql_query("DELETE FROM " . $DB_PREFIX . "_temp_users");

$res = mysql_query("	SELECT userID, name
						FROM ".$DB_PREFIX."_users");
while ($row= mysql_fetch_array($res))
{
	$name = str_replace("á", "a", $row['name']);
	$name = str_replace("é", "e", $name);
	$name = str_replace("í", "i", $name);
	$name = str_replace("ó", "o", $name);
	$name = str_replace("ú", "u", $name);
	$name = str_replace("à", "a", $name);
	$users[] = $name;
	/*$res2 = mysql_query("SELECT tourID FROM ".$DB_PREFIX."_user_tours WHERE userID = '" . $row['userID'] . "'");*/
	$res2 = getTours($row['userID']);
	$km[] = getKilometers($res2);
	$id[] = $row['userID'];
}

for ($n = 0; $n < sizeof($users); $n++)
{
	mysql_query("INSERT INTO ". $DB_PREFIX ."_temp_users (userID, name, km) 
				 VALUES ('" . $id[$n] . "', '" . $users[$n] . "', '" . $km[$n] . "')");
}
unset($users);
unset($km);
unset($links);

$res = mysql_query("SELECT userID, name, km FROM ". $DB_PREFIX ."_temp_users ORDER BY km DESC");

while ($row = mysql_fetch_array($res))
{
	if ($row['km'] > 0)
	{
		$users[] = $row['name'];
		$km[] = sprintf("%d", $row['km']);
		$links[] = ""; //@todo: "index.php?action=user&amp;userid=" . $row['userID'];
	}
}

include_once( 'ofc-library/open-flash-chart.php' );
$g = new graph();
$g->bg_colour = '#FFFFFF';
//
// PIE chart, 60% alpha
//
$g->pie(60,'#111111','{font-size: 9px; color: #000000;');
//
// pass in two arrays, one of data, the other data labels
//
$g->pie_values( $km, $users, $links );
//
// Colours for each slice, in this case some of the colours
// will be re-used (3 colurs for 5 slices means the last two
// slices will have colours colour[0] and colour[1]):
//
$g->pie_slice_colours( array('#033387','#00306f') );

//$g->set_tool_tip( '#val#%' );

//$g->title( 'Kms totales', '{font-size:18px; color: #000000}' );
echo $g->render();
?>