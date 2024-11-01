<?php
/*
Plugin Name: WP Bashorg
Plugin URI: http://adminofsystem.net/
Description: Wordpress bashorg parser 
Version: 1.1
Author: Yahin Ruslan
Author URI: http://adminofsystem.net
*/

/*  Copyright 2010  Yahin Ruslan (email : nessus@adminofsystem.netL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function bashorg_widget_control()
{
   if(!empty($_REQUEST['bashorg_widget_title'])) {
        update_option('bashorg_widget_title', $_REQUEST['bashorg_widget_title']);
   }

   if(!empty($_REQUEST['bashorg_widget_count'])) {
        update_option('bashorg_widget_count', $_REQUEST['bashorg_widget_count']);
   }
  
   echo '<table>';
   echo "<tr><td>Title: </td><td><input type='text' name='bashorg_widget_title' value=" . get_option('bashorg_widget_title') . "></td></tr>"; 
   echo "<tr><td>Count: </td><td><input type='text' name='bashorg_widget_count' value=" . get_option('bashorg_widget_count') . "></td></tr>";
   echo '</table>';
}
function bashorg_widget_show( $args )
{

   extract($args);   
   $count = get_option('bashorg_widget_count');
   $n = 0;

   echo $before_widget; 
   echo $before_title;
   echo get_option('bashorg_widget_title');
   echo $after_title.'<ul>'; 
   foreach(file('http://bash.im/') as $line) 
   {
	if(preg_match('/<div class="text">(.+)<\/div>/',$line,$matches)) 
        {
		if( $n < $count ) 
		{
             		echo '<li>' . iconv('CP1251','UTF-8',html_entity_decode($matches[1])) . '</li>'; 
             		$n++;
          	}
          	else 
		{
             		echo '</ul>' . $after_widget;
             		return; 
          	}      
      	}
   }
   echo '</ul>' . $after_widget;
}
function bashorg_widget_init()
{
   register_sidebar_widget('Bashorg', 'bashorg_widget_show');
   register_widget_control('Bashorg', 'bashorg_widget_control');
}
function bashorg_activate()
{
   add_option('bashorg_widget_title', 'Title');	
   add_option('bashorg_widget_count', 4);
}
function bashorg_deactivate()
{
   delete_option('bashorg_widget_title');
   delete_option('bashorg_widget_count');	
}

add_action('plugins_loaded', 'bashorg_widget_init');
register_activation_hook( __FILE__, 'bashorg_activate');
register_deactivation_hook( __FILE__, 'bashorg_deactivate');
?>
