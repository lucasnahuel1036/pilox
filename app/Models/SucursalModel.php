<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table            = 'sucursales';
    protected $primaryKey       = 'id_sucursal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Los campos que el administrador puede llenar
    protected $allowedFields    = ['nombre', 'direccion', 'telefono'];
}