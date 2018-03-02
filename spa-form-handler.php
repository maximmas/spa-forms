<?php
/*
Plugin Name: SPA Forms Plugin
Plugin URI: 
Description: Forms handler for the Royal Spa WordPress Theme
Version: 1.0
Author: Maxim Maslov
Author URI: maximmaslov.ru
License: GPLv2
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

define ( "SPA_BOOKING_FORM", plugin_dir_path( __FILE__ ) . '/html-templates/booking-form.html' );

require_once ( plugin_dir_path( __FILE__ ) . '/includes/classes/SpaFormPostSettings.php' );
require_once( plugin_dir_path( __FILE__ ) . '/admin/settings-page.php' );

function sfpScripts() {

    $script_url = plugins_url( '/includes/js/handler.js', __FILE__ );
    $bpopup_url = plugins_url( '/includes/js/jquery.bpopup.min.js', __FILE__ );
    $style_url = plugins_url( '/includes/css/style.css', __FILE__ );

    wp_enqueue_script( 'sfp-script', $script_url, array( 'jquery', 'bpopup' ) );
    wp_enqueue_script( 'bpopup', $bpopup_url, array('jquery') );

    wp_enqueue_style( 'sfp-style', $style_url );

}

add_action( 'wp_enqueue_scripts', 'sfpScripts' );


function sfpAdminScripts()
{
    wp_enqueue_script('sfp_admin_script', plugin_dir_url( __FILE__ ) . 'admin/js/admin-scripts.js', array(), false, true);

    wp_enqueue_script('jquery-ui-core', array('jquery'));
    wp_enqueue_script('jquery-ui-tabs', array('jquery', 'jquery-ui-core'));
    wp_enqueue_script('jquery-ui-accordion', array('jquery', 'jquery-ui-core'));
    wp_enqueue_script('jquery-ui-sortable', array('jquery', 'jquery-ui-core'));
    wp_enqueue_style('jquery-ui-style', plugin_dir_url( __FILE__ ) . 'admin/libs/jquery-ui.min.css');
    wp_enqueue_style('scf-admin-style', plugin_dir_url( __FILE__ ) . 'admin/libs/scf-admin-style.css');

};
add_action('admin_enqueue_scripts', 'sfpAdminScripts');


/**
 * Returns html code of booking form
 *
 * Uses in the Booking shortcode
 *
 *
 * @return string
 *
 */
function sfpBookingForm(){
    $spa_form = file_get_contents( SPA_BOOKING_FORM, true );
    $params   = get_option('sfp_options');

    $spa_form = sprintf( $spa_form,
                        $params['name'],
                        $params['email'],
                        $params['date'],
                        $params['time'],
                        $params['people'],
                        $params['more'],
                        $params['desc'],
                        $params['button'],
                        $params['send_err'],
                        $params['send_ok']
                );
           
    return $spa_form;
    
    
};


/**
 * Creates custom post type for storage forms's data
 *
 */
function sfpRegisterCustomPosts(){
    $_post = new SpaFormPostSettings;
    $labels = array(
        'name' 					=> esc_html( $_post->getName() ),
        'singular_name' 		=> esc_html( $_post->getName() ),
        'menu_name' 			=> esc_html( $_post->getName() )
    );
    $data = array(
        'labels' 				=> $labels,
        'public' 				=> true,
        'rewrite' 				=> array( 'slug'=>$_post->getSlag(), 'with_front'=>false ),
        'menu_icon' 			=> 'dashicons-thumbs-up',
        'has_archive' 			=> false,
        'hierarchical' 			=> false,
        'show_in_nav_menus'		=> false,
        'query_var' 			=> true,
        'menu_position' 		=> 108,
        'supports' 				=> array( 'title', 'custom-fields' )
    );
    register_post_type( $_post->getSlag(), $data );
};
add_action( 'init', 'sfpRegisterCustomPosts' );



