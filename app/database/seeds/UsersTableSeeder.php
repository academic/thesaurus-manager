<?php
class UsersTableSeeder extends Seeder {

    public function run() {
	$admin = Sentry::register(array(
    			'email'    => 'admin@localhost.com',
    			'password' => 'admin',
		));  
	$editor = Sentry::register(array(
                        'email'    => 'editor@localhost.com',
                        'password' => 'editor',
                ));
	
	$adminGroup = Sentry::createGroup(array(
    	'name'        => 'Managers',
    	'permissions' => array(
        	'admin' => 1,
        	'editor' => 1,
    	),
	));
	
	$editorGroup = Sentry::createGroup(array(
        'name'        => 'Editors',
        'permissions' => array(
                'admin' => 0,
                'editor' => 1,
        ),
        ));

	$admin->addGroup($adminGroup);
	$editor->addGroup($editorGroup);

    }
}
