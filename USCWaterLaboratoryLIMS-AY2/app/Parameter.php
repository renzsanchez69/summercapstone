<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    //
    protected $table = 'parameters';
    protected $primaryKey = 'parameterId';

    protected $fillable = [
        'analysis', 'method', 'price', 'station', 'managedBy', 'managedDate',
    ];

    public function samples()
    {
        return $this->belongsToMany(Sample::class, 'sample__tests', 'parameters', 'sampleCode')->withPivot('status', 'timeReceived', 'timeCompleted');
    }

    public function stations()
    {
        return $this->belongsTo(Station::class, 'station', 'stationId');
    }
}
