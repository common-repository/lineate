<?php
/*
Plugin Name: Lineate
Description: Format poetry in WordPress with simple shortcodes.
Version: 1.0
Author: Johnathon Williams
Author URI: http://johnathonwilliams.com
*/

/**
 * Copyright (c) 2012 Johnathon Williams (Odd Jar). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// set default options on activation


register_activation_hook( __FILE__, 'ln_set_default_options' );

function ln_set_default_options() {
	$options = get_option( 'ln_plugin_options' );
	if ( ! $options ) {
		$options['indent_int'] = 30;
		$options['stanza_int'] = 40;
		update_option('ln_plugin_options', $options );
	}
}


function ln_insert_line_styles( $atts, $content = null ) {
	$atts = extract( shortcode_atts( array(
				'indent' => null,
				'stanza' => null
			), $atts ) );

	$options = get_option( 'ln_plugin_options' );
	$indent_level = $options['indent_int'];

	$inline_style = '';

	if ( $indent && $indent < 8) {
		(int) $indent;
		$indent_value = $indent * $indent_level;
		$indent_pixels = $indent_value . 'px';
		$inline_style = "style='padding-left:$indent_pixels;'";
	}

	return "<div class='lineate' $inline_style>" . $content . "</div>";
}
add_shortcode( 'lineate', 'ln_insert_line_styles' );

function ln_insert_stanza_styles( $atts = null, $content = null ) {

	$options = get_option( 'ln_plugin_options' );
	$stanza_margin = $options['stanza_int'];
	$margin_pixels = $stanza_margin . 'px';
	$inline_style = "style='margin-bottom:$margin_pixels;'";
	$markup = "<div class='lineate-stanza' $inline_style>" . do_shortcode( $content ) . "</div>";
	return $markup;
}
add_shortcode( 'stanza', 'ln_insert_stanza_styles' );

// fix a bug whereby WordPress surrounds nested shortcodes in p tags
add_filter( 'the_content', 'ln_shortcode_empty_paragraph_fix' );
function ln_shortcode_empty_paragraph_fix( $content ) {
	$array = array (
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']'
	);

	$content = strtr( $content, $array );

	return $content;
}

add_action( 'wp_enqueue_scripts', 'ln_add_stylesheet' );

function ln_add_stylesheet() {
	$style_url = plugins_url( 'css/lineate-style.css', __FILE__ );
	$style_file = WP_PLUGIN_DIR . '/lineate/css/lineate-style.css';
	if ( file_exists( $style_file ) ) {
		wp_register_style( 'lineate_style_sheet', $style_url );
		wp_enqueue_style( 'lineate_style_sheet' );
	}
}

// add tinymce buttons

add_action( 'admin_init', 'ln_create_tinymce_button' );

function ln_create_tinymce_button() {
	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'ln_add_plugin' );
		add_filter( 'mce_buttons', 'ln_register_button' );
	}
}

function ln_register_button( $buttons ) {
	array_push( $buttons, "|", "lineate" );
	return $buttons;
}

function ln_add_plugin( $plugin_array ) {
	$plugin_array['lineate'] = WP_PLUGIN_URL . '/lineate/js/lineate-tinymce-main.js';
	return $plugin_array;
}

add_action( 'admin_menu', 'ln_add_menu_page' );

function ln_add_menu_page() {
	add_menu_page( 'Lineate: Formatting for Poetry', 'Lineate', 'manage_options', 'lineate-menu', 'ln_display_page', WP_PLUGIN_URL . '/lineate/images/ln-icon.png' );
}

function ln_display_page() { ?>
	<div class="wrap">
		<?php screen_icon( 'plugins' ); ?>
		<h2>Lineate Settings</h2>
		<form action="options.php" method="post">
			<?php settings_fields( 'ln_plugin_options' ); ?>
			<?php do_settings_sections( 'ln_plugin' ); ?>
			<input name="Submit" type="submit" class="button-primary" value="Save Changes" />
		</form>
	</div><!-- wrap -->
<?php }

// Register and define the settings
add_action( 'admin_init', 'ln_plugin_admin_init' );
function ln_plugin_admin_init() {
	register_setting(
		'ln_plugin_options',
		'ln_plugin_options',
		'ln_plugin_validate_options'
	);

	add_settings_section(
		'ln_plugin_main',
		'',
		'ln_plugin_section_text',
		'ln_plugin'
	);

	add_settings_field(
		'ln_plugin_indent_int',
		'Width of base indent',
		'ln_plugin_indent_input',
		'ln_plugin',
		'ln_plugin_main'
	);

	add_settings_field(
		'ln_plugin_stanza_int',
		'Space between stanzas',
		'ln_plugin_stanza_input',
		'ln_plugin',
		'ln_plugin_main'
	);

}

// Draw the section header
function ln_plugin_section_text() {
	echo '<p>Here you can set the vertical distance between stanzas and the width of the base indent.</p> 
	<p>If you set a base indent of 20 pixels, then the first indented line will be 20 pixels 
	from the left margin, the second indented line 40 pixels, etc.</p>';
	settings_errors();
}

// Display and fill the form field
function ln_plugin_indent_input() {

	// get option 'text_string' value from the database
	$options = get_option( 'ln_plugin_options' );
	if ( ! $options['indent_int'] ) {
		$no_value = true;
		$indent_int = '30';
	} else {
		$no_value = false;
		$indent_int = $options['indent_int'];
	}

	echo "
	<input id='ln_plugin_indent_int' name='ln_plugin_options[indent_int]' type='text' value='$indent_int' /> pixels
	";

}

// Display and fill the form field
function ln_plugin_stanza_input() {
	// get option 'text_string' value from the database
	$options = get_option( 'ln_plugin_options' );

	if ( ! $options['stanza_int'] ) {
		$no_value = true;
		$stanza_int = '40';
	} else {
		$no_value = false;
		$stanza_int = $options['stanza_int'];
	}
	echo "
	<input id='ln_plugin_stanza_int' name='ln_plugin_options[stanza_int]' type='text' value='$stanza_int' /> pixels
	";
}

// Validate user input
function ln_plugin_validate_options( $input ) {
	$valid['indent_int'] = (int) $input['indent_int'];
	if ( $valid['indent_int'] != $input['indent_int'] ) {
		add_settings_error(
			'ln_plugin_indent_int',
			'ln_plugin_texterror',
			'Only numbers are allowed in these fields.',
			'error'
		);
	}

	$valid['stanza_int'] = (int) $input['stanza_int'];
	if ( $valid['stanza_int'] != $input['stanza_int'] ) {
		add_settings_error(
			'ln_plugin_stanza_int',
			'ln_plugin_texterror',
			'Only numbers are allowed in these fields.',
			'error'
		);
	}

	return $valid;
}