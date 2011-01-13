<?php
ob_implicit_flush(true);
require dirname(__FILE__).'/path.php';
require 'Pluf.php';

// Where is your configuration file?
define('ADMIN_CONFIG_FILE', $path_to_Zblog.'/Zblog/conf/zblog.php');
Pluf::start(ADMIN_CONFIG_FILE);

if (count($_POST) === 0 && Pluf::f('debug') === false) {
	// production environment
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
		$dispatch = Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction());
		$foo = ob_get_contents();
		ob_end_clean();

		// how much time to keep the cache ?
		$cache_timeout = $dispatch[0]->view[0]['cache_timeout'];
		if($cache_timeout === null) {
			$cache_timeout = Pluf::f('cache_timeout'); // default to configuration option
		}

		// store in cache
		$cache->set(Pluf_HTTP_URL::getAction(), $foo, $cache_timeout);
	}
	
	echo $foo;
} else {
	// when in "debug" mode, most of the time its in home dev
	Pluf_Dispatcher::loadControllers(Pluf::f('zblog_urls'));
	Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction());
}