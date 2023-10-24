<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for($i = 1 ; $i<=5 ; $i++){
            $conversations = Conversation::whereId($i)->with('users')->first();
            for($s = 1 ; $s<=5 ; $s++){
                Message::create(['conversation_id'=>$conversations->id,'user_id'=>$conversations->users->random()->id,'body'=>$faker->sentence()]);
                Conversation::whereId($i)->update(['last_message_at'=>now()]);
            }
        }
    }
    
}
