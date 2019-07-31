<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryList extends Model
{
    protected $table = 'inventory_list';
	protected $primaryKey = 'listid';

	protected $fillable = [
        'inventoryId', 'itemId', 'qty'
    ];

    public function inventories()
    {
        return $this->belongsTo(Inventory::class, 'inventoryId', 'inventoryId');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'itemId', 'itemId');
    }
}
