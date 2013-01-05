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
	include 'parse_route.php';
	
	global $min_alt, $max_alt, $RS_URL;
	$max_alt = 0;
	$min_alt = 1000000;
// * Init track values if exists
// ***************************************************************************
$track = 'local_data/tracks/' . $_REQUEST['routeid'] . '.kml';
if (file_exists($track))
	{
		$file_exists = true;
		$coor = getCoorArray($track);
		$first = $coor[0];
		$first = explode(",", $first);		
		$acc = 0.0;
		$last = $min_alt;
		$cn = 0;
		$lon_ctr = 0;
		$lat_ctr = 0;
		$alt_array = array('"ALTITUDE"');
		$head_array = array("");
		foreach ($coor as $c)
		{
			//min and max
			$temp = explode(",", $c);
			$tmp = round($temp[2]);
			if ($tmp > $max_alt)
			{
				$max_alt = $tmp;
			}
			if ( ($tmp < $min_alt) && ($tmp > 0) ) // avoid incomplete data registers
			{
				$min_alt = $tmp;
			}
			//accumulated height
			if (($cn++ % 10) == 0)
			{			
				$d = ($temp[2] - $last);
				if ( ($d > -15) && ($d < 15) )
				{
					if ($d >= 0)
						$acc += $d;
					else
						$acc -= $d;
				}
				//echo ($d . '<br />');
				$last = $temp[2];
			}
			// Calculate center of the route
			$lon_ctr += $temp[1];
			$lat_ctr += $temp[0];
			// char data
			$alt_array[$cn]=round($temp[2]);
		}
		// Obtain map url
		$lon_ctr /= $cn;
		$lat_ctr /= $cn;
		$mapurl = '<p><iframe'
		. ' src="http://www.takitwithme.com/iframe.html?&amp;url='. $RS_URL .'/'
		. $track . '&amp;z=11&amp;ll=' . $lon_ctr . ',' . $lat_ctr 
		. '&amp;nolargebutton=1&amp;nogebutton=1&amp;nogpsbutton=1"'
		. ' name="takit-embed" frameborder="0" height="415" scrolling="auto" width="465"></iframe></p>';
	}
	else
	{
		$file_exists = false;
	}
?>