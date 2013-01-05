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

// * Init track values if exists
// ***************************************************************************
include('parse_kml.php');

global $DB_PREFIX, $RS_URL;
// * Show last tours function
// ***************************************************************************
function showLastTours()
{
	global $DB_PREFIX;
	$id = ($_REQUEST['routeid']);
	echo('<h2>Últimas salidas:</h2>');
	/*
	$tours = mysql_query("SELECT tourID, tour_date
						  FROM ". $DB_PREFIX . "_tours
						  WHERE routeID = '" . $id .
						  "' ORDER BY tour_date DESC");*/
	$tours = getSeasonTours("AND routeID = '".$id."' ORDER BY tour_date DESC");
							
	if ($tours && mysql_num_rows($tours) > 0)
	{
		echo('<table align="center" width="500" border="0">
			  <tr>
				  <th  scope="col"><div align="center">Fecha</div></th>
				  <th scope="col"><div align="center">Número Asistentes</div></th>
			  </tr>');
		while ($row = mysql_fetch_array($tours))
		{
			$member_number = mysql_num_rows(
							 mysql_query("SELECT userID
										  FROM ". $DB_PREFIX . "_user_tours
										  WHERE tourID = '" . $row['tourID'] . "'"));
			echo('<tr>');
			echo('<td><div align="center"><a href="'.$RS_URL.'index.php?action=tour&amp;tourid=' . $row['tourID'] .'">' . 
					date("d m Y", strtotime($row['tour_date'])) .'</a></div></td>');
			echo('<td><div align="center">' . $member_number .'</div></td>');
			echo('</tr>');
		}
		echo('</table>');
	}
	else
	{
		echo('<p>Aún no se han hecho salidas por esta ruta!</p>');
	}
}

// * Display General route details
// ***************************************************************************
	$id = ($_REQUEST['routeid']);
	$con = connect();
	$route = mysql_query("SELECT name, description, km 
						  FROM ". $DB_PREFIX . "_routes
						  WHERE routeID = '" . $id . "'");
	$row = mysql_fetch_array($route);
		
	echo('<h1>Ver detalle de la ruta</h1>');
	echo('<h2>General</h2>');
	echo('<table align="left" width="350" >
          <tbody>
            <tr>
              <th colspan="2" scope="col"><div align="center"><strong>' . $row['name'] . '</strong></div></th>
            </tr>
            <tr>
              <td><div align="center">Kilómetros</div></td>
              <td><div align="center">' . $row['km'] . '</div></td>
            </tr>
          ');
	$tours = mysql_query("SELECT tourID, tour_date
						  FROM ". $DB_PREFIX . "_tours
						  WHERE routeID = '" . $id .
						  "' ORDER BY tour_date DESC");
	echo('<tr>
		  <td><div align="center">Salidas realizadas</div></td>
		  <td><div align="center">' . mysql_num_rows($tours) . '</div></td>
		</tr>');
	if ($file_exists)
	{
		echo('<tr>
              <td><div align="center">Altitud Mínima</div></td>
              <td><div align="center">' . $min_alt . '</div></td>
            </tr>');
		echo('<tr>
              <td><div align="center">Altitud Máxima</div></td>
              <td><div align="center">' . $max_alt . '</div></td>
            </tr>');
		echo('<tr>
              <td valign="middle" colspan="2" scope="col"><div align="left"><a href="local_data/tracks/' . $id . '.kml"><img src="themes/common/floppy.png" alt="descargar" border="0" /> Descargar track</a></div></td>
            </tr>');
		//printf("<p>Desnivel Acumulado: <strong>%dm.</strong></p>", $acc);
	}
	echo('</tbody>
      </table>');
	
	// * Display track
	// ***************************************************************************
	echo (' <h2>&nbsp;</h2>
			<h2>&nbsp;</h2>
			<h2>&nbsp;</h2>
			<h2>&nbsp;</h2>'); // IE bugfix
	echo ('<h2>Mapa de la ruta</h2>');
	
	//print_r($s_alt_array);
	//print_r($s_head_array);
	disconnect($con);
	if ($file_exists)
	{
		// This line displays TakeItWithMe track
		echo($mapurl);
		
		echo ('<h2>Perfil de la ruta</h2>');
		echo('<p>');
		// Update table in use before call InsertChart !!!
		$track = "". $DB_PREFIX . "_kml_" . $id;
		$con = connect();
		
		
		$res = mysql_query("	SELECT value
						FROM ". $DB_PREFIX . "_settings
						WHERE name = 'current_track'
						");	
		
		mysql_query("UPDATE ". $DB_PREFIX . "_settings
					 SET value = '" . $track . "' "
					 . "WHERE name = 'current_track'");
		disconnect($con);
		include_once 'ofc-library/open_flash_chart_object.php';
		open_flash_chart_object( 465, 350, $RS_URL .'/graph_route.php', false );
		echo('</p>');
	}
	else
	{
		echo('<p>Aún no hay track para esta ruta. Los socios pueden subir tracks en administración, zona rutas.</p>');
	}
	$con = connect();
	
	// * Display last tours
	// ***************************************************************************
	showLastTours();
	disconnect($con);
	
	// * Display user comments
	// ***************************************************************************
	echo('<h2>Así ven los socios esta ruta:</h2>');
	if ($row['description'] != "")
	{
		echo('<p>' . nl2br($row['description']) . '</p>');
	}
	else
	{
		echo('<p>Aún no hay descripción para esta ruta! Si eres socio puedes añadirla desde el menú administrar!</p>');
	}
	if (isset($_COOKIE['user']))
	{
		 echo('<p align="right"><a href="'.$RS_URL.'/index.php?action=edit_route&amp;route_id=' . $id . '">Editar esta ruta</a></p>');
	}
?>