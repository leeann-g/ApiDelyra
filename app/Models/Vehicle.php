<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'vehicles';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_vehiculo';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_domiciliario',
        'placa',
        'tipo_vehiculo',
        'run_vigente',
        'seguro_vigente',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'run_vigente' => 'date',
        'seguro_vigente' => 'date',
    ];

    /**
     * Get the delivery person that owns the vehicle.
     */
    public function deliveryPerson()
    {
        return $this->belongsTo(DeliveryPerson::class, 'id_domiciliario', 'id_domiciliario');
    }
}
