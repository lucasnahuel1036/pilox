<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Estos son los campos que permitimos que se guarden o modifiquen
    protected $allowedFields    = [
        'nombre', 'apellido', 'email', 'password', 'rol', 
        'estado', 'telefono', 'fecha_nacimiento', 'especialidad', 'cargo'
    ];
}