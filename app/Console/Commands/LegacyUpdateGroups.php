<?php

namespace App\Console\Commands;

use App\Group;
use App\LegacyGroups;
use Illuminate\Console\Command;

class LegacyUpdateGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novalabs:legacyUpdateGroups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update groups from legacy database to bridge database';

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
        $group_ids = Group::all('id');
        $group_updated = 0;
        $group_unchanged = 0;
        
        foreach ($group_ids as $group_id)
        {
            if($this->checkForChanges($group_id->id))
                $group_updated++;
            else
                $group_unchanged++;
        }

        echo 'Groups updated: ' . $group_updated . ' not: ' . $group_unchanged ."\n";

        return $group_updated;
    }

    public function checkForChanges($existing_group_id)
    {
        $existing_group = Group::where('id', '=', $existing_group_id)->first();
        if($existing_group){
            $legacy_group = LegacyGroups::where('id', '=', $existing_group->id)->first();

            if ($legacy_group) {

                if ($existing_group->name != $legacy_group->name)
                    $existing_group->name = $legacy_group->name;

                if ($existing_group->description != $legacy_group->description)
                    $existing_group->description = $legacy_group->description;

                if ($existing_group->category != $legacy_group->category)
                    $existing_group->category = $legacy_group->category;

                if ($existing_group->isDirty()) {
                    return $existing_group->save();
                } else
                    return false;
            } else {
                return false;
            }
        }
        else{
            // todo: remove group and sign offs
            echo "Group no longer exists: " . $existing_group_id . "\n";
        }

    }
}
