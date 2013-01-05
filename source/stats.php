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
global $DB_PREFIX;
// * echoes list of users separated by commas in a tour.
// ****************************************************************************
if (!function_exists(echo_users))
{	
	function echo_users($tourID)
	{
		global $DB_PREFIX;
		$users = mysql_query("SELECT userID
							  FROM ". $DB_PREFIX . "_user_tours
							  WHERE tourID = '" . $tourID. "'");
		$users_number = mysql_num_rows($users);
		echo('Los ' . $users_number . ' socios que asistieron a la salida fueron: ');
		$cnt = $users_number;
		while ($row = mysql_fetch_array($users))
		{
			$user = mysql_query("SELECT name
							  FROM ". $DB_PREFIX . "_users
							  WHERE userID = '" . $row['userID'] . "'");
			$name = mysql_fetch_array($user);
			if ($cnt-- == $users_number)
			{
				echo('<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>');
			}
			else if ($cnt > 0)
			{
				echo(', ' . '<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>' );
			}
			else
			{
				echo(' y ' . '<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>' . '.');
			}
		}
	}
}

if (!function_exists(getUsersNames))
{	
	function getUsersNames($tourID)
	{
		global $DB_PREFIX;
		$users = mysql_query("SELECT userID
							  FROM ". $DB_PREFIX . "_user_tours
							  WHERE tourID = '" . $tourID. "'");
		$users_number = mysql_num_rows($users);
		$cnt = $users_number;
		while ($row = mysql_fetch_array($users))
		{
			$user = mysql_query("SELECT name
							  FROM ". $DB_PREFIX . "_users
							  WHERE userID = '" . $row['userID'] . "'");
			$name = mysql_fetch_array($user);
			if ($cnt-- == $users_number)
			{
				$answer .= '<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>';
			}
			else if ($cnt > 0)
			{
				$answer .= ', ' . '<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>';
			}
			else
			{
				$answer .= ' y ' . '<a href="'.$RS_URL.'index.php?action=user&amp;userid=' . $row['userID'] . '">' . $name['name'] . '</a>.';
			}
		}
		return $answer;
	}
}

/**
	@return true if a tour exists in one of the selected seasons
	not tested yet
*/
/*
if (!function_exists(isTourInSeason))
{	
	function isTourInSeason($tourID)
	{
		global $DB_PREFIX;
		$sql = mysql_query("  SELECT seasonID
							  FROM ". $DB_PREFIX . "_tours
							  WHERE tourID = '" . $tourID. "'");
		$row = mysql_fetch_array($sql);
		foreach ($_SESSION['seasons'] as $value)
		{
			if ($value == $row['seasonID'])
				return true;
		}
		return false;
	}
}*/

/**
	returns a string for a sql query containing filter for selected seasons
	example (quotes for clarity):
	"0 OR seasonID = 1 OR seasonID = 4"
*/
if (!function_exists(filterSeasonQuery))
{	
	function filterSeasonQuery($field = null)
	{
		if (!$field) $field = "seasonID";
		session_start();
		global $DB_PREFIX;
		if (!$_SESSION['seasons']) 
		{
			echo 'no sessions!';
			return null;
		}
		$query = " 0";
		$season = 0;
		foreach ($_SESSION['seasons'] as $value)
		{
			$season++;
			if ($value)
			{
				// Adds every valid value in query
				$query .= " OR ".$field." = " . $season;
			}
		}
		return $query;
	}
}

/**
	@return an array with tours of the selected(s) season(s).
*/
if (!function_exists(getSeasonTours))
{	
	function getSeasonTours($additionalParams = "")
	{
		session_start();
		global $DB_PREFIX;	
		
		$query = filterSeasonQuery();
		if (!$query) return null;
		
		$tour = mysql_query(	"SELECT tourID, tour_date, routeID, seasonID
								FROM ". $DB_PREFIX . "_tours WHERE (" . $query . ") " . $additionalParams);

		return $tour;
	}
}

if (!function_exists(getSeasonUserTours))
{	
	function getSeasonUserTours()
	{
		session_start();
		global $DB_PREFIX;	
		
		if (!$_SESSION['seasons']) 
		{
			echo 'no sessions!';
			return null;
		}
		$query = " 0";
		$season = 0;
		foreach ($_SESSION['seasons'] as $value)
		{
			$season++;
			if ($value)
			{
				// Adds every valid value in query
				$query .= " OR ". $DB_PREFIX . "_tours.seasonID = " . $season;
			}
		}
		$res = mysql_query ("SELECT ". $DB_PREFIX . "_tours.tourID FROM ". $DB_PREFIX . "_user_tours, ". $DB_PREFIX . "_tours WHERE (" . $query . ") AND " . $DB_PREFIX . "_tours.tourID = " . $DB_PREFIX . "_user_tours.tourID");
		return $res;
	}
}

if (!function_exists(getUsersNumber))
{	
	function getUsersNumber($tourID)
	{
		global $DB_PREFIX;
		$users = mysql_query("SELECT userID
							  FROM ". $DB_PREFIX . "_user_tours
							  WHERE tourID = '" . $tourID. "'");
		return mysql_num_rows($users);
	}
}

if (!function_exists(getCurrentURL))
{	
	function getCurrentURL()
	{
		$url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") ."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		return urlencode($url);
	}
}



if (!function_exists(echo_route))
{	
	function echo_route($routeID)
	{
		global $DB_PREFIX;
		$route = mysql_query("SELECT name, km 
								FROM ". $DB_PREFIX . "_routes
								WHERE routeID = '" . $routeID . "'");			
		$route_record = mysql_fetch_array($route);			
		echo('<a href="'.$RS_URL.'index.php?action=route&amp;routeid=' . $routeID . '">' 
				. $route_record['name'] . ' (' . $route_record['km'] . 'Km)' . '</a>');
	}
}

if (!function_exists(getRouteName))
{	
	function getRouteName($routeID)
	{
		global $DB_PREFIX;
		$route = mysql_query("SELECT name 
													FROM ". $DB_PREFIX . "_routes
													WHERE routeID = '" . $routeID . "'");			
		$route_record = mysql_fetch_array($route);
		return $route_record['name'];
	}
}

if (!function_exists(getRouteDistance))
{	
	function getRouteDistance($routeID)
	{
		global $DB_PREFIX;
		$route = mysql_query("SELECT km 
													FROM ". $DB_PREFIX . "_routes
													WHERE routeID = '" . $routeID . "'");			
		$route_record = mysql_fetch_array($route);
		return $route_record['km'];
	}
}

// ****************************************************************************
if(!function_exists(getTours))
{
	function getTours($userID)
	{
		global $DB_PREFIX;
		$query = filterSeasonQuery($DB_PREFIX."_tours.seasonID");
		return mysql_query("SELECT ". $DB_PREFIX . "_user_tours.tourID
							FROM ". $DB_PREFIX . "_user_tours, ". $DB_PREFIX . "_tours
							WHERE ".$DB_PREFIX."_user_tours.userID = '" . $userID . "'
							AND (".$query.")
							AND ". $DB_PREFIX . "_user_tours.tourID = ".$DB_PREFIX."_tours.tourID");
	}
}

// * receive an array of ". $DB_PREFIX . "_user_tours (tourID is needed) positions. Returns total Km's
// ****************************************************************************
if(!function_exists(getKilometers))
{
	function getKilometers($array_tourID)
	{
		global $DB_PREFIX;
		$km = 0;
		while ($row = mysql_fetch_array($array_tourID))
		{
			$res = mysql_query("SELECT routeID
							    FROM ". $DB_PREFIX . "_tours
							    WHERE tourID = '" . $row['tourID'] . "'");
			$routeID = mysql_fetch_array($res);
			$res = mysql_query("SELECT km
							    FROM ". $DB_PREFIX . "_routes
							    WHERE routeID = '" . $routeID['routeID'] . "'");
			$add = mysql_fetch_array($res);
			$km += $add['km'];
		}
		if (mysql_num_rows($array_tourID) > 0 ) // if there was some data
			mysql_data_seek($array_tourID, 0);
		return $km;
	}
}

// * draws a table with the last tours of a user, sorted by date desc
// ****************************************************************************
if(!function_exists(drawTableLastTours))
{
	function drawTableLastTours($userID)
	{
		global $DB_PREFIX;
		echo('<table width="500" border="0">
          <tr>
            <th scope="col"><div align="center">Fecha</div></th>
            <th scope="col"><div align="center">Ruta</div></th>
            <th scope="col"><div align="center">Asistentes</div></th>
          </tr>');
		/*$tour = mysql_query("SELECT tourID, tour_date, routeID
								 FROM ". $DB_PREFIX . "_tours
								 ORDER BY tour_date DESC");*/
		$tour = getSeasonTours("ORDER BY tour_date DESC");
		// draw general stats if no user selected						
		if ($userID == NULL)
		{
			echo('<p>' . mysql_num_rows($tour) . ' salidas realizadas, que suman un total de ' . getKilometers($tour) . 'Km.</p>');
		}						 
		while ($row = mysql_fetch_array($tour))
		{
			// check if user wents to the tour
			if ($userID != NULL)
			{
			$went = mysql_query("SELECT tourID 
								 FROM ". $DB_PREFIX . "_user_tours 
								 WHERE tourID = '". $row['tourID'] ."' AND userID = '" . $userID . "'");
				
			if(mysql_num_rows($went) == 0) continue;
			}
			
			//draw table
			$route_res = mysql_query("SELECT name
							 FROM ". $DB_PREFIX . "_routes
							 WHERE routeID = '" . $row['routeID'] . "'");
			$route = mysql_fetch_array($route_res);
			
			$member_res = mysql_query("SELECT userID
							 FROM ". $DB_PREFIX . "_user_tours
							 WHERE tourID = '" . $row['tourID'] . "'");
			$member_num = mysql_num_rows($member_res);
			echo('<tr>');
			
			echo('<td><div align="center"><a href="'.$RS_URL.'index.php?action=tour&amp;tourid=' . 
			$row['tourID'] . '">' . date("d m Y", strtotime($row['tour_date'])) . '</a></div></td>');
			
			echo('<td><div align="left"><a href="'.$RS_URL.'index.php?action=route&amp;routeid=' . 
			$row['routeID'] . '">' . $route['name'] . '</a></div></td>');
			
			echo('<td><div align="center">' . $member_num . '</div></td>');
			
			echo('</tr>');
		}
		echo('</table>');
	}
}

if (!function_exists(days_in_month))
{
	function days_in_month($month, $year)
	{
		return date('t', mktime(0, 0, 0, $month+1, 0, $year)); 
	}
}

// * returns a string telling when was las time since an event.
// ****************************************************************************
if(!function_exists(dateWhenWasString))
{
	function dateWhenWasString($phpdate)
	{
		// get now date 
		$n = strtotime( "now" );
		$now = getdate($n);
		$now_yy = $now['year'];
		$now_mm = $now['mon'];
		$now_dd = $now['mday'];
			
		$tr = getdate($phpdate);
		$tr_yy = $tr['year'];
		$tr_mm = $tr['mon'];
		$tr_dd = $tr['mday'];
		$days = 0;
		
		if ($tr_yy > $now_yy) return "error";
		else if ( ($tr_yy == $now_yy) && ($tr_mm > $now_mm) ) return "error";
		else if ( ($tr_yy == $now_yy) && ($tr_mm == $now_mm) && ($tr_dd > $now_dd) ) return "error";
		 
		//calculate days of difference between both dates
		while ( ($tr_yy != $now_yy) 
				|| ($tr_mm != $now_mm) 
				|| ($tr_dd != $now_dd) 
			  )
		{
			if ( $tr_dd <= days_in_month($tr_mm, $tr_yy) )
			{
				$tr_dd++;
				$days++;
			}
			else if ($tr_mm < 12)
			{
				$tr_mm++;
				$tr_dd=1;
			}
			else
			{
				$tr_yy++;
				$tr_mm=1;
				$tr_dd=1;
			}
		}
		$str = "";
		if ($days > 730)
		{
			$str = "hace más de dos años";
		}	
		else if ($days > 365)
		{
			$str = "el año pasado";
		}	
		else if ($days > 30)
		{	
			$n = 0;
			while($days > 30)
			{
				$days -= 30;
				$n++;
			}
			if ($n == 1)
			{
				$str = "hace 1 mes";
			}
			else
			{
				$str = "hace " . $n . " meses.";
			}
		}
		else if ($days > 1)
		{
			$str = "hace " . $days . " días";
		}
		else if ($days > 0)
		{
			$str = "ayer";
		}
		else
		{
			$str = "hoy";
		}
		return $str;
	}
}

					
?>