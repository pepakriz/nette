<?php

/**
 * Test: Nette\DI\Config\Adapters\IniAdapter
 *
 * @author     David Grudl
 * @package    Nette\DI
 */

use Nette\DI\Config;



require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.ini');


$config = new Config\Loader;
$data = $config->load('files/config.sample.ini', 'production');
Assert::same( array(
	'webname' => 'the example',
	'database' => array(
		'adapter' => 'pdo_mysql',
		'params' => array(
			'host' => 'db.example.com',
			'username' => 'dbuser',
			'password' => 'secret',
			'dbname' => 'dbname',
		),
	),
), $data );


$data = $config->load('files/config.sample.ini', 'development');
Assert::same( array(
	'webname' => 'the example',
	'database' => array(
		'adapter' => 'pdo_mysql',
		'params' => array(
			'host' => 'dev.example.com',
			'username' => 'devuser',
			'password' => 'devsecret',
			'dbname' => 'dbname',
		),
	),
	'timeout' => '10',
	'display_errors' => '1',
	'html_errors' => '',
	'items' => array('10', '20'),
	'php' => array(
		'zlib.output_compression' => '1',
		'date.timezone' => 'Europe/Prague',
	),
), $data );


$config->save($data, TEMP_FILE);
Assert::match( <<<EOD
; generated by Nette

webname = "the example"
database.adapter = "pdo_mysql"
database.params.host = "dev.example.com"
database.params.username = "devuser"
database.params.password = "devsecret"
database.params.dbname = "dbname"
timeout = 10
display_errors = 1
html_errors = ""
items.0 = 10
items.1 = 20
php.zlib..output_compression = 1
php.date..timezone = "Europe/Prague"
EOD
, file_get_contents(TEMP_FILE) );


$data = $config->load('files/config.sample.ini');
$config->save($data, TEMP_FILE);
Assert::match( <<<EOD
; generated by Nette

[production]
webname = "the example"
database.adapter = "pdo_mysql"
database.params.host = "db.example.com"
database.params.username = "dbuser"
database.params.password = "secret"
database.params.dbname = "dbname"

[development < production]
database.params.host = "dev.example.com"
database.params.username = "devuser"
database.params.password = "devsecret"
timeout = 10
display_errors = 1
html_errors = ""
items.0 = 10
items.1 = 20
php.zlib..output_compression = 1
php.date..timezone = "Europe/Prague"
EOD
, file_get_contents(TEMP_FILE) );
