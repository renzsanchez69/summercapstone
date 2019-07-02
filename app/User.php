<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Sellers;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes, CascadeSoftDeletes;
    const ADMIN_TYPE = true;

    protected $cascadeDeletes = ['orders', 'cart'];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'date_of_birth', 'gender', 'phone_number', 'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the cart associated with the user.
     */
    public function cart()
    {
        return $this->hasOne('App\Cart');
    }

    /**
     * Get the orders associated with the user.
     */
    public function orders()
    {
        return $this->hasMany('App\Orders');
    }

    public function isAdmin()
    {
        return $this->is_admin === self::ADMIN_TYPE;
    }

    public function isSeller()
    {
        return Sellers::where('user_id', $this->id)->exists();
    }

    public function seller()
    {
        return $this->hasOne('App\Sellers');
    }

    public function sellerId()
    {
        return DB::select('SELECT * FROM users join sellers on sellers.user_id = users.id WHERE users.Id = ' . $this->id);
    }
}
