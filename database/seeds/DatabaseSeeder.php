<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UsersTableSeeder::class);

        // 填充微博数据
        $this->call(StatusesTableSeeder::class);

        // 填充粉丝列表数据
        $this->call(FollowersTableSeeder::class);

        Model::reguard();
    }
}