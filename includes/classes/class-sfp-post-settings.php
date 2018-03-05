<?php

class Sfp_Post_Settings
{
	private $name; 
	private $slag; 
	private $postname; 

	public function __construct() {
		$this->set_post_params();
	}

	
	private function set_post_params() {
		$this->name     = "Requests"; 
		$this->slag     = "requests"; 
		$this->postname = "spa_requests"; 
	}

	public function get_name() {
		return $this->name;
	}

	public function get_slag() {
		return $this->slag;
	}

	public function get_postname() {
		return $this->postname;
	}
}
