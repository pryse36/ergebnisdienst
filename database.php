<?php defined('BASEDIR') OR die('No direct access allowed.');
$mysql_cfg = array(
  'host' => 'localhost',
  'database' => 'djk_ergebnisdienst',
  'username' => 'root',
  'password' => ''
);

$mysqli = new mysqli($mysql_cfg['host'], $mysql_cfg['username'], $mysql_cfg['password'], $mysql_cfg['database']);
$mysqli->set_charset('utf8');
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// create table 'liga_tabelle'
/*$mysqli->query("CREATE TABLE liga_tabelle (
  `url` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `logo` varchar(9999) COLLATE latin1_german2_ci DEFAULT NULL,
  `punkte` int(11) NOT NULL,
  `spiele` int(11) NOT NULL,
  `differenz` int(11) NOT NULL,
  `performance` int(11) DEFAULT NULL,
  PRIMARY KEY (`url`)
)"

);*/