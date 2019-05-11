<?php

namespace Phpsa\Datastore\Ams;

use Phpsa\Datastore\Asset;

class RobotsTxtAsset extends Asset{

	public $name = 'Robots.txt';
	public $shortname = 'robots_txt';
	public $max_instances = 1;


	public $meta_description = 'off'; // set to 'off' to turn it off
	public $meta_keywords	 = 'off'; // set to 'off' to turn it off

	public $value_equals = 'entries';
	public $properties = array(
		'entries' => array(
			'name' => 'Entries',
			'type' => self::TEXT,
			'required' => false,
			'help' => 'setup your robots.txt file here'
		)
	);

	public static function about() {
		return 'A robots.txt file for use in the site';
	}

}

?>
