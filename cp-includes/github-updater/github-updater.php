<?php 
/*
Based on https://github.com/YahnisElsts/plugin-update-checker
*/
require_once 'plugin-updates/plugin-update-checker.php';
$ExampleUpdateChecker = new PluginUpdateChecker(
	'https://raw.github.com/casepress-studio/casepress/master/info.json',
	__FILE__
);

?>