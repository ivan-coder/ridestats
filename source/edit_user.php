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
	if (!isset($_COOKIE['user']))
	{
		header("Location: login_error.php");
	}
include "connect.php";
include "stats.php";
include "parse_route.php";

global $DB_PREFIX;
?>
<h1>Administraci&oacute;n</h1>
<h1>Zona socios</h1>
<p>Desde esta p&aacute;gina, puedes editar o crear socios.</p>
<?php
  if (isset($_REQUEST['user_id']))
  {
    $con = connect();
    $users = mysql_query("SELECT name, userID, comment, join_date
       FROM ". $DB_PREFIX . "_users
       WHERE userID = '" . $_REQUEST['user_id'] . "'");
    disconnect($con);
    $user = mysql_fetch_array($users);
    echo('<h2>Datos socio ' . $user['name'] . '</h2>');
    // Upload photo file
    echo('<p><form method="post" action="'.$RS_URL.'index.php?action=save_user&amp;user_id=' . $_REQUEST['user_id'] . '">');
    echo('<p>Nombre:<input name="username" type="text" value = "' . $user['name'] . ' " /></p>');
    echo('<p>ID:<input name="user_id" type="text" readonly="readonly" value = "' .$user['userID'] . ' " /></p>');
    //echo('<p>Fecha(AAAA-MM-DD): <input name="join_date" type="text" value = "' . $user['join_date'] . '" /></p>');
    ////
    echo('<p>Fecha (AAAA-MM-DD): ');
    echo('<input name="join_date" value = "' . $user['join_date'] . ' ">');
    echo('<input type=button value="Elegir" onClick="displayDatePicker');
    echo("('join_date')");
    echo(';"></p>');
    ////
    
    echo('<p>Comentario:</p><p><textarea name="comment" cols="40" rows="6">' . $user['comment'] . '</textarea></p>');
    
    echo('<input type="submit" value="Guardar" name="save" /></form></p>');
    
    echo('<h2>Cambiar foto</h2>');
    echo('<p><img src="../local_data/usr_img/' . $_REQUEST['user_id'] . '.jpg" 
                alt="no hay imagen!" width="100" height="130" border="1" /></p>');
    echo('<form action="'.$RS_URL.'index.php?action=upload_user_photo&amp;user_id=' . $_REQUEST['user_id'] . '" method="post"
      enctype="multipart/form-data">
      <p>
      Foto (100 x 130, jpg):
      <input type="file" name="file" id="file" />
      <input type="submit" name="submit" value="Subir foto" />
      </p>
      </form>');
		echo('<p align="right"><a href="'.$RS_URL.'index.php?action=delete_user&amp;user_id=' . $_REQUEST['user_id'] . '">Borrar socio</a></p>');
  }
  else
  {
    // Editar
    echo('<h2>Editar socio</h2>');
    echo('<p><form method="post" action="'.$RS_URL.'index.php?action=edit_user">Elegir socio <select name="user_id">');
    $con = connect();
    $users = mysql_query("SELECT name, userID
           FROM ". $DB_PREFIX . "_users ORDER BY name ASC");
    while ($user = mysql_fetch_array($users))
    {
      echo('<option value = "' . $user['userID'] . '">' . $user['name'] . '</option>');
    }
    disconnect($con);
    echo('</select> <input type="submit" value="Editar" /></form></p>');
        
    // New users
    echo('<h2>Añadir nuevo socio</h2>');
    echo('<p><form method="post" action="'.$RS_URL.'index.php?action=new_user">');
    echo('Nombre: <input name="new_user" type="text" />');
    echo('<input type="submit" value="Crear Socio" /></form></p>');
  }		
?>