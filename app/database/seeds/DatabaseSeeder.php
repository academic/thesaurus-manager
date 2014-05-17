<?php

class DatabaseSeeder extends Seeder {

	/**
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UsersTableSeeder');
	}

}
