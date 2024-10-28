<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::take(12)->pluck('id');

        $timezones = ["CET", "CST", "GMT+1"];
        foreach ($users as $userId) {
            $row = User::find($userId);
            $row->name = 'Amended-'.fake()->name();
            $row->time_zone = $timezones[rand(0,count($timezones) -1)];
            $row->save();
        }
    }
}
