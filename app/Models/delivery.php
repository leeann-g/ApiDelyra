<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'deliveries';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_entrega';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_pedido',
        'id_domiciliario',
        'direccion_envio',
        'estado',
        'fecha_entrega',
        'hora_estimada',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'estado' => 'boolean',
        'fecha_entrega' => 'datetime',
        'hora_estimada' => 'datetime:H:i:s',
    ];

    /**
     * Get the order that owns the delivery.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Get the delivery person that owns the delivery.
     */
    public function deliveryPerson()
    {
        return $this->belongsTo(DeliveryPerson::class, 'id_domiciliario', 'id_domiciliario');
    }
}
