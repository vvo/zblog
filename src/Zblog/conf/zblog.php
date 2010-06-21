<?php
$cfg = array();

// when debug is off, there's a cache of 30 minutes on each pages
$cfg['debug'] = true;
//$cfg['debug'] = false;

// language settings
$cfg['time_zone'] = 'Europe/Paris';
$cfg['languages'] = array('fr');
setlocale(LC_ALL, "fr_FR.UTF-8");

// i removed index.php with .htaccess, if you do not have mod_rewrite, change this to index.php
$cfg['zblog_base'] = '';

$cfg['mail_to'] = 'vincent@zeroload.net';

$cfg['zblog_statics'] = '/static';
$cfg['url_media'] = '/static';

$cfg['admin_password'] = 'joerginp';

$cfg['tmp_folder'] = '/tmp';


$cfg['middleware_classes'] = array('Pluf_Middleware_Debug');

// database configuration
$cfg['db_login'] = 'vincent';
$cfg['db_password'] = 'joerginp';
$cfg['db_server'] = 'localhost';
$cfg['db_database'] = 'zblog';
$cfg['db_table_prefix'] = '';
$cfg['db_version'] = '';
$cfg['db_engine'] = 'MySQL';


// i use disqus for the comments
$cfg['disqus_prefix'] = 'ZL-COMMENTS-PROD-';


// when running on MAC, you need this ..
//$cfg['pear_path'] = '/Applications/MAMP/bin/php5/lib/php';

// admin specified because we want to use it but this is not mandatory to have it here
$cfg['installed_apps'] = array('Pluf', 'Zblog', 'Admin');
$cfg['zblog_urls'] = dirname(__FILE__).'/urls.php';
$cfg['template_folders'] = array(dirname(__FILE__).'/../templates');
$cfg['mimetype'] = 'text/html';

// template and tags modifiers
$cfg['template_modifiers'] = array(
      'markdown' => 'Pluf_Text_MarkDown_parse'
);

$cfg['template_tags'] = array(
		'jsmin' => 'Zblog_Template_Jsmin',
		'url' => 'Pluf_Template_Tag_Url',
		'cfg' => 'Pluf_Template_Tag_Cfg',
		'mediaurl' => 'Pluf_Template_Tag_MediaUrl',
		'tags' => 'Zblog_Template_Tags',
		'cssbooster' => 'Zblog_Template_Booster_Css',
		'jsbooster' => 'Zblog_Template_Booster_Js',
		'comments_permalink' => 'Zblog_Template_DisqusComments',
//		'markdown'			=> 'Zblog_Template_Markdown'
);

// what cache to use ?
$cfg['cache_engine'] = 'Pluf_Cache_File';
$cfg['cache_timeout'] = 7200;

$cfg['cache_file_folder'] = realpath(dirname(__FILE__).'/../../../cache');

return $cfg;
