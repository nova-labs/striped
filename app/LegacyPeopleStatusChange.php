<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegacyPeopleStatusChange extends Model
{
    protected $table = 'people_status_changes';

    public function people()
    {
        return $this->belongsTo('App\LegacyPeople', 'people_id', 'id', '=');
    }

    protected $connection = 'legacy';
}
