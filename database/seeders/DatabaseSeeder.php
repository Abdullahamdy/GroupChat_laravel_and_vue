<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
     /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name'=>'Abdullah','email'=>'Abdullah@gmail.com','image'=>'Abdullah.jpg','email_verified_at'=>now(),'password'=>bcrypt('11111111'),'remember_token'=>Str::random(10)]);
        User::create(['name'=>'Hamdy','email'=>'Hamdy@gmail.com','image'=>'hamdy.jpg','email_verified_at'=>now(),'password'=>bcrypt('11111111'),'remember_token'=>Str::random(10)]);
        User::create(['name'=>'Hema','email'=>'hema@gmail.com','image'=>'ibrahem.jpg','email_verified_at'=>now(),'password'=>bcrypt('11111111'),'remember_token'=>Str::random(10)]);
        User::create(['name'=>'Abdo','email'=>'Abdo@gmail.com','image'=>'Abdo.jpg','email_verified_at'=>now(),'password'=>bcrypt('11111111'),'remember_token'=>Str::random(10),]);



        Conversation::create(['name'=>'Family Group','uuid'=>Str::uuid(),'user_id'=>1,]);
        Conversation::create(['name'=>'Work Group','uuid'=>Str::uuid(),'user_id'=>rand(1,4),]);
        Conversation::create(['name'=>'Friends Group','uuid'=>Str::uuid(),'user_id'=>rand(1,4),]);
        Conversation::create(['name'=>'Gays Group','uuid'=>Str::uuid(),'user_id'=>rand(1,4),]);
        Conversation::create(['name'=>'Art Group','uuid'=>Str::uuid(),'user_id'=>rand(1,4), ]);


        DB::table('conversation_user')->insert(['conversation_id'=>1,'user_id'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>1,'user_id'=>2,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>1,'user_id'=>3,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>1,'user_id'=>4,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>2,'user_id'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>2,'user_id'=>2,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>3,'user_id'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>3,'user_id'=>3,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>3,'user_id'=>4,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>4,'user_id'=>2,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>4,'user_id'=>3,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>4,'user_id'=>4,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>5,'user_id'=>3,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('conversation_user')->insert(['conversation_id'=>5,'user_id'=>4,'created_at'=>now(),'updated_at'=>now()]);

    $this->call(MessageTableSeeder::class);
    }
}
