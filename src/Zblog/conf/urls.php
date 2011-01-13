<?php

// TODO: configure all this shit

$ctl = array();

$ctl[] = array('regex' => '#^/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'homepage',
	'method' => 'main');

// A/B testing new page
$ctl[] = array('regex' => '#^/2$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'homepage2',
	'minifyHTML' => true,
	'method' => 'main2');

$ctl[] = array('regex' => '#^/slider$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'slider',
	'minifyHTML' => true,
	'method' => 'slider');

$ctl[] = array('regex' => '#^/services/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'services',
	'method' => 'services');

$ctl[] = array('regex' => '#^/références/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'about',
	'method' => 'about');

$ctl[] = array('regex' => '#^/contact/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'name' => 'contact',
	'method' => 'contact');

$ctl[] = array('regex' => '#^/contact/sent/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Views',
	'method' => 'contact_sent');

$ctl[] = array('regex' => '#^/blog/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_homepage',
	'method' => 'blog');

$ctl[] = array('regex' => '#^/blog/feed.xml$#',
	'priority' => 4,
	'cache_timeout' => 1800, // 30 minutes cache on the rss feed, not more
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_feed',
	'method' => 'atom_feed');

$ctl[] = array('regex' => '#^/blog/tag/(.+)/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag',
	'method' => 'view_by_tag');

$ctl[] = array('regex' => '#^/blog/tag/(.+)/page/(\d+)/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_tag_page',
	'method' => 'view_by_tag');

$ctl[] = array('regex' => '#^/blog/page/(\d+)$#',
	'priority' => 4,
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
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_month',
	'method' => 'view_month');

// day archives
$ctl[] = array('regex' => '#^/blog/day/(\d{4})/(\d{2})/(\d{2})/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_day',
	'method' => 'view_day');

// specific post
$ctl[] = array('regex' => '#^/blog/(.+)/$#',
	'priority' => 4,
	'base' => Pluf::f('zblog_base'),
	'model' => 'Zblog_Blog',
	'name' => 'blog_post',
	'method' => 'view_post');

// no need to cache this, better be not cached
$ctl[] = array('cache_timeout' => 1, 'regex' => '#^/deploy/$#', 'priority' => 4, 'base' => Pluf::f('zblog_base'), 'model' => 'Zblog_Views', 'method' => 'deploy');

return $ctl;
