<?php

namespace App\Console\Commands;

use App\Member;
use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:makeMemberAdmin {email} {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy user from members to User Table and give admin permission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inputEmail = $this->argument('email');
        $remove = $this->option('remove');

        if ($remove)
        {

        }
        else
        {
            $members = Member::where('email', $inputEmail)->get();

            if ($members->count() == 1)
            {
                $member = $members->first();

                $user = New User();
                $user->email = $member->email;
                $user->name = $member->name;
                $user->password = $member->old_password;
                $user->save();

                $user->assignRole('super-admin');

            }
            else
            {
                $this->info('Sorry, '. $members->count(). ' users with that email');
            }
        }

    }
}
