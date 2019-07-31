<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;

class Item extends Model
{
    //
    protected $table = 'items';
    protected $primaryKey = 'itemId';

    protected $fillable = [
        'itemName', 'containerType', 'quantity', 'supplier'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier', 'supplierId');
    }

    public function user()
    {
        return $this->belongsToMany(Employee::class, 'inventories', 'itemUsed', 'usedBy')->withPivot('managedBy', 'updated_at');
    }
}
