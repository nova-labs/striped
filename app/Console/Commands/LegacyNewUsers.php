<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Member;
use App\LegacyPeople;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LegacyNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:legacyNewUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate new legacy users to bridge database';

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
        $max_local_user_id = DB::table('users')->max('id');
        if (isset($max_local_user_id))
            $local_max = $max_local_user_id;
        else
            $local_max =0;

        $new_legacy_users = LegacyPeople::where('id', '>', $local_max)->get();

        $migration_user_count =0;


        foreach ($new_legacy_users as $legacy_user)
        {
            $email =  $legacy_user->emails()->first()['email_address'];

            $existing_user = Member::where('email', $email)->first();

            if (!$existing_user){
                $this->addNewUser($legacy_user, $email);
                $migration_user_count++;
            }
        }

        $legacy_user_count = LegacyPeople::count();

        echo 'Users: ' . $legacy_user_count . ' migrated: ' . $migration_user_count ."\n";
    }

    public function addNewUser($legacy_user, $email)
    {
        $user = new Member();

        $user->email = strtolower($email);


        $user->id = $legacy_user->id;
        $user->member_type = $legacy_user->member_type;
        $user->name = $legacy_user->name;
        $user->old_username = $legacy_user->username;
        $user->sponsor_id = $legacy_user->sponsor_id;
        $user->notes = $legacy_user->notes;
        $user->full_member_date = $legacy_user->full_member_date;
        $user->aspiration = $legacy_user->aspiration;
        $user->meetup_id = $legacy_user->meetup_id;
        $user->stripe_id = $legacy_user->stripe_customer_id;
        $user->phone = $legacy_user->phone;

        $user->password = 0;
        $user->old_password = $legacy_user->password;

        $time = explode('.', $legacy_user->created_timestamp);
        $user->created_at = $time[0];

        $card_info = explode(',', $legacy_user->stripe_payment_info);
        if (isset($card_info[1]))
            $user->card_last_four = $card_info[1];
        if (isset($card_info[0]))
            $user->card_brand = $card_info[0];

        $user->badge_number = $legacy_user->badge_number;
        $user->family_primary_member_id = $legacy_user->family_primary_member_id;

        $user->last_login = Carbon::createFromTimestamp($legacy_user->last_login_epoch);

        $user->save();
    }
}
