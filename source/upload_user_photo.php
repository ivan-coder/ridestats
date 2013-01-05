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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
include "connect.php";
include "stats.php";
include "parse_route.php";
?>

<?php
$len = strlen($_FILES["file"]["name"]);
$ext = substr($_FILES["file"]["name"], $len-4, $len);
$ext = strtolower($ext);
if ( ($ext == ".gif") || ($ext == ".jpg") || ($ext == "jpeg") )
{
	if ($_FILES["file"]["size"] < (30*1024))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "<p>Error: " . $_FILES["file"]["error"] . "</p>";
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],
				"local_data/usr_img/" . $_REQUEST['user_id'] . ".jpg");
				echo('
			<h2>Foto actualizada</h2>
				<p>La foto del socio ha sido actualizada. &iquest;Qu&eacute; deseas hacer ahora?</p>
				<p><a href="'.$RS_URL.'index.php?action=edit_user&amp;user_id=' . $_REQUEST['user_id'] . '">Volver a la ficha del socio</a></p>
			<p><a href="edit_user.php">Editar otro socio</a></p>');
		}
	}
	else
	{
	echo ('<p>Foto no valida. Comprueba que el peso de la foto no excede los 30KB</p>
			<p><a href="'.$RS_URL.'index.php?action=edit_user&amp;user_id=' . $_REQUEST['user_id'] . '">Volver a la ficha del socio</a></p>');
	}
}
else
{
echo ('<p>Archivo no valido. Tu archivo con la extensión '.$ext.' no parece valido. Comprueba que subes una imagen con formato .jpg o .jpeg</p>
		<p><a href="'.$RS_URL.'index.php?action=edit_user&amp;user_id=' . $_REQUEST['user_id'] . '">Volver a la ficha del socio</a></p>');
}
?>	  