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
if (isset($_REQUEST['Submit']))
{
	if ($_REQUEST['DB_USER'] != '' &&
			$_REQUEST['DB_PASS'] != '' &&
			$_REQUEST['DB_NAME'] != '' &&
			$_REQUEST['DB_HOST'] != '' &&
			$_REQUEST['DB_PREFIX'] != '' &&
			$_REQUEST['GROUP_NAME'] != '' &&
			$_REQUEST['ADMIN_PASSWORD'] != '')
			$isDataOk = 1;
	else $isDataOk = 0;
	$dataSended = 1;
}
?>
<?php
	function createTables($con)
	{
		error_reporting(E_ALL);
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_routes
		(
		routeID TINYINT(4) NOT NULL AUTO_INCREMENT ,
		name TEXT,
		description TEXT,
		km SMALLINT(6),
		PRIMARY KEY (routeID)
		) ENGINE = MYISAM';
		mysql_query($sql,$con);
		
		// Settings.
		/*$sql = 
		'DROP TABLE ' . $_REQUEST['DB_PREFIX'] . '_settings';		
		mysql_query($sql,$con);	*/
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_settings
		(
		name TEXT,
		value TEXT
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);
		
		// Temporal routes.
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_temp_routes
		(
		routeID TINYINT(4),
		name TEXT,
		km TINYINT(4),
		tours TINYINT(4),
		last_tour DATE
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);
		
		// Temporal tours
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_temp_tours
		(
		tourID TINYINT(4) ,
		routeID TINYINT(4) ,
		tour_date DATE,
		name TEXT,
		users TINYINT(4)
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);
		
		// Temporal users
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_temp_users
		(
		userID 		TINYINT(4) ,
		name 			TEXT,
		tours 		INT(16),
		km 				INT(16),		
		join_date DATE
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_users
		(
		userID TINYINT(4) NOT NULL AUTO_INCREMENT,
		name VARCHAR(32),
		join_date DATE,		
		comment TEXT,
		PRIMARY KEY (userID)
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_tours
		(
		tourID SMALLINT(6) NOT NULL AUTO_INCREMENT,
		tour_date DATE,	
		routeID TINYINT(4),
		seasonID TINYINT(4),
		PRIMARY KEY (tourID)
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);		
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_user_tours
		(
		userID TINYINT(4),
		tourID SMALLINT(6)
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);		
		
		/*$sql = 
		'DROP TABLE ' . $_REQUEST['DB_PREFIX'] . '_dates';		
		mysql_query($sql,$con);	*/
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_dates
		(
		name TINYTEXT,
		value DATETIME
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);	
		
		$sql = 
		'CREATE TABLE ' . $_REQUEST['DB_PREFIX'] . '_seasons
		(
		ID SMALLINT(6) NOT NULL AUTO_INCREMENT,
		description VARCHAR(20),
		PRIMARY KEY (ID)
		) ENGINE = MYISAM';		
		mysql_query($sql,$con);	
	}
	
	function populateTables($con)
	{
		$sql = 
		'INSERT INTO ' . $_REQUEST['DB_PREFIX'] . '_dates
		(name) VALUES ("last_update")';
		mysql_query($sql,$con);	
		
		$sql = 
		'INSERT INTO ' . $_REQUEST['DB_PREFIX'] . '_settings
		(name, value) VALUES ("current_track", "")';
		mysql_query($sql,$con);	
		$sql = 
		'INSERT INTO ' . $_REQUEST['DB_PREFIX'] . '_settings
		(name, value) VALUES ("maintainment_mode", NULL)';
		mysql_query($sql,$con);			
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
Ride Stats installer
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" 
	media="screen" title="User 
Defined Style" href="themes/blue/style.css" />
</head>
<body>
<div id="central">
  <div id="header"> <br />
    <a href="http://ridestats.meteorgames.es/">Ride Stats</a></div>
  <div id="content">
    <div id="rightside">
      <h1>Instalador Ride Stats</h1>
      <?php if ($dataSended && !$isDataOk) echo '<h3 align="center">Falta algún campo por rellenar!</h3>';?>
<?php 
	if ($isDataOk)
	{
		// Data is ok, so tries to connect
		
		// Provided data may be wrong, turn off all error messages
		error_reporting(0);
		
		// tries to connect to db
		$con = mysql_connect($_REQUEST['DB_HOST'], $_REQUEST['DB_USER'], $_REQUEST['DB_PASS']);
		
		// if db connection fails, send a message and go back.
		if (!$con) {
			echo ('<h3 align="center">No se ha podido conectar a la base de datos! El error fue: <strong>' . mysql_error()) .'</strong></h3>';
			echo '<p align="center">Revisa el nombre de usuario y contraseña de la base de datos y asegurate de que sean correctos.</p>';
		}
		
		else if (!mysql_select_db($_REQUEST['DB_NAME'], $con))
		{
			// Connection was right, but database name fails
			echo('<h3 align="center">Se ha conectado con el servidor, pero hubo un error eligiendo la base de datos! El error fue: <strong>' . mysql_error() .'</strong></h3>');
			echo '<h3> La/s base/s de datos encontradas fueron: </h3>';
			$db_list = mysql_list_dbs($con);
			while ($row = mysql_fetch_object($db_list)) {
					 echo '<h3><strong>' . $row->Database . '</strong></h3>';
					 }
			echo '<h3>Elige una de esta lista y colocala en el campo apropiado, seguramente todo vaya bien después de esto.</h3>';
		}		
		else
		{
			// all was right, write settings file
			error_reporting(E_ALL);
			$contents = 
'<?php'."\n".'
/* '."\n".'
	This file allows you to configure and customize the system.'."\n".'
*/'."\n".'

$DB_USER_NAME = "' . $_REQUEST['DB_USER'] . '";'."\n".'
$DB_PASSWORD = "' . $_REQUEST['DB_PASS'] . '";'."\n".'
$DB_HOST = "' . $_REQUEST['DB_HOST'] . '";'."\n".'
$DB_NAME = "' . $_REQUEST['DB_NAME'] . '";'."\n".'
$DB_PREFIX = "' . $_REQUEST['DB_PREFIX'] . '";'."\n".'
$GROUP_NAME = "' . $_REQUEST['GROUP_NAME'] . '";'."\n".'
$ADMIN_PASSWORD = "' . $_REQUEST['ADMIN_PASSWORD'] . '";'."\n".'
?>
';
			if (file_exists('settings.php'))
				unlink('settings.php');
			$handle = fopen("settings.php", "x");
			fwrite ($handle, $contents);
			fclose($handle);
			
			// Create and populate tables
			createTables($con);
			populateTables($con);
			
			// Disconnects
			mysql_close($con); 
			
			// send a congrat message to the user.
			echo '<p align="center">Todo fue bien! Enhorabuena!</p>';
			echo '<p align="center">Recuerda: es importante borrar el archivo "install.php" para no comprometer la seguridad de tu sitio.</p>';
			echo '<p align="center"><a href="index.php">Ir a mi nuevo sitio ahora!</a></p>';
			die();
		}
	}
	if (!$dataSended) echo '<p>Instalar Ride Stats es muy sencillo! S&oacute;lo tienes que rellenar los siguientes campos para completar la instalaci&oacute;n del sistema.</p>';
?>    
      <h2>Configuración de la base de datos MySQL</h2>
      <p>A continuación es necesario introducir los detalles del servidor de la base de datos. <strong>Tu proveedor de servicios de hosting debería facilitarte estos detalles</strong> o al menos hacer que puedas acceder a ellos fácilmente.</p>
      <form id="form1" name="form1" method="post" action="install.php">
      
        <label>
        <p><strong>Nombre Usuario</strong>: 
          <input type="text" name="DB_USER" id="DB_USER" value="<?=$_REQUEST['DB_USER'];?>" />
        </p>
        <p>Por ejemplo, <strong>'yo_mismo'</strong>. Debe tratarse del nombre que utilizas para conectarte a la base de datos; no puede ser cualquier nombre.</p>
        <hr />
        <br />
        <label><p><strong>Password</strong>:
          <input type="password" name="DB_PASS" id="DB_PASS" />
        </p>
        <p>El password para conectarse a la base de datos.</p>
        <hr />
        <br />
        <label><p><strong>Nombre base de datos</strong>:
          <input type="text" name="DB_NAME" id="DB_NAME" value="<?=$_REQUEST['DB_NAME'];?>" />
        </p>
        <p>Por ejemplo, <strong>'yo_mismo_db'</strong>.        </p>
        <hr />
        <p>&nbsp;</p>
        <label><p><strong>Prefijo para la base de datos</strong>:
          <input type="text" name="DB_PREFIX" id="DB_PREFIX" value="<?=$_REQUEST['DB_PREFIX'];?>" />
        </p>
        <p>Por ejemplo, <strong>'rs'</strong>. Las tablas de la base de datos serán creadas usando este prefijo. Procura que sea un nombre bastante corto (2-3 letras).</p>
        <hr />
        <p>&nbsp;</p>
        <label><p><strong>Host</strong>:
          <input type="text" name="DB_HOST" id="DB_HOST" value="localhost" />
        </p>
        <p>Si no lo conoces, en la mayor&iacute;a de casos 'localhost' deber&iacute;a funcionar.</p>
        <hr />
        <p>&nbsp;</p>
        <h2>Personalización del sitio</h2>
        <p>A continuaci&oacute;n puedes configurar algunos valores para personalizar la p&aacute;gina de estad&iacute;sticas.</p>
       	<label><p><strong>Nombre de tu peña ciclista</strong>:
          <input type="text" name="GROUP_NAME" id="GROUP_NAME" value="<?=$_REQUEST['GROUP_NAME'];?>" />
       	</p>
       	<p>Por ejemplo, <strong>'Mi pe&ntilde;a'</strong>.        </p>
        <hr />
        <br />
        
        <label><p><strong>Password para administrar el sitio</strong>:
          <input type="password" name="ADMIN_PASSWORD" id="ADMIN_PASSWORD" />
       	</p>
       	<p>Es el password que se utilizará para permitir cambiar los contenidos del sitio, por ejemplo para añadir rutas o salidas. No tiene por que ser el mismo que el de la base de datos.</p>
        <hr />
        <br />
        
        <p>Asegurate de haber completado TODOS los campos antes de proceder a enviar los datos</p>
        <p align="center"><label>Enviar
        <input type="submit" name="Submit" id="Submit" value="Aceptar" />
        </label></p>
            </form>
    </div>
    <div id="footer">
      <p>Powered by Ride Stats</p>
    </div>
  </div>
</div>
</body>
</html>
