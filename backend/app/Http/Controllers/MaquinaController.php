<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', '1');
        return response()->json(Maquina::where('maquina_estado', $status)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->all();
        if (!isset($validated['maquina_fecha_registro'])) {
            $validated['maquina_fecha_registro'] = now();
        }

        if ($request->hasFile('maquina_imagen')) {
            $file = $request->file('maquina_imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $validated['maquina_imagen'] = $filename;
        }

        if ($request->hasFile('factura_compra')) {
            $file = $request->file('factura_compra');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $validated['factura_compra'] = $filename;
        }

        $maquina = Maquina::create($validated);
        return response()->json($maquina, 201);
    }

    public function show($id)
    {
        return response()->json(Maquina::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $maquina = Maquina::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('maquina_imagen')) {
            $file = $request->file('maquina_imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $data['maquina_imagen'] = $filename;
        }

        if ($request->hasFile('factura_compra')) {
            $file = $request->file('factura_compra');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/maquinas'), $filename);
            $data['factura_compra'] = $filename;
        }

        $maquina->update($data);
        return response()->json($maquina);
    }

    public function destroy($id)
    {
        $maquina = Maquina::findOrFail($id);
        $maquina->maquina_estado = '0';
        $maquina->save();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $maquina = Maquina::findOrFail($id);
        $maquina->maquina_estado = '1';
        $maquina->save();
        return response()->json($maquina);
    }

    public function downloadPdf($id)
    {
        $maquina = Maquina::findOrFail($id);
        $mantenimientos = \App\Models\MaquinaMantenimiento::where('maquina_id', $id)->orderBy('maq_mtto_fecha', 'desc')->get();

        $tabla_mantenimientos = "";
        $tt = 0;
        foreach ($mantenimientos as $value) {
            $tipo = $value->tipo_mantenimiento == '1' ? 'Preventivo' : 'Correctivo';
            $tabla_mantenimientos .= '<tr>
                <td style="padding: 10px; border: 1px solid #000;">' . e($tipo) . '</td>
                <td style="padding: 10px; border: 1px solid #000;">' . e($value->maq_mtto_fecha) . '</td>
                <td style="padding: 10px; border: 1px solid #000;">' . e($value->maq_mtto_reponsable) . '</td>
                <td style="padding: 10px; border: 1px solid #000;">S/ ' . number_format((float)$value->maq_mtto_costo, 2) . '</td>
                <td style="padding: 10px; border: 1px solid #000;">' . e($value->maq_mtto_observacion) . '</td>
            </tr>';
            $tt += (float)$value->maq_mtto_costo;
        }
        $tabla_mantenimientos .= '<tr>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td style="padding: 10px; font-weight: bold; border: 1px solid #000;">S/ ' . number_format($tt, 2) . '</td>
                <td style="border: 1px solid #000;"></td>
            </tr>';

        $logoDataUri = 'https://omcar.dbusinessaqp.com/assets/logo_2-DmLP2iC3.png';
        $imagen_maquina = '';
        if ($maquina->maquina_imagen) {
            $localPath = public_path('storage/maquinas/' . $maquina->maquina_imagen);
            if (file_exists($localPath)) {
                $imgUrl = url('storage/maquinas/' . $maquina->maquina_imagen);
            } else {
                $imgUrl = 'https://omcar.peruviandress.com/storage/maquinas/' . $maquina->maquina_imagen;
            }
            $imagen_maquina = '<img src="' . $imgUrl . '" style="width:294px;" />';
        }

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8" /><style>'
            . 'body{font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 0;}'
            . 'table{border-collapse: collapse; width: 100%;}'
            . 'td, th{padding: 10px;}'
            . '</style></head><body>'
            . '<div style="width: 100%; text-align: center;">'
            . '<img src="' . $logoDataUri . '" border="0" style="width: 150px; margin-left: auto; margin-right: auto;" />'
            . '</div>'
            . '<p style="text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;"> FICHA DE MAQUINA </p>'
            . '<table style="width: 100%; border: 1px solid #000; border-collapse: collapse;">'
            . '<tr>'
            . '<td style="width: 50%; vertical-align: top; border: 1px solid #000;">'
            . '<table style="width: 100%; border-collapse: collapse;">'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Codigo</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_tipo) . '-' . e($maquina->maquina_codigo) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Descripcion</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_descripcion) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Marca de la maquina</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_marca) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Modelo</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_modelo) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Nro de Serie</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_serie) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Marca de Motor</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_marca_motor) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Nro Serie de Motor</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_serie_motor) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Medidas para Espacio</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_exigencias) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Voltaje</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_voltaje) . '</td></tr>'
            . '<tr><td style="font-weight: bold; width: 50%; border: 1px solid #000;">Tipo Corriente</td><td style="width: 50%; border: 1px solid #000;">' . e($maquina->maquina_tipo_corriente) . '</td></tr>'
            . '</table>'
            . '</td>'
            . '<td style="width: 50%; vertical-align: top; border: 1px solid #000; text-align: center;">'
            . $imagen_maquina
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="text-align: center; margin-top: 25px; font-size: 20px; font-weight: bold;">Lista de Mantenimientos</p>'
            . '<table style="width: 100%; border: 1px solid #000; border-collapse: collapse;">'
            . '<tr>'
            . '<th style="width: 20%; border: 1px solid #000;">Mantenimiento Realizado</th>'
            . '<th style="width: 10%; border: 1px solid #000;">Fecha</th>'
            . '<th style="width: 20%; border: 1px solid #000;">Responsable</th>'
            . '<th style="width: 10%; border: 1px solid #000;">Costo</th>'
            . '<th style="width: 20%; border: 1px solid #000;">Observaciones</th>'
            . '</tr>'
            . $tabla_mantenimientos
            . '</table>'
            . '</body></html>';

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Datos-Mantenimiento.pdf"',
        ]);
    }
}
