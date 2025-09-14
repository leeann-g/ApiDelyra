<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'customers';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_cliente';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_usuario',
        'direccion_envio',
    ];

    /**
     * Get the user that owns the customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the orders for the customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_cliente', 'id_cliente');
    }
}
