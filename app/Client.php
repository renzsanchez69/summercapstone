<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Notifiable;

    protected $table = 'clients';
    protected $primaryKey = 'clientId';

    protected $fillable = [
        'nameOfPerson', 'nameOfEntity', 'address', 'contactNumber', 'telephone', 'faxNumber', 'emailAddress', 'discount', 'deposit', 
        'reclaimSample', 'testResult', 'remarks', 'dateSubmitted', 'managedBy', 'managedDate',
    ];

    public function samples()
    {
        return $this->hasMany(Sample::class, 'risNumber', 'clientId');
    }

    public function parameters()
    {
        return $this->hasManyThroughMany(Parameter::class, Sample_Tests::class, 'sampleCode', 'parameters', 'clientId', 'testId');
    }

    public function routeNotificationForNexmo($notification)
    {
        return $this->contactNumber;
    }
}
