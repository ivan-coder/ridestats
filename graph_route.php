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

global $DB_PREFIX;

$con = connect();

$res = mysql_query("	SELECT value
						FROM " . $DB_PREFIX . "_settings
						WHERE name = 'current_track'
						");
			
$row= mysql_fetch_array($res);
$track_table = $row['value'];

$res = mysql_query("	SELECT pointID, alt
						FROM " . $track_table);
mysql_close($con);

$cn2 = 0;
$points = 50; //points in graph

$max = 0;
$p = mysql_num_rows($res)/$points;
while ($row = mysql_fetch_array($res))
{
	if (($cn2 % $p) == 0)
	{
		$data[] = $row['alt'];
	}
	if ($max < $row['alt'])
		$max = $row['alt'];
	$cn2++;
}
$data[] = 'null';
for ($n=0; $n < $max; $n+=200);
$max = $n;
// use the chart class to build the chart:
include_once( 'ofc-library/open-flash-chart.php' );
$g = new graph();
$g->line( 2, '#2222BB');

// Title
$g->title( 'Perfil Ruta ', '{font-size: 18px; color: #FFFFFF}' );

//Set style
$g->bg_colour = '#44607e';
//$g->set_x_label_style( 14, '#FFFFFF', 2 );
$g->set_y_label_style( 14, '#FFFFFF');
$g->set_y_legend( 'Metros', 12, '#FFFFFF' );
$g->x_axis_colour( '#D0D0D0', '#808080' );
$g->y_axis_colour( '#D0D0D0', '#808080' );
$g->set_inner_background( '#999999', '#888888', 90 );

$g->set_data( $data );

$g->set_x_axis_steps( 10 );
// label each point with its value
//$g->set_x_labels( array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ) );

// set the Y max
$g->set_y_max( $max );
// label every 20 (0,20,40,60)
$g->y_label_steps( 5 );

// display the data
echo $g->render();
?>