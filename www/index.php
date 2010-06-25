<?php
$path_to_Pluf = realpath(dirname(__FILE__).'/../../pluf/src');
$path_to_CSSJSBooster = realpath(dirname(__FILE__).'/../www/CSS-JS-Booster');
// admin specified because we want to use it and be installed when we start the website but this is not mandatory to have it here
$path_to_Admin = realpath(dirname(__FILE__).'/../../admin/src');
$path_to_Zblog = realpath(dirname(__FILE__).'/../src');

set_include_path(get_include_path().
		PATH_SEPARATOR.$path_to_Pluf.PATH_SEPARATOR.$path_to_Zblog
		.PATH_SEPARATOR.$path_to_Admin.PATH_SEPARATOR.$path_to_CSSJSBooster);

require 'Pluf.php';

Pluf::start($path_to_Zblog.'/Zblog/conf/zblog.php');

if (count($_POST) === 0 && Pluf::f('debug') === false) {
	$cache = Pluf_Cache::factory();
	if (null === ($foo=$cache->get(Pluf_HTTP_URL::getAction()))) {
		ob_start();
		// As we are using a dispatcher, we need to load the corresponding
		// view controllers. The controllers are just a mapping between the query
		// string and corresponding classes and methods.
		Pluf_Dispatcher::loadControllers(Pluf::f('zblog_urls'));
		// Dispatch the call. Note that the use of a dispatcher is not
		// mandatory at all, you can create any number of .php file to dispatch
		// manually. A dispatcher enables the use of only one index.php file.
		Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction());
		$foo = ob_get_contents();
		ob_end_clean();
		$cache->set(Pluf_HTTP_URL::getAction(), $foo);
	}
	ob_implicit_flush(true);
	echo $foo;
} else {
	Pluf_Dispatcher::loadControllers(Pluf::f('zblog_urls'));
	Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction());
}