<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryPerson extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'delivery_people';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_domiciliario';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_usuario',
        'estado_dis',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'estado_dis' => 'boolean',
    ];

    /**
     * Get the user that owns the delivery person.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the vehicles for the delivery person.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'id_domiciliario', 'id_domiciliario');
    }

    /**
     * Get the deliveries for the delivery person.
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'id_domiciliario', 'id_domiciliario');
    }
}
