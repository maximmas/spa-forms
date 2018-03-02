<?php

class SpaFormPostSettings
{
	private $name; // cpt name
	private $slag; // cpt slag
	private $meta; // form fields

	public function __construct()
	{
		$this->setPostParams();
	}

	
	private function setPostParams()
	{
		$this->name = "Requests"; 
		$this->slag = "spa_requests"; 
		$this->meta = array(
						"name",
						"email",
						"date",
						"time",
						"people",
						"extra"
					);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSlag()
	{
		return $this->slag;
	}

	public function getMeta()
	{
		return $this->meta;
	}


}
