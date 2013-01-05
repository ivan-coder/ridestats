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
  global $RS_URL;
	if (!isset($_COOKIE["user"]))
	{
		header('Location: '.$RS_URL.'/index.php?action=login_error');
	}
?>
	<h1>Administraci&oacute;n</h1>
	<h2>Bienvenido,</h2>
	<p>Utiliza el men&uacute; de navegaci&oacute;n de la izquierda para administrar los contenidos de la base de datos.</p>
	<p>Te animamos a que lleves la iniciativa a la hora de actualizar la p&aacute;gina. Lo &uacute;nico que te pedimos es que seas responsable con los cambios que haces. Antes de hacer un cambio pi&eacute;nsalo al menos dos veces!</p>