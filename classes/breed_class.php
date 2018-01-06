<?php
class Breed {
	public $no;
	public $spec;
	public $desc;
	public $short_desc;
	public $billcat;
	
	public function __construct($row) {
		$this->no = (int) $row['breed_no'];
		$this->spec = $row['breed_spec'];
		$this->desc = $row['breed_desc'];
		$this->short_desc = $row['breed_shortdesc'];
		$this->billcat = $row['breed_billcat'];
	}
}

class Breeds {
	public $by_no = array();
	public $by_short_desc = array();
	public $count = 0;
	
	public $isLoaded = FALSE;
	
	public function __construct() {
	}
	
	public function load($force = FALSE) {
		global $petadmin_db;
		
		$sql = "Select breed_no, breed_spec, breed_desc, breed_shortdesc, breed_billcat from vw_breed";
		
		if ($this->isLoaded and ! $force) {
			return;
		}
		
		if ($this->isLoaded) {
			$this->by_no = array();
			$this->by_short_desc = array();
			$this->count = 0;
		}
		
		crowbank_log('Loading breeds');
		
		$result = $petadmin_db->execute($sql);
		
		foreach($result as $row) {
			$breed = new Breed($row);
			$this->count++;
			$this->by_no[$breed->no] = $breed;
			$this->by_short_desc[$breed->short_desc] = $breed;
		}
		
		$this->isLoaded = TRUE;
	}
	
	public function get_by_no($no) {
		$this->load();
		return array_key_exists($no, $this->by_no) ? $this->by_no[$no] : NULL;
	}
	
	public function get_by_short_desc($short_desc) {
		$this->load();
		return array_key_exists($short_desc, $this->by_short_desc) ? $this->by_short_desc[$short_desc] : NULL;
	}
	
}