<?php
/**
 * Plugin Name: NWPA Add Favicon Icon
 * Plugin URI: http://store.newworldltd.com/nwpa-favicon/
 * Description: Add favicon.ico to Wordpress
 * Version: 1.0.0
 * Author: New World Public Actions (NWPA)
 * Author URI: http://store.newworldltd.com/
 * License: GPL2
 */
 
 /*  Copyright 2015  NWPA  (email : appstore@newworldltd.com)

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

// Hook for adding admin menus
add_action('admin_menu', 'nwpa_add_pages');

function nwpa_add_pages() {
	// add with shortcode - Add a new submenu under Settings:
	add_options_page( 'NWPA Favicon', 'NWPA Favicon', 'manage_options', 'nwpafavicon', 'nwpa_favicon');
}

function nwpa_favicon(){
	
	//must check that the user has the required capability 
    if (!current_user_can('manage_options')){wp_die('You do not have sufficient permissions to access this page.');}
	
	$opt_name 			= 'nwpa_favicon_onoff'; 	// variables for the field and option names 
	$opt_val 			= get_option($opt_name); 	// Read in existing option value from database
	$hidden_field_name 	= 'nwpa_submit_hidden';		//
	$data_field_name 	= 'nwpa_favicon_onoff';		//
	
	// See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
	{
        if (!isset($_POST[$data_field_name]))
		{
			$opt_val = 0;	
		}
		else
		{
			// Read their posted value
			$opt_val = $_POST[$data_field_name];
			// SANITIZE $_POST
			$opt_val = sanitize_text_field($_POST[ $data_field_name ]);
			// validate
			if ($opt_val>=1){$opt_val = 1;}
			if ($opt_val<1){$opt_val = 0;}
		}
		
        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put a "settings saved" message on the screen
		echo '<div class="updated"><p><strong>settings saved.</strong></p></div>';
    }
	
	// Now display the settings editing screen
    echo '<div class="wrap">';
		echo "<h2>NWPA Favicon Plugin Settings</h2>"; // header
		// settings form
		?>

		<form name="form1" method="post" action="">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
			<p>
				Favicon ICON:
				<?php
				if ($opt_val == 1)
				{
				?>
					<select name="<?php echo $data_field_name; ?>" id="<?php echo $data_field_name; ?>"><option value="1" selected>ON</option><option value="0">OFF</option></select>
				<?php	
				}
				else if ($opt_val == 0)
				{
				?>
					<select name="<?php echo $data_field_name; ?>" id="<?php echo $data_field_name; ?>"><option value="1">ON</option><option value="0" selected>OFF</option></select>
					
				<?php
				}
				?>
			
			</p>
			
			
			<hr />
			<p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Changes"/></p>
		</form>
	</div>
	<?php	
}

// ADD(ODD) FAVICON ICON TO HEADER
function addfavicon() {
	
	// Get value from DB
	$opt_name 			= 'nwpa_favicon_onoff'; 
	$opt_val 			= get_option($opt_name);
	
	if ($opt_val==1)
	{
		if (is_single() || is_page()) /* If Post or Page */
		{ 
			$link = plugins_url( 'favicon.ico', __FILE__ );
			echo '<link rel="shortcut icon" type="image/x-icon" href="'.$link.'" />'.PHP_EOL;
		}
	}
}

add_action('wp_head', 'addfavicon');

?>