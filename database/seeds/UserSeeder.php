<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * insert admin and guest user to user table
     * @return void
     */
    public function run()
    {
        $users=[
            ['id'=>0,
            'name'=>'guest',
            'email'=>'guest@ishare.com',
            'mobile'=>'0000000000',
            'password'=>Hash::make('imguest'),
            'is_email_verified'=>1,
            'is_admin'=>0,
            'is_mobile_verified'=>1,
            'is_active'=>1
        ],
            ['id'=>1,
            'name'=>'admin',
            'email'=>'admin@ishare.com',
            'mobile'=>'7205518366',
            'password'=>Hash::make('imadmin'),
            'is_email_verified'=>1,
            'is_admin'=>1,
            'is_mobile_verified'=>1,
            'is_active'=>1
            ]
        ];
        DB::table('users')->insert($users);
    }
}
