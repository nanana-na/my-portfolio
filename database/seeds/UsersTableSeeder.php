<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Post;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => 'master',
            'number' => '20000000',
            'password' => Hash::make('password')
        ]);
        Post::create([
            'user_id'  => '1',
            'group'  => '佐賀大学バトミントン部',
            'place'  => '佐賀大学体育館',
            'date'  => '月、水、土、日',
            'icon'  => '',
            'time'   => '月、水→18:00~20:00
            土、日→17:00~19:00',
            'body'   => '	初心者の方も経験者の方も大歓迎です！
            大学でもバドミントン頑張りたい人や、これからバドミントンを始めたいって人はぜひ見学しに来てください！
            見学の際はシューズを持って来てもらえれば打てます！'
        ]);
        User::create([
            'name' => '佐賀大学バトミントン部',
            'number' => '0001',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'CUBE',
            'number' => '0002',
            'password' => Hash::make('password')
        ]);
        User::create([
            'name' => 'Geese',
            'number' => '0003',
            'password' => Hash::make('password')        ]);
        User::create([
            'name' => 'BDD',
            'number' => '0004',
            'password' => Hash::make('password')
        ]);
        User::create([
            'name' => 'CLOVER',
            'number' => '0005',
            'password' => Hash::make('password')        ]);
        User::create([
            'name' => 'Score!!',
            'number' => '0006',
            'password' => Hash::make('password')        ]);
    }
}
