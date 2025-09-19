<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo',
        'contrasena_hash',
        'telefono',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'contrasena_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'contrasena_hash' => 'hashed',
        ];
    }

    /**
     * Get the customer associated with the user.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the delivery person associated with the user.
     */
    public function deliveryPerson()
    {
        return $this->hasOne(DeliveryPerson::class, 'id_usuario', 'id_usuario');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_rol', 'id_usuario', 'id_rol');
    }
}
