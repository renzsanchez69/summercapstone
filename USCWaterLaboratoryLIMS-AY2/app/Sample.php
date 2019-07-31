<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use Notifiable;
    
    protected $table = 'samples';
    protected $primaryKey = 'sampleId';

    protected $fillable = [
        'laboratoryCode', 'clientsCode', 'sampleType', 'sampleCollection', 'samplePreservation', 'purposeOfAnalysis', 'sampleSource', 
        'dueDate', 'managedBy', 'managedDate',
    ];

    protected $hidden = [
        'risNumber',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'risNumber', 'clientId');
    }

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'sample__tests', 'sampleCode', 'parameters')->withPivot('status', 'timeReceived', 'timeCompleted');
    }
}
