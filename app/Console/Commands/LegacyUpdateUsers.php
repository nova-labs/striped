<?php

namespace App\Console\Commands;

use App\LegacyPeople;
use App\Member;
use Illuminate\Console\Command;

use App\User;
use Carbon\Carbon;

class LegacyUpdateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:legacyUpdateUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user details from legacy database to bridge database';

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
        $users = User::all();
        $users_updated = 0;
        $users_unchanged = 0;
        $counter = 0;

        foreach($users as $user){
            if($this->checkForChanges($user->id)){
                $users_updated++;
            }
            else {
                $users_unchanged++;
            }
            $counter++;
            if (($counter % 100) == 0)
                echo $counter . "\n";
        }
        echo 'Users updated: ' . $users_updated . ' not: ' . $users_unchanged ."\n";

        return $users_updated;
    }

    public function checkForChanges($existing_user_id)
    {
        $existing_user = Member::where('id','=', $existing_user_id)->first();
        $legacy_user = LegacyPeople::where('id','=',$existing_user->id)->first();

        if($legacy_user){

            $email =  $legacy_user->emails()->first()['email_address'];

            if ($existing_user->email != $email)
                $existing_user->member_type = $email;

            if ($existing_user->member_type != $legacy_user->member_type)
                $existing_user->member_type = $legacy_user->member_type;

            if ($existing_user->name != $legacy_user->name)
                $existing_user->name = $legacy_user->name;

            if ($existing_user->sponsor_id != $legacy_user->sponsor_id)
                $existing_user->sponsor_id = $legacy_user->sponsor_id;

            if ($existing_user->notes != $legacy_user->notes)
                $existing_user->notes = $legacy_user->notes;

            if ($existing_user->full_member_date != $legacy_user->full_member_date)
                $existing_user->full_member_date = $legacy_user->full_member_date;

            if ($existing_user->phone != $legacy_user->phone)
                $existing_user->phone = $legacy_user->phone;

            if ($existing_user->meetup_id != $legacy_user->meetup_id)
                $existing_user->meetup_id = $legacy_user->meetup_id;

            if ($existing_user->stripe_id != $legacy_user->stripe_customer_id)
                $existing_user->stripe_id = $legacy_user->stripe_customer_id;

            if ($existing_user->badge_number != $legacy_user->badge_number)
                $existing_user->badge_number = $legacy_user->badge_number;

            if ($existing_user->family_primary_member_id != $legacy_user->family_primary_member_id)
                $existing_user->family_primary_member_id = $legacy_user->family_primary_member_id;

            if ($existing_user->last_login != Carbon::createFromTimestamp($legacy_user->last_login_epoch))
                $existing_user->last_login = Carbon::createFromTimestamp($legacy_user->last_login_epoch);

            $card_info = explode(',', $legacy_user->stripe_payment_info);
            if (isset($card_info[1]))
                $existing_user->card_last_four = $card_info[1];
            if (isset($card_info[0]))
                $existing_user->card_brand = $card_info[0];

            if($existing_user->isDirty()){
                return $existing_user->save();
            }
            else
                return false;
        }
        else{
            // todo: remove deleted user
            return false;
        }
    }
}
