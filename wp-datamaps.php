<?php
/**
 * Plugin Name: wp-datamaps
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the plugin.
 * Version: 1.0.0
 * Author: Linda G. Gorman
 * Author URI: http://lindaggorman.com
 * License: GPL2
 */



add_action('admin_menu', 'map_create_menu');

function map_create_menu() {

	add_menu_page('Map Plugin Settings', 'Map Settings', 'manage_options', __FILE__, 'map_settings_page','dashicons-location');
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'map-settings-group', 'coords' );
    add_settings_section('map_main', 'Map Settings', 'section_text', 'map-plugin');
    add_settings_field('map_settings_field', '', 'map_placename', 'map-plugin', 'map_main');

}

function section_text() {
    echo '<p> Fill in the fields below to customize your map.  Note that all colors must be entered in hex code format (ex. #b134dd).  After saving, paste the shortcode [d3map] into the post or page where you would like the map to appear. </p>';
}

/* Output the form options */
function map_placename() {
    $options = get_option('coords');
    echo "</tr><tr><td>Location:</td><td><input id='map_settings_field' name='coords[text_string]' type='text' value='{$options['text_string']}' required /></td></tr>";
    echo "<tr><td>Fill Color:</td><td><input id='map_settings_field' name='coords[color]' type='text' value='{$options['color']}' pattern='^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$' required /></td></tr>";
    echo "<tr><td>Highlight Color:</td><td><input id='map_settings_field' name='coords[hover]' type='text' value='{$options['hover']}' pattern='^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$' required/></td></tr>";
    echo "<tr><td>Marker Color:</td><td><input id='map_settings_field' name='coords[markerColor]' type='text' value='{$options['markerColor']}' pattern='^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$' required/></td></tr>";
    echo "<tr><td>Marker Highlight Color:</td><td><input id='map_settings_field' name='coords[markerHover]' type='text' value='{$options['markerHover']}' pattern='^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$' required/></td></tr>";
    echo "<tr><td>Height:</td><td><input id='map_settings_field' name='coords[height]' type='text' value='{$options['height']}' required/></td></tr>";
    echo "<tr><td>Width:</td><td><input id='map_settings_field' name='coords[width]' type='text' value='{$options['width']}' required/></td></tr>";
    echo "<tr><td>Marker Description:</td><td><textarea id='map_settings_field' name='coords[desc]' type='textarea' value='{$options['desc']}'></textarea></td></tr>";
}

/* Display the admin settings page */
function map_settings_page() {
?>
<div class="wrap">
<h2>Set Map Coordinates</h2><br><br>

<form method="post" action="options.php">
    <?php 
    settings_fields( 'map-settings-group' );
    do_settings_sections( 'map-plugin' ); 
    
    echo '<br><br>';
    submit_button(); ?>

</form>
</div>

<?php 

} 

/* Scripts outputted when the shortcode is inserted */
function map_code_func() {

    wp_enqueue_script('jquery');
    wp_enqueue_script('d3', plugins_url('js/d3.min.js', __FILE__));
    wp_enqueue_script('topojson', plugins_url('js/topojson.js', __FILE__));
    wp_enqueue_script('datamaps', plugins_url('js/datamaps.js', __FILE__));

    $custom = get_option('coords');
    
    wp_enqueue_script('main', plugins_url('js/script.js', __FILE__));
    wp_localize_script('main','options_object' ,$custom);


    $map = '<div id="container" style="position: relative; width:500px; height:300px; border:1px #ccc solid"></div>';
    return $map;
}

add_shortcode ('d3map', 'map_code_func');


?>



