<?php 
/**
* Class with plugin constants
*
*/

class Sfp_Constants
{
		
	function __construct(){}
		

	/**
    * Returns array of allowed html tags and attributes
    *
	* Uses in wp_kses functions
    *
    * @return array
    */
	public static function get_allowed_tags()
	{
		
		$tags = array(
			'a' => array(
				'href'	=> true,
				'title' => true,
				'rel'	=> true,
				'class' => true,
	            'target'  => true,
			),
			'abbr' => array(
				'title' => true
			),
			'acronym' => array(
				'title' => true
			),
			'b' => array(),
			'br' => array(),
			'blockquote' => array(
				'cite' => true
			),
			'cite' 	=> array(),
			'code' 	=> array(),
			'del' 	=> array(
				'datetime' => true
			),
			'em'	=> array(),
			'q'		=> array(
				'cite' => true
			),
			's'			=> array(),
			'strike' 	=> array(),
			'strong' 	=> array(),
			'div' 		=> array(
            	'data-os-animation' => true,
            	'class' 			=> true,
            	'data-swiper-parallax' => true,
            	'data-toggle' => true,
            	'data-target' => true
        	),
	        'span' => array(
	            'class' => true,
                'id' => true
	        ),
	        'ul' => array(
	            'class' => true,
	        ),
	        'li' => array(
	            'class' => true,
	        ),
	        'i' => array(
	            'class' => true,
	        ),
	        'img' => array(
	            'src'   	=> true,
	            'alt'		=> true,
	            'width'		=> true,
                'height'	=> true,
                'class'		=> true,
                'srcset'	=> true,
                'sizes'		=> true,
                'title'		=> true
	        ),
	        'h2' => array(
	            'class' => true,
	        ),
	        'h3' => array(
	            'class' => true,
	        ),
	        'h4' => array(
	            'class' => true,
	        ),
	        'blockquote' => array(
	            'class' => true,
	        ),
	        'cite' => array(
	            'class' => true,
	            'title' => true,
	        ),
	        'figure' => array(
	            'class' => true,
	        ),
	        'p' => array(
	            'class' => true,
	        ),
	        'footer' => array(
	            'class' => true,
	        ),
	        'form' => array(
	            'class' => true,
	            'id' => true,
	            'method' => true,
	            'action' => true,

	        ),
	        'label' => array(
	            'class' => true,
	            'for' => true
	        ),
	        'input' => array(
    			'class' => true,
    			'id' => true,
    			'name' => true,
    			'required' => true,
    			'data' => true,
    			'type' => true
	        ),
	        'select' => array(
	            'class' => true,
	            'id' => true
	        ),
	        'option' => array(
	            'class' => true,
	            'value' => true
	        ),
	        'button' => array(
	            'class' => true,
	            'type' => true
	        ),
	        'textarea' => array(
	            'class' => true,
	            'name' => true
	        ),
	        'section' => array(
	            'class' => true,
	            'style' => true,
	            'data' => true,
	        ),


		);

		return $tags;
	}



	
}