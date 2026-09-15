<?php

namespace App\Models;

use CodeIgniter\Model;

class ActividadModel extends Model
{
    protected $table            = 'actividades';
    protected $primaryKey       = 'id_actividad';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nombre', 'descripcion'];
}