<?php

// TODO: configure all this shit

$ctl = array();

$ctl[] = array('regex' => '#^/$#',
		'priority' => 4,
		'base' => Pluf::f('zblog_base'),
		'model' => 'Zblog_Views',
		'name' => 'homepage',
		'method' => 'main');

$ctl[] = array('regex' => '#^/prestations/$#',
		'priority' => 4,
		'base' => Pluf::f('zblog_base'),
		'model' => 'Zblog_Views',
		'name' => 'services',
		'method' => 'prestations');

$ctl[] = array('regex' => '#^/à-propos/$#',
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
		'base' => Pluf::f('zblog_base'),
		'model' => 'Zblog_Blog',
		'name' => 'blog_feed',
		'method' => 'atom_feed');

$ctl[] = array('regex' => '#^/blog/tag/([0-9a-zA-Z_\-]+)$#',
		'priority' => 4,
		'base' => Pluf::f('zblog_base'),
		'model' => 'Zblog_Blog',
		'name' => 'blog_tag',
		'method' => 'view_by_tag');

$ctl[] = array('regex' => '#^/blog/tag/([0-9a-zA-Z_\-]+)/page/(\d+)/$#',
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
/*$ctl[] = array('regex' => '#^/blog/(\d{4})/$#',
		'priority' => 4,
		'base' => Pluf::f('zblog_base'),
		'model' => 'Zblog_Blog',
		'method' => 'view_year');*/

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

return $ctl;
