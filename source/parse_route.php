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
if (!defined("parse_route"))
{
	define("parse_route", 1);
	global $parser;
	$parser=xml_parser_create();
	if (!$parser) echo 'Parser couldn´t be created!';
	$isLinestringNodeReached = false;
	$parsing = false;
	$cnt = 0;		
	
	if (!function_exists(start))
	{
	function start($parser,$element_name,$element_attrs)
	  {
		global $isLinestringNodeReached;
	  	if ($element_name == "LINESTRING")
		{
			$isLinestringNodeReached = true;
		}
		
		if ( ($element_name == "COORDINATES") && ($isLinestringNodeReached) )
		{
			global $parsing;
			$parsing = true;
			//echo('COOR Init <br />');
		}					
	  }
	}
	if (!function_exists(stop))
	{
	function stop($parser,$element_name)
	  {
		if ($element_name == "COORDINATES")
		{
			global $parsing;
			$parsing = false;
			//echo('COOR End <br />');
		}
	  }
	 }
	
	if (!function_exists(char))
	{
	function char($parser,$data)
	  {
		global $parsing, $cnt, $coor;
		if ($parsing == true)
		{
			//echo ($cnt . ': ' . $data . '<br />');
			if (strlen($data) > 1)
			{
				$coor[$cnt]=$data;
				$cnt++;
			}
		}
	   }
	}
	
	// Returns an array with coords:
	// "lon, lat, alt"
	if (!function_exists(getCoorArray))
	{
		function getCoorArray($filename)
		{
			global $parser, $coor;
			xml_set_element_handler($parser,"start","stop");
			xml_set_character_data_handler($parser,"char");
			$fp=fopen($filename,"r");
			
			//Read data
			while ($data=fread($fp,524288))
			  {
			  xml_parse($parser,$data,feof($fp)) or 
			  die (sprintf("XML Error: %s at line %d", 
				xml_error_string(xml_get_error_code($parser)),
				xml_get_current_line_number($parser)));
			  }
			
			//Free the XML parser
			xml_parser_free($parser);
			return $coor;
		}
	}
} // define end
	
?>