<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = ['1', '2', '3'];
        $faker    = app(Faker\Generator::class);

        $statuses = factory(Status::class)->times(100)->make()->each(function ($statuses) use ($faker, $user_ids) {
                $statuses->user_id = $faker->randomElement($user_ids);
        });

        Status::insert($statuses->toArray());
    }
}