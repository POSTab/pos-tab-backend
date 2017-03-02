<?php

use Illuminate\Database\Seeder;
use App\Role;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name='User';
        $role_user->description = 'A normal user';
        $role_user->save();
      
        $role_auther = new Role();
        $role_auther->name='Manager';
        $role_auther->description = 'a manager';
        $role_auther->save();
        
        $role_admin = new Role();
        $role_admin->name='Admin';
        $role_admin->description = 'A Admin';
        $role_admin->save();
    }
}
