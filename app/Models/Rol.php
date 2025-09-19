<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'rols';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_rol';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre_rol',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_rol', 'id_rol', 'id_usuario');
    }
}
