<?php
class Language extends Controller {
	private $default = 'en-gb';
	private $directory, $code;
	private $data = array();
	private $db;
	private $languages;

	public function __construct($code = 'en', $registry) {
		//pr($code);

		$this->db = $registry->get('db');
		$languages = $this->db->query("select * from `" . DB_PREFIX . "language` where `code` = '" . $code . "'");
		if ($languages->num_rows) {
			foreach ($languages->rows as $val) {
				$this->languages[$val['code']] = $val['directory'];
			}
			$this->code = $code;
		} else {
			// Default language English gb eb-gb
			$this->languages['en'] = 'en-gb';
			$this->code = 'en';
		}
		//pr($code);

		$this->directory = $this->languages[$this->code];
		!$this->directory ? !pr($this->languages) && !pr($this->code) : false;
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	// Please dont use the below function i'm thinking getting rid of it.
	public function all() {
		return $this->data;
	}

	// Please dont use the below function i'm thinking getting rid of it.
	public function merge(&$data) {
		array_merge($this->data, $data);
	}

	public function load($filename, &$data = array()) {
		/* if ($filename == $this->code) {
		  $filename = $this->directory;
		  } */
		$_ = array();

		//	pr($filename);
		// $this->directory = $this->languages[]; // CODE!!!
		// $file = DIR_LANGUAGE . 'english/' . $filename . '.php';
		// Compatibility code for old extension folders
		// $old_file = DIR_LANGUAGE . 'english/' . str_replace('extension/', '', $filename) . '.php';
		// pr($filename);

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';
		if (is_file($file)) {
			require($file);
		} else {
			require( DIR_LANGUAGE . $this->directory . '/' . $this->directory . '.php' );
		}


		// pr($this->languages); 		pr($this->directory);



		$this->data = array_merge($this->data, $_);

		return $this->data;
	}

}