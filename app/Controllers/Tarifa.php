<?php

namespace App\Controllers;

use App\Models\TarifaModel;

class Tarifa extends BaseController
{
    public function index()
    {
        $data['tarifas'] = (new TarifaModel())->getTarifas();

        return view('templates/header')
             . view('admin/tarifas', $data)
             . view('templates/footer');
    }

    public function actualizar()
    {
        $model   = new TarifaModel();
        $precios = $this->request->getPost('precio');

        if (!is_array($precios)) {
            session()->setFlashdata('error', 'No se recibieron los precios.');
            return redirect()->to('/admin/tarifas');
        }

        // Primero validamos los tres precios, recién después guardamos
        foreach ([4, 8, 12] as $clases) {
            $valor = $precios[$clases] ?? null;

            if (!is_numeric($valor) || $valor <= 0 || $valor > 10000000) {
                session()->setFlashdata('error', 'Ingresá un precio válido para el abono de ' . $clases . ' clases.');
                return redirect()->to('/admin/tarifas');
            }
        }

        foreach ([4, 8, 12] as $clases) {
            $model->update($clases, ['precio' => round((float) $precios[$clases], 2)]);
        }

        session()->setFlashdata('success', 'Tarifas actualizadas. Rigen para los abonos que se asignen desde ahora.');
        return redirect()->to('/admin/tarifas');
    }
}