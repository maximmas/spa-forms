<?php
/*
Plugin Name: SPA Forms
Plugin URI: 
Description: Forms handler for the Royal Spa WordPress Theme
Version: 1.0
Author: Maxim Maslov
Author URI: maximmaslov.ru
License: GPLv2
Network: true
Text domain: sfp
Domain Path: /languages
*/

/*  Copyright 2018  Maxim Maslov  (email : me@maximmaslov.ru )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define ( "SFP_BOOKING_FORM", plugin_dir_path( __FILE__ ) . '/forms-templates/booking-form.html' );

require_once ( plugin_dir_path( __FILE__ ) . '/includes/classes/class-sfp-post-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin/settings-page.php' );

/**
 * Enqueue scripts and styles
 *
 */
function sfp_scripts() {

    $script_url = plugins_url( '/includes/js/handler.js', __FILE__ );
    $style_url  = plugins_url( '/includes/css/style.css', __FILE__ );
    $bpopup_url = plugins_url( '/includes/js/jquery.bpopup.min.js', __FILE__ );


    wp_enqueue_script( 'sfp-script', $script_url, array( 'jquery', 'sfp-bpopup' ) );
    wp_enqueue_script( 'sfp-bpopup', $bpopup_url, array('jquery') );

    // wp_enqueue_script( 'bootstrap', SPA_THEME_DIR . '/js/bootstrap.min.js',  );
    // wp_enqueue_script( 'bootstrap-datepicker', SPA_THEME_DIR . '/js/bootstrap-datepicker.min.js', $libs, false, true );
    // wp_enqueue_script( 'bootstrap-clockpicker', SPA_THEME_DIR . '/js/bootstrap-clockpicker.min.js', $libs, false, true );

    wp_enqueue_style( 'sfp-style', $style_url );

    // wp_enqueue_style( 'bootstrap', SPA_THEME_DIR . '/libs/bootstrap.min.css' ); 
    // wp_enqueue_style( 'bootstrap-clockpicker', SPA_THEME_DIR . '/libs/bootstrap-clockpicker.min.css' );
    // wp_enqueue_style( 'bootstrapdatepicker3', SPA_THEME_DIR . '/libs/bootstrap-datepicker3.min.css' );


};

add_action( 'wp_enqueue_scripts', 'sfp_scripts' );

function sfp_admin_scripts()
{
    wp_enqueue_script( 'sfp_admin_script', plugin_dir_url( __FILE__ ) . 'admin/js/admin-scripts.js', array(), false, true );
    wp_enqueue_script( 'jquery-ui-core', array('jquery') );
    wp_enqueue_script( 'jquery-ui-tabs', array('jquery', 'jquery-ui-core') );
    wp_enqueue_script( 'jquery-ui-accordion', array('jquery', 'jquery-ui-core') );
    wp_enqueue_script( 'jquery-ui-sortable', array('jquery', 'jquery-ui-core') );
    wp_enqueue_style( 'jquery-ui-style', plugin_dir_url( __FILE__ ) . 'admin/libs/jquery-ui.min.css' );
    wp_enqueue_style( 'scf-admin-style', plugin_dir_url( __FILE__ ) . 'admin/libs/scf-admin-style.css' ) ;
};
add_action( 'admin_enqueue_scripts', 'sfp_admin_scripts' );


/**
 * Returns html code of booking form
 *
 * Uses in the Booking shortcode
 *
 *
 * @return string
 *
 */
function sfp_booking_form(){
    $form_template   = file_get_contents( SFP_BOOKING_FORM, true );
    $options    = get_option( 'sfp_options' );
    //fw_print($options);

    $form = sprintf( $form_template,
                        $options['name'],
                        $options['email'],
                        $options['date'],
                        $options['time'],
                        $options['people'],
                        $options['more'],
                        $options['desc'],
                        $options['button'],
                        $options['send_err'],
                        $options['send_ok']
                );
           
    return $form;
};

