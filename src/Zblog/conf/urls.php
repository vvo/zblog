<?php

// TODO: configure all this shit

$ctl = array();

$ctl[] = array('regex' => '#^/$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'homepage',
	'minifyHTML' => true,
	'method' => 'main');

$ctl[] = array('regex' => '#^/slider$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'slider',
	'minifyHTML' => true,
	'method' => 'slider');

$ctl[] = array('regex' => '#^/services/$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'services',
	'method' => 'services');

$ctl[] = array('regex' => '#^/références/$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'references',
	'method' => 'references');

$ctl[] = array('regex' => '#^/contact/$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'contact',
	'method' => 'contact');

$ctl[] = array('regex' => '#^/contact/sent/$#',
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'method' => 'contact_sent');

$ctl[] = array('regex' => '#^/blog/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
//	'minifyHTML' => true,
	'name' => 'blog_homepage',
	'method' => 'blog');

$ctl[] = array('regex' => '#^/blog/feed.xml$#',
	'cache_timeout' => 1800, // 30 minutes cache on the rss feed, not more
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_feed',
	'method' => 'atom_feed');

// nouveau tag/page + spécifique = plus en haut dans la priorité
$ctl[] = array('regex' => '#^/blog/tag/(\d+)/(.+)/page/(\d+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag_page',
	'method' => 'view_by_tag');

$ctl[] = array('regex' => '#^/blog/tag/(\d+)/(.+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag',
	'method' => 'view_by_tag');

$ctl[] = array('regex' => '#^/blog/tag/(.+)/page/(\d+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag_page_old',
	'method' => 'view_by_tag_page_old');

$ctl[] = array('regex' => '#^/blog/tag/(.+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag_old',
	'method' => 'view_by_tag_old');

$ctl[] = array('regex' => '#^/blog/page/(\d+)$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_page',
	'method' => 'blog');

// year archives
/* $ctl[] = array('regex' => '#^/blog/(\d{4})/$#',
  'priority' => 4,
  'base' => Pluf::f('zblog_base'),
  'model' => 'Zblog_Blog',
  'method' => 'view_year'); */

// month archives
$ctl[] = array('regex' => '#^/blog/month/(\d{4})/(\d{2})/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_month',
	'method' => 'view_month');

// day archives
$ctl[] = array('regex' => '#^/blog/day/(\d{4})/(\d{2})/(\d{2})/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_day',
	'method' => 'view_day');

// specific post new version
$ctl[] = array('regex' => '#^/blog/(\d+)/(.+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_post',
	'method' => 'view_post');

// specific post old version
$ctl[] = array('regex' => '#^/blog/(.+)/$#',
	'cache_timeout' => 3600*2,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_post_old',
	'method' => 'view_post_old');

// no need to cache this, better be not cached
$ctl[] = array('cache_timeout' => 1, 'regex' => '#^/deploy/$#', 'priority' => 4, 'base' => Pluf::f('zblog_base'), 'model' => 'Zblog_Views', 'method' => 'deploy');

return $ctl;
