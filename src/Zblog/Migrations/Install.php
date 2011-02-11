<?php
function Zblog_Migrations_Install_setup($params='') {
	$tag = new Zblog_Tag();
	$post = new Zblog_Post();
	$user = new Pluf_User();
	$user->login = 'admin';
	$user->administrator = 1;
	$user->setPassword(Pluf::f('admin_password'));

	$db = Pluf::db();
	$schema = new Pluf_DB_Schema($db);

	$schema->model = $tag;
	$schema->createTables();
	$schema->model = $post;
	$schema->createTables();
	$user->create();
}

function Zblog_Migrations_Install_teardown($params='') {
	$post = new Zblog_Post();
	$tag = new Zblog_Tag();
	$db = Pluf::db();
	$schema = Pluf::factory('Pluf_DB_Schema', $db);
	$schema->model = $post;
	$schema->dropTables();
	$schema->model = $tag;
	$schema->dropTables();
}
