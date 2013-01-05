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
	require_once('settings.php');
	include "connect.php";
	include "stats.php";
	global $GROUP_NAME, $RS_URL, $DB_PREFIX;
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?=$title?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" 
   type="text/css" media="screen"
   href="<?=$style?>" />
</head>
<body>

<div id="central">
  <div id="header"> <br />
    <a href="./index.php"><?=$GROUP_NAME;?></a> </div>
  <ul id="navlist">
    <li><a id="n1" href="<?php echo($RS_URL); ?>/index.php">Principal</a></li>
    <li><a id="n2" href="<?php echo($RS_URL); ?>/index.php?action=users">Socios</a></li>
    <li><a id="n3" href="<?php echo($RS_URL); ?>/index.php?action=routes">Rutas</a></li>
    <li><a id="n4" href="<?php echo($RS_URL); ?>/index.php?action=tours">Salidas</a></li>
    <li><a id="n5" href="<?php echo($RS_URL); ?>/index.php?action=admin_login">Administrar</a></li>
    <li><a id="n6" href="<?php echo($RS_URL); ?>/index.php?action=faq">F.A.Q.</a></li>
    <li><a id="n7" ></a></li>
  </ul>
  <div id="content">
    <div id="leftside">
      <h2><a href="<?php echo($RS_URL); ?>/index.php">ESTADISTICAS</a></h2>
      <h3><a href="<?php echo($RS_URL); ?>/index.php?action=users">Ver Socios</a></h3>
      <h3><a href="<?php echo($RS_URL); ?>/index.php?action=routes">Ver Rutas</a></h3>
      <h3><a href="<?php echo($RS_URL); ?>/index.php?action=tours">Ver Salidas</a></h3>
      <?php if ($admin_rights) echo'<h2>ADMINISTRACIÓN</h2>
			<h3><a href="'.$RS_URL.'/index.php?action=edit_user">Zona Socios</a></h3>
			<h3><a href="'.$RS_URL.'/index.php?action=edit_route">Zona Rutas</a></h3>
			<h3><a href="'.$RS_URL.'/index.php?action=edit_tour">Zona Salidas</a></h3>';?>
      <p>&nbsp;</p>
      
	  
				
		<?php
		/**
			Displays seasons checkboxes
		*/
		echo '<form name="input" action='.$RS_URL.'/source/season_switch.php?URL='.getCurrentURL().' method="POST">';
		
		$con = connect();
		
		$seasons = mysql_query("SELECT ID, description FROM ". $DB_PREFIX . "_seasons");
		while($row = mysql_fetch_array($seasons))
		{
			echo '<p><input type="checkbox" name="seasons[]" value="'.$row['description'].'" ';
			if ($_SESSION['seasons'][$row['ID']])
			{
				echo 'checked="checked" ';
			}
			echo ' /> ' . $row['description'] . '<p/>';
		}
		
		disconnect($con);
		echo '<p><input type="submit" value="Submit" /></p> </form> ';
		?>
		
	  
	  <p>&nbsp;</p>
      <p><a style="text-decoration:none; color:#003399" href="<?php echo($RS_URL); ?>/source/styleswitch.php?set=blue">Tema Azul</a></p>
      <p><a style="text-decoration:none; color:#003399" href="<?php echo($RS_URL); ?>/source/styleswitch.php?set=red">Tema Rojo</a></p>
    </div><!--leftside-->
    
    <div id="rightside">
	    <h1><?=$title?></h1>
      <?=$body?>
    </div><!--rightside-->
    <div id="footer">
    	<!--Please do not remove this link. More people may be interested in this system!-->
      <p>Powered by <a href="http://ivan.org.es/ridestats/">Ride Stats</a></p>
    </div><!--footer-->
  </div><!--content-->
  <div id="imagepreloader"><img src="buttonover.gif" alt="mouseover" /></div>
</div><!--central-->

</body>

</html>
