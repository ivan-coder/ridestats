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
/*
	Connect
*/
include_once 'settings.php';

if (!function_exists(connect))
{
	function connect()
	{
		global $DB_HOST, $DB_USER_NAME, $DB_PASSWORD, $DB_NAME;
		$con = mysql_connect($DB_HOST, $DB_USER_NAME, $DB_PASSWORD);
		if (!$con) {
			die('Could not connect: ' . mysql_error());
		}
		
		mysql_select_db($DB_NAME, $con);
		return $con;
	}
}

if (!function_exists(disconnect))
{
	function disconnect($con)
	{
		mysql_close($con);
	}
}
?>