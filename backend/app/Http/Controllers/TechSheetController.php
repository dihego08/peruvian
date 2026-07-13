<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class TechSheetController extends Controller
{
    public function getFicha($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        
        $ficha = DB::table('ficha_tecnica')->where('code_producto', $code)->first();
        $complementos = DB::table('complementos')->where('code_producto', $code)->get();
        $identificacion = DB::table('identificacion')->where('code_producto', $code)->get();
        $modificaciones = DB::table('modificaciones')->where('code_producto', $code)->get();
        $observaciones = DB::table('observaciones')->where('code_producto', $code)->get();
        $archivo = DB::table('fecha_tecnica_archivo')->where('id_producto', $code)->first();

        return response()->json([
            'product' => $product,
            'ficha' => $ficha,
            'complementos' => $complementos,
            'identificacion' => $identificacion,
            'modificaciones' => $modificaciones,
            'observaciones' => $observaciones,
            'archivo' => $archivo
        ]);
    }

    public function updateFicha(Request $request, $code)
    {
        $data = $request->only(['elaborado_por', 'revisado_por', 'aprobado_por', 'u_modificacion']);
        
        $exists = DB::table('ficha_tecnica')->where('code_producto', $code)->exists();

        if ($exists) {
            DB::table('ficha_tecnica')->where('code_producto', $code)->update($data);
        } else {
            $data['code_producto'] = $code;
            DB::table('ficha_tecnica')->insert($data);
        }

        return response()->json(['message' => 'Ficha actualizada']);
    }

    public function getManual($code)
    {
        $maquinas = DB::table('maquinas')->where('code_producto', $code)->get();
        $etapas = DB::table('etapas')->get();
        
        $records = [];
        foreach ($etapas as $etapa) {
            $pasos = DB::table('pasos')
                ->where('code_producto', $code)
                ->where('id_etapa', $etapa->id)
                ->orderBy('orden')
                ->get();
            
            $records[] = [
                'id' => $etapa->id,
                'etapa' => $etapa->etapa,
                'pasos' => $pasos
            ];
        }

        return response()->json([
            'maquinas' => $maquinas,
            'records' => $records
        ]);
    }

    public function saveInstruccion(Request $request)
    {
        $data = $request->validate([
            'id_etapa' => 'required|integer',
            'paso' => 'required|string',
            'instruccion' => 'required|string',
            'code_producto' => 'required|string',
            'orden' => 'required|integer'
        ]);

        $data['instruccion'] = nl2br($data['instruccion']);
        DB::table('pasos')->insert($data);

        return response()->json(['message' => 'Instrucción guardada']);
    }

    public function updateInstruccion(Request $request, $id)
    {
        $data = $request->validate([
            'paso' => 'string',
            'instruccion' => 'string',
            'orden' => 'integer'
        ]);

        if (isset($data['instruccion'])) {
            $data['instruccion'] = nl2br($data['instruccion']);
        }

        DB::table('pasos')->where('id', $id)->update($data);
        return response()->json(['message' => 'Instrucción actualizada']);
    }

    public function deleteInstruccion($id)
    {
        DB::table('pasos')->where('id', $id)->delete();
        return response()->json(['message' => 'Instrucción eliminada']);
    }

    public function getMedidas($code)
    {
        $medidas = DB::table('medidas')->where('code_producto', $code)->get();
        return response()->json($medidas);
    }

    public function saveMedida(Request $request)
    {
        $data = $request->validate([
            'code_producto' => 'required|string',
            'descripcion' => 'required|string',
            't_2' => 'nullable|string',
            't_4' => 'nullable|string',
            't_6' => 'nullable|string',
            't_8' => 'nullable|string',
            't_10' => 'nullable|string',
            't_12' => 'nullable|string',
            't_14' => 'nullable|string',
            't_16' => 'nullable|string',
            's' => 'nullable|string',
            'm' => 'nullable|string',
            'l' => 'nullable|string',
            'xl' => 'nullable|string',
            'xxl' => 'nullable|string',
            'xxxl' => 'nullable|string',
        ]);

        DB::table('medidas')->insert($data);
        return response()->json(['message' => 'Medida guardada']);
    }

    public function updateMedida(Request $request, $id)
    {
        $data = $request->all();
        unset($data['id']);
        DB::table('medidas')->where('id', $id)->update($data);
        return response()->json(['message' => 'Medida actualizada']);
    }

    public function deleteMedida($id)
    {
        DB::table('medidas')->where('id', $id)->delete();
        return response()->json(['message' => 'Medida eliminada']);
    }

    public function saveComplemento(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'complemento' => 'required|string',
            'code_producto' => 'required|string'
        ]);

        DB::table('complementos')->insert($data);
        return response()->json(['message' => 'Complemento guardado']);
    }

    public function deleteComplemento($id)
    {
        DB::table('complementos')->where('id', $id)->delete();
        return response()->json(['message' => 'Complemento eliminado']);
    }

    public function saveIdentificacion(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'complemento' => 'required|string',
            'code_producto' => 'required|string'
        ]);

        DB::table('identificacion')->insert($data);
        return response()->json(['message' => 'Identificación guardada']);
    }

    public function deleteIdentificacion($id)
    {
        DB::table('identificacion')->where('id', $id)->delete();
        return response()->json(['message' => 'Identificación eliminada']);
    }

    public function saveModificacion(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'aprobado_por' => 'required|string',
            'ultima_modificacion' => 'required|date',
            'code_producto' => 'required|string'
        ]);

        DB::table('modificaciones')->insert($data);
        return response()->json(['message' => 'Modificación guardada']);
    }

    public function deleteModificacion($id)
    {
        DB::table('modificaciones')->where('id', $id)->delete();
        return response()->json(['message' => 'Modificación eliminada']);
    }

    public function saveObservacion(Request $request)
    {
        $data = $request->validate([
            'observacion' => 'required|string',
            'detalle' => 'required|string',
            'code_producto' => 'required|string'
        ]);

        DB::table('observaciones')->insert($data);
        return response()->json(['message' => 'Observación guardada']);
    }

    public function deleteObservacion($id)
    {
        DB::table('observaciones')->where('id', $id)->delete();
        return response()->json(['message' => 'Observación eliminada']);
    }

    public function saveMaquina(Request $request)
    {
        $data = $request->validate([
            'maquina' => 'required|string',
            'code_producto' => 'required|string'
        ]);

        DB::table('maquinas')->insert($data);
        return response()->json(['message' => 'Máquina guardada']);
    }

    public function deleteMaquina($id)
    {
        DB::table('maquinas')->where('id', $id)->delete();
        return response()->json(['message' => 'Máquina eliminada']);
    }
}