/**
 * Register shortcodet to display form
 *
 */
add_shortcode( 'sfp_booking_form', 'sfp_booking_form' );


/**
 * Creates custom post type for storage forms's data
 *
 */
function sfp_register_custom_posts(){
    $settings = new Sfp_Post_Settings;
    $labels = array(
        'name' 					=> esc_html( $settings->get_name() ),
        'singular_name' 		=> esc_html( $settings->get_name() ),
        'menu_name' 			=> esc_html( $settings->get_name() )
    );
    $data = array(
        'labels' 				=> $labels,
        'public' 				=> true,
        'rewrite' 				=> array( 'slug'=>$settings->get_slag(), 'with_front'=>false ),
        'menu_icon' 			=> 'dashicons-thumbs-up',
        'has_archive' 			=> false,
        'hierarchical' 			=> false,
        'show_in_nav_menus'		=> false,
        'query_var' 			=> true,
        'menu_position' 		=> 108,
        'supports' 				=> array( 'title', 'editor' )
    );
    register_post_type( $settings->get_postname(), $data );
};
add_action( 'init', 'sfp_register_custom_posts' );


// sendmail handler
add_action( 'wp_ajax_booking', 'sfp_sendmail_contact' );
add_action( 'wp_ajax_nopriv_booking', 'spa_sendmail_contact' );
function sfp_sendmail_contact(){

    $options            = get_option( 'sfp_options' );
    $site_name          = get_bloginfo('name');
    $admin_template     = $options['admin_mail_temp'];
    $client_template    = $options['client_mail_temp'];
    $admin_email    = ( !empty( $options['admin_email'] ) ) ? $options['admin_email'] : get_bloginfo( 'admin_email' );
   
    $name       = sanitize_text_field( $_POST['name'] );
    $email      = sanitize_text_field( $_POST['email'] );
    $date       = sanitize_text_field( $_POST['date'] );
    $time       = sanitize_text_field( $_POST['time'] );
    $people     = sanitize_text_field( $_POST['people'] );
    $request    = sanitize_text_field( $_POST['message'] );

    $message_to_admin = sprintf( $admin_template, $site_name, $name, $email, $date, $time, $people, $request );
    $message_to_client = sprintf( $client_template, $site_name, $name, $email, $date, $time, $people, $request );

    $subject_to_admin = esc_html__( 'New message from ', 'sfp' ) . $site_name;
    $subject_to_client = esc_html__( 'Confirmation message from ', 'sfp' ) . get_bloginfo('name');
    
    // save data to custom post    
    sfp_create_post( $message_to_admin );
    
    // replace name / email   
    sfp_replace_sender_info( $admin_email );
    
     wp_mail( $admin_email,
              $subject_to_admin,
              $message_to_admin,
              "X-Mailer: PHP/" . phpversion() . "\r\n" . "Content-type: text/html; charset=\"utf-8\"");
    wp_mail( $email,
             $subject_to_client,
             $message_to_client,
             "X-Mailer: PHP/" . phpversion() . "\r\n" . "Content-type: text/html; charset=\"utf-8\"");
    
    echo "1"; 
    wp_die();
   
};

function sfp_replace_sender_info( $admin_email ){

    add_filter( 'wp_mail_from', function( $email ){ 
        return $admin_email; 
    });
    
    add_filter( 'wp_mail_from_name', function( $name )
        return get_bloginfo('name');
    });    
};

function sfp_create_post($text)
{
	$_post    = new Sfp_Post_Settings;
	$post_type = $_post->get_postname();
    $data_item = array(
        'post_title'        => "Customer request " . date_i18n('Y-m-d H:i'),
        'post_content'      => $text,
        'post_status'       => 'publish',
        'post_type'         => $post_type,
        'post_name'         => 'request' . date_i18n('Y-m-d H:i'),
        'comment_status'    => 'closed',
        'ping_status'       => 'closed'
    );
    $post_id = wp_insert_post( wp_slash( $data_item ) );
};

