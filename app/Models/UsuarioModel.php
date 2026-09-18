<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    
    protected $allowedFields    = [
        'nombre', 'apellido', 'dni', 'email', 'password', 'rol', 
        'estado', 'telefono', 'fecha_nacimiento', 'especialidad', 'cargo'
    ];
}