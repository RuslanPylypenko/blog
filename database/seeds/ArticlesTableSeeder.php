<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('Ru_RU');

        $filepath = public_path('storage/images');

        if(!File::exists($filepath)){
            File::makeDirectory($filepath);
        }

        for($i = 0; $i < 100; $i++){
            Db::table('articles')->insert([
                'title' => rtrim($faker->text(rand(20, 50)), '.'),
                'text' => $faker->text(rand(300, 600)),
                'views' => rand(0, 600),
                'likes' => rand(0, 300),
                'image' => $faker->image($filepath,400,300, false, false) ,
                'created_at' => $faker->date('Y-m-d H:i:s'),
                'updated_at' => $faker->date('Y-m-d H:i:s'),
            ]);
        }

    }
}
