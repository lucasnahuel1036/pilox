<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifaModel extends Model
{
    protected $table            = 'tarifas';
    protected $primaryKey       = 'clases';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['clases', 'precio'];

    // Tarifas ordenadas por cantidad de clases
    public function getTarifas()
    {
        return $this->orderBy('clases', 'ASC')->findAll();
    }

    // Precio actual de un tipo de abono (null si no existe)
    public function getPrecio($clases)
    {
        $tarifa = $this->find($clases);
        return $tarifa ? (float) $tarifa['precio'] : null;
    }
}