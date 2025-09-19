<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'orders';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_pedido';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_cliente',
        'fecha_pedido',
        'estado',
        'total',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha_pedido' => 'date',
        'total' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Get the delivery for the order.
     */
    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'id_pedido', 'id_pedido');
    }
}