// sendmail handler
add_action( 'wp_ajax_booking', 'spa_sendmail_contact' );
add_action( 'wp_ajax_nopriv_booking', 'spa_sendmail_contact' );
function spa_sendmail_contact(){
  //   $spa_settings_options    = fw_get_db_settings_option();
  //   $spa_recepient           = $spa_settings_options['email'];
    $spa_subject             = esc_html__( 'New contact from ', 'spa' ) . get_bloginfo( 'name' );
    $spa_charset             = get_bloginfo( 'charset' );

    $spa_name  = sanitize_text_field( $_POST['name'] );
    $spa_email = sanitize_email( $_POST['email'] );
    $spa_date  = sanitize_email( $_POST['date'] );
    $spa_time  = sanitize_email( $_POST['time'] );
    $spa_message = sanitize_text_field( $_POST['message'] );
  
   	
   	$aero_options       = get_option('aero_options');
    $admin_email        = $aero_options['option_email'];
    $admin_email        = (!empty($admin_email)) ? $admin_email : get_bloginfo('admin_email');
    $site_name          = get_bloginfo('name');

    $client_name        = sanitize_text_field($_POST["name"]);
    $client_phone       = sanitize_text_field($_POST["phone"]);
    $client_question    = sanitize_text_field($_POST["message"]);
    $client_email       = sanitize_text_field($_POST["email"]);
    
    $message = '-------------------<br><br>';
    $message .= 'Дата вопроса: ' . date_i18n('Y-m-d H:i') . '<br><br>';
    $message .= 'Контактное лицо: ' . $client_name . '<br><br>';
    $message .= 'Телефон: ' . $client_phone . '<br><br>';
    $message .= 'Email: ' . $client_email . '<br><br>';
    $message .= 'Вопрос: ' . $client_question . '<br><br>';

    /* собираем текст сообщения для администратора */
    $message_to_admin = 'Получен новый вопрос на сайте ' . $site_name . '<br>';
    $message_to_admin .= $message;
    $subject_to_admin = 'Новый вопрос на сайте ' . $site_name;

    /* собираем текст сообщения для клиента */
    $message_to_client = 'Спасибо за обращение на сайте ' . $site_name . ' !<br><br>';;
    $message_to_client .= 'Вы оставили следующую информацию:' . '<br><br>';
    $message_to_client .= $message;
    $subject_to_client = 'Подтверждение обращения на сайте ' . get_bloginfo('name');

    
    sfpCreatePost($message);

    function aero_sender_email( $email ) {
        $aero_options = get_option('aero_options');
        $sender_email = $aero_options['option_email']; 
        $sender_email = ( !empty($sender_email) ) ? $sender_email : get_bloginfo('admin_email');
        return $sender_email;
    };

    function aero_sender_name( $name ) {
        return get_bloginfo('name');
    };
    
    add_filter( 'wp_mail_from', 'aero_sender_email' );
    add_filter( 'wp_mail_from_name', 'aero_sender_name' );

    wp_mail($admin_email, $subject_to_admin, $message_to_admin, "X-Mailer: PHP/" . phpversion() . "\r\n" . "Content-type: text/html; charset=\"utf-8\"");
    wp_mail($client_email, $subject_to_client, $message_to_client, "X-Mailer: PHP/" . phpversion() . "\r\n" . "Content-type: text/html; charset=\"utf-8\"");
    wp_die();

    echo "1";
};

function sfpCreatePost($text)
{
	$_post    = new SpaFormPostSettings;
	$post_type = $_post->getSlag()
    $data_item = array(
        'post_title'        => "Customer request " . date_i18n('Y-m-d H:i'),
        'post_content'      => $text,
        'post_status'       => 'publish',
        'post_type'         => $post_type,
        'post_name'         => 'request' . date_i18n('Y-m-d H:i'),
        'comment_status'    => 'closed',
        'ping_status'       => 'closed'
    );
    $post_id = wp_insert_post(wp_slash($data_item));
};

?>