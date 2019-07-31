<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    //
    use Notifiable;

    protected $table = 'employees';
    protected $primaryKey = 'employeeId';

    protected $fillable = [
        'username', 'password', 'employeeName', 'position', 'idNumber', 'licenseNumber', 'userType', 'managedBy', 'managedDate',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'inventories', 'usedBy', 'itemUsed')->withPivot('managedBy', 'updated_at');
    }
}
