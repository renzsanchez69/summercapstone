<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $table = 'suppliers';
    protected $primaryKey = 'supplierId';

    protected $fillable = [
        'companyName', 'emailAddress', 'contactNumber', 'managedBy', 'managedDate',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'supplier', 'supplierId');
    }
}
