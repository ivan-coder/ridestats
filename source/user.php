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
include "settings.php";

global $RS_URL;

if (isset($_REQUEST['userid']))
{
	$id = ($_REQUEST['userid']);
}
$con = connect();
$users = mysql_query("SELECT name, join_date, comment
											FROM ". $DB_PREFIX . "_users
											WHERE userID = '" . $id . "'");
$user = mysql_fetch_array($users);
$name = explode(" ", $user['name']); $name = $name[0];
$join_date = date("d m Y", strtotime($user['join_date']));

// calculate km's
/*
$result_tours = mysql_query("SELECT tourID
						FROM ". $DB_PREFIX . "_user_tours
						WHERE userID = '" . $id . "'");*/
$result_tours = getTours($id);
$total_km = getKilometers($result_tours);
$total_tours = mysql_num_rows($result_tours);

// Calculate last tours and tour percentage
//$res = mysql_query("SELECT tourID FROM ". $DB_PREFIX . "_tours");
//$tt = mysql_num_rows($res);
$res = getSeasonTours();
$tt = mysql_num_rows($res);
$per = $tt > 0 ? (($total_tours / $tt) * 100) : 0;

$percentage = sprintf("%4.2f", $per);
if ( substr($percentage, strlen($percentage)-3) == ".00" )
	$percentage = substr($percentage, 0, strlen($percentage)-3);

$comment_value = array(25, 50, 75, 99, 100);
$comment_text  = array(
	'Esperamos que en adelante venga más a menudo!',
	'Podría venir más',
	'Es un socio muy asiduo!',
	'Cuenta con él para la próxima salida!',
	'Este hombre se merece un monumento! No se pierde ni una!');

$i = 0; while($per > $comment_value[$i]) $i++;
$comment = $user['name'] . ' ha participado en el <strong>' . $percentage . '%</strong> de las salidas. ' . $comment_text[$i];

$ext_comment = nl2br($user['comment']);

// calculate tours
if (isset($_COOKIE['user']))
{
	 echo('<p></p><p align="right"><a href="'.$RS_URL.'index.php?action=edit_user&amp;user_id=' . $_REQUEST['userid'] . '">Editar este socio</a></p>');
}
?>

<h1>Detalle del socio</h1>
<table width="500" border="0">
	<tr>
		<th scope="col"><?=$user['name'];?></th>
		<th scope="col"><img src="<?=$RS_URL;?>/local_data/usr_img/<?=$id;?>.jpg" 
					alt="no hay imagen!" width="100" height="130" align="right" border="1" /></th>
	</tr>
</table>
<p>Miembro desde el día <strong><?=$join_date;?></strong></p>
<p><strong><?=$total_km;?></strong> Km en <strong><?=$total_tours;?></strong> salidas.</p>
<p><?=$comment;?></p>
<h2>Más sobre <?=$name;?></h2>
<p><?=$ext_comment;?></p>
<h2>Últimas salidas de <?=$name;?>:</h2>
<?php
	drawTableLastTours($id);
	disconnect($con);
?>
