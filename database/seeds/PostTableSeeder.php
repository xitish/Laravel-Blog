<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post = new \App\Post([
            'title' => 'Learning Laravel',
            'content' => 'This is a predefined dummu data in for testing',
            'user_id' => 1
        ]);
        $post->save();

        $post = new \App\Post([
            'title' => 'Advanced Laravel',
            'content' => 'This is a advanced dummy data in for testing',
            'user_id' => 1
        ]);
        $post->save();
    }
}
