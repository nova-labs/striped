<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegacyMachines extends Model
{
    protected $table = 'machines';

    public function people()
    {
        return $this->belongsToMany('App\LegacyPeople', 'person_machines', 'machine_id', 'person_id');
    }

    protected $connection = 'legacy';
}
