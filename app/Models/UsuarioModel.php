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

    // Usuarios de un rol, filtrados por nombre, apellido, DNI o email.
    // Devuelve el modelo, así se puede seguir encadenando (paginate, findAll, etc.)
    public function buscarPorRol($rol, $busqueda = '')
    {
        $this->where('rol', $rol);

        $busqueda = trim((string) $busqueda);

        if ($busqueda !== '') {
            // Cada palabra tiene que aparecer en alguno de los campos
            foreach (preg_split('/\s+/', $busqueda) as $palabra) {
                $this->groupStart()
                         ->like('nombre', $palabra)
                         ->orLike('apellido', $palabra)
                         ->orLike('dni', $palabra)
                         ->orLike('email', $palabra)
                     ->groupEnd();
            }
        }

        return $this->orderBy('apellido', 'ASC')->orderBy('nombre', 'ASC');
    }
}