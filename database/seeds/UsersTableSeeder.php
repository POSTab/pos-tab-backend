<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$role_user= Role::where('name','User')->first();
		
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email ='admin@gmail.com';
		$admin->mobile = '7276574438';
		$admin->username='admin';
        $admin->password = bcrypt('123456');
		$admin->active='1';
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}
