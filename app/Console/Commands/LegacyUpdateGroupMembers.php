<?php

namespace App\Console\Commands;

use App\Group;
use App\MemberGroup;
use App\LegacyGroups;
use App\LegacyPersonGroup;
use Illuminate\Console\Command;

class LegacyUpdateGroupMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:legacyUpdateGroupMembers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update groups membership from legacy database to bridge database';

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
        $groups = Group::all('id');
        $membership_added = 0;
        $membership_unchanged = 0;
        
        foreach ($groups as $group)
        {
            $group_id = $group->id;
            $legacy_group_id_list = LegacyPersonGroup::where('group_id','=',$group_id)->get();

            foreach ($legacy_group_id_list as $entry){

                $new_entry = MemberGroup::where('group_id','=',$group_id)->where('member_id','=',$entry->person_id)->first();

                if($new_entry){
                    $membership_unchanged++;
                }
                else{
                    $new_membership = new MemberGroup();
                    $new_membership->member_id = $entry->person_id;
                    $new_membership->group_id = $entry->group_id;

                    $time = explode('.', $entry->date_created);
                    $new_membership->created_at = $time[0];

                    $new_membership->save();
                    $membership_added++;
                }
            }
        }

        // to do check for membership equality and check those groups that have too many members

        $total_source = LegacyPersonGroup::count();
        $total_dest = MemberGroup::count();
        echo 'Source: ' . $total_source . ' dest: ' . $total_dest ."\n";

        echo 'Membership updated: ' . $membership_added . ' not: ' . $membership_unchanged ."\n";

        return $membership_unchanged;
    }

}
