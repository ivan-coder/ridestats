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
include "../settings.php";
include "connect.php";

global $GROUP_NAME, $RS_URL, $DB_PREFIX;

session_start();

$con = connect();
		
$seasons = mysql_query("SELECT ID, description FROM ". $DB_PREFIX . "_seasons");

foreach ($_REQUEST['seasons'] as $value)
{
	echo 'Value: ' . $value . '<br />';
}
while($row = mysql_fetch_array($seasons))
{
	$exists = false;
	foreach ($_REQUEST['seasons'] as $value)
	{
		if ($value == $row['description'])
		{
			//echo 'true (session: '.$_SESSION['seasons'].') (row id:'.$row['ID'].')<br />';
			$exists = true;
		}
		else
		{
			//echo 'false <br />';
		}
	}
	$_SESSION['seasons'][$row['ID']] = $exists;
}

print_r($_SESSION);  

disconnect($con);

echo '<br />URL: '.$_REQUEST['URL'].'<br />';

header("Location: ".$_REQUEST['URL']);
?>