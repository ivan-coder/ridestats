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
include 'settings.php';

global $ADMIN_PASSWORD, $DB_PREFIX, $RS_URL;
if (isset($_REQUEST["password"]))
{
	if ($_REQUEST["password"] == $ADMIN_PASSWORD)
	{
		// update las_login info
		include "connect.php";
		$con = connect();
		$date = date("Y-m-d H:i:s"); 
		mysql_query("UPDATE ". $DB_PREFIX . "_dates SET value = '" . $date . "' WHERE name = 'last_update'");
		disconnect($con);
		//set cookie
		setcookie("user", "Admin", time()+3600, "/");
		//redirect page
		//header('Location: '.$RS_URL.'/index.php?action=admin');
		header('Location: '.$RS_URL.'/index.php?action=admin');
	}
}
if (isset($_COOKIE["user"]))
{
	header('Location: '.$RS_URL.'/index.php?action=admin');
}
?>

<h1>Administrar</h1>
<p>Desde esta página es posible administrar los contenidos de la base de datos. Introduce el password a continuación.</p>

<form name="login" action="<?php echo $RS_URL; ?>/index.php?action=admin_login" method="post">
<p> Password: 
  <input type="password" name="password" />
  <input type="submit" value="Administrar" />
</p>
</form>

<?php
if (isset($_REQUEST["password"]))
{
	echo('<p>Password incorrecto.</p>');
}
?>