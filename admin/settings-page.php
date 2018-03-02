<?php 
/**
 * SPA Forms settings page
 *
 * v 1.0
 *
 */


// Plugin settings page registration
add_action( 'admin_menu', 'sfp_create_settings_menu' );
function sfp_create_settings_menu()
{
    add_plugins_page( 'SPA Forms setup', 'SPA Forms setup', 'manage_options', 'sfp_setup', 'sfp_setup_page' );
    add_action( 'admin_init', 'sfp_register_settings' );
};

function sfp_register_settings()
{
    register_setting( 'sfp_options', 'sfp_options', 'sfp_sanitize_options' );
};

function sfp_setup_page()
{
    ?>
    <div>
        <h2><?php esc_html_e('SPA Forms settings page', 'sfp'); ?></h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('sfp_options');
            $sfp_options = get_option('sfp_options');

            /* set default values */
            
            /* form labels*/
            $sfp_name    = $sfp_options['name'] ? $sfp_options['name'] : 'YOUR NAME';
            $sfp_email   = $sfp_options['email'] ? $sfp_options['email'] : 'YOUR E-MAIL';
            $sfp_date    = $sfp_options['date'] ? $sfp_options['date'] : 'DATE';
            $sfp_time    = $sfp_options['time'] ? $sfp_options['time'] : 'TIME';
            $sfp_people  = $sfp_options['people'] ? $sfp_options['people'] : '# PEOPLE';
            $sfp_more    = $sfp_options['more'] ? $sfp_options['more'] : 'I have more than 6 people in my party';
            $sfp_desc    = $sfp_options['desc'] ? $sfp_options['desc'] : 'SPECIAL REQUESTS';
            $sfp_button  = $sfp_options['button'] ? $sfp_options['button'] : 'SEND MESSAGE';

            /* sending responses */
            $sfp_send_ok    = $sfp_options['send_ok'] ? $sfp_options['send_ok'] : 'Thanks for your message!';
            $sfp_send_err   = $sfp_options['send_err'] ? $sfp_options['send_err'] : 'Sendind error! Try again later';

            ?>

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b><?php esc_html_e( 'Booking form', 'sfp' ); ?></b></a></li>
                    <li><a href="#tabs-2"><b><?php esc_html_e( 'Response', 'sfp' ); ?></b></a></li>
                </ul>
                <!-- Form labels section -->
                <div id="tabs-1">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Name field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[name]"
                                       value="<?php echo esc_attr($sfp_name); ?>"
                                       placeholder="<?php echo esc_attr($sfp_name); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Email field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[email]"
                                       value="<?php echo esc_attr($sfp_email); ?>"
                                       placeholder="<?php echo esc_attr($sfp_email); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Date field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[date]"
                                       value="<?php echo esc_attr($sfp_date); ?>"
                                       placeholder="<?php echo esc_attr($sfp_date); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Time field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[time]"
                                       value="<?php echo esc_attr($sfp_time); ?>"
                                       placeholder="<?php echo esc_attr($sfp_time); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( '#People field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[people]"
                                       value="<?php echo esc_attr($sfp_people); ?>"
                                       placeholder="<?php echo esc_attr($sfp_people); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'More people field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[more]"
                                       value="<?php echo esc_attr($sfp_more); ?>"
                                       placeholder="<?php echo esc_attr($sfp_more); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Special requests field label', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[desc]"
                                       value="<?php echo esc_attr($sfp_desc); ?>"
                                       placeholder="<?php echo esc_attr($sfp_desc); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Button text', 'sfp' ); ?></th>
                            <td><input type="text" name="sfp_options[button]"
                                       value="<?php echo esc_attr($sfp_button); ?>"
                                       placeholder="<?php echo esc_attr($sfp_button); ?>"/></td>
                        </tr>
                    </table>
                </div>
                <!-- responses -->
				<div id="tabs-2">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Notification of successful sending', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[send_ok]"
                                       value="<?php echo esc_attr($sfp_send_ok); ?>"
                                       placeholder="<?php echo esc_attr($sfp_send_ok); ?>"
                                />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Notification of error sending', 'sfp' ); ?></th>
                            <td>
                                <input type="text" name="sfp_options[send_err]"
                                       value="<?php echo esc_attr($sfp_send_err); ?>"
                                       placeholder="<?php echo esc_attr($sfp_send_err); ?>"
                                />
                            </td>
                        </tr>
                    </table>
                </div>
                
	            <p class="submit">
    	            <input type="submit" class="button-primary" value="Save Changes"/>
        	    </p>
        </form>
    </div>
    <?php
};


function sfp_sanitize_options($input)
{
    $input['desc'] 	    = sanitize_text_field( $input['desc'] );
    $input['email'] 	= sanitize_text_field( $input['email'] );
    $input['name'] 	    = sanitize_text_field( $input['name'] );
    $input['time']      = sanitize_text_field( $input['time'] );
    $input['date']      = sanitize_text_field( $input['date'] );
    $input['people']    = sanitize_text_field( $input['people'] );
    $input['more']      = sanitize_text_field( $input['more'] );
    $input['send_ok']   = sanitize_text_field( $input['send_ok'] );
    $input['send_err']  = sanitize_text_field( $input['send_err'] );

    return $input;
};

