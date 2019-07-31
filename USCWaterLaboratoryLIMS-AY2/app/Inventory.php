<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $table = 'inventories';
    protected $primaryKey = 'inventoryId';

    protected $fillable = [
        'usedBy',
    ];

    public function items()
    {
        return $this->belongsTo(Item::class, 'itemUsed', 'itemId');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'usedBy', 'employeeId');
    }
}
