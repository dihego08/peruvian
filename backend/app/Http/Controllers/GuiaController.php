<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GuiaRemisionService;

class GuiaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('guia_cabecera as g')
            ->leftJoin('person as p', 'p.no', '=', 'g.ruc_destinatario')
            ->select('g.*', 'p.name')
            ->orderBy('g.id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('g.num_guia', 'like', "%$search%")
                  ->orWhere('g.ruc_destinatario', 'like', "%$search%")
                  ->orWhere('p.name', 'like', "%$search%")
                  ->orWhere('g.ruc_transportista', 'like', "%$search%");
            });
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('g.fecha_emision', [$request->desde, $request->hasta]);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $cabecera = DB::table('guia_cabecera as g')
            ->leftJoin('person as p', 'p.no', '=', 'g.ruc_destinatario')
            ->select('g.*', 'p.name')
            ->where('g.id', $id)
            ->first();

        $detalle = DB::table('guia_detalle as vd')
            ->leftJoin('product as p', 'p.id', '=', 'vd.id_producto')
            ->select('vd.*', 'p.name as descripcion_producto')
            ->where('vd.id_guia', $id)
            ->get();

        return response()->json([
            'cabecera' => $cabecera,
            'detalle'  => $detalle,
        ]);
    }

    /**
     * Get the next guide number (T001-XXXX) based on the aux table
     */
    public function nextNumGuia()
    {
        try {
            $aux = DB::table('aux')->where('i', 12)->first();
            $next = $aux ? ($aux->id + 1) : 1;
            return response()->json(['num_guia' => 'T001-' . $next]);
        } catch (\Exception $e) {
            $count = DB::table('guia_cabecera')->count();
            return response()->json(['num_guia' => 'T001-' . ($count + 1)]);
        }
    }

    public function getDepartamentos()
    {
        return response()->json(DB::table('departamento')->get());
    }

    public function getProvincias(Request $request)
    {
        return response()->json(
            DB::table('provincia')->where('departamento', $request->departamento)->get()
        );
    }

    public function getDistritos(Request $request)
    {
        return response()->json(
            DB::table('distrito')->where('provincia', $request->provincia)->get()
        );
    }

    /**
     * Search products for adding to guide
     */
    public function searchProducts(Request $request)
    {
        $query = DB::table('product as p')
            ->select('p.id', 'p.name', 'p.code', 'p.price_in', 'p.unit');

        if ($request->filled('nombre')) {
            $query->where('p.name', 'like', '%' . $request->nombre . '%');
        } elseif ($request->filled('codigo')) {
            $query->where('p.code', 'like', '%' . $request->codigo . '%');
        }

        return response()->json($query->limit(30)->get());
    }

    public function store(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'Debe agregar al menos un ítem'], 422);
        }

        try {
            DB::beginTransaction();

            // Insert cabecera
            $guiaId = DB::table('guia_cabecera')->insertGetId([
                'num_guia'             => $request->num_guia,
                'fecha_emision'        => $request->fecha_emision,
                'fecha_traslado'       => $request->fecha_traslado,
                'ruc_destinatario'     => $request->ruc_destinatario,
                'destino'              => $request->destino,
                'ruc_transportista'    => $request->ruc_transportista ?? '',
                'ruc_conductor'        => $request->ruc_conductor ?? '',
                'placa'                => $request->placa ?? '',
                'comentario'           => $request->comentario ?? '',
                'total_bruto'          => $request->total_bruto ?? 0,
                'total_neto'           => $request->total_neto ?? 0,
                'estado'               => 0,
                'origen'               => $request->origen,
                'ubigeo'               => $request->ubigeo ?? '',
                'ubigeo_destino'       => $request->ubigeo_destino ?? '',
                'modalidad_trasnporte' => str_pad($request->modalidad_trasnporte ?? '01', 2, '0', STR_PAD_LEFT),
                'motivo_traslado'      => str_pad($request->motivo_traslado ?? '01', 2, '0', STR_PAD_LEFT),
                'descripcion_motivo'   => $request->descripcion_motivo ?? '',
            ]);

            // Insert detalle items
            foreach ($items as $item) {
                DB::table('guia_detalle')->insert([
                    'id_guia'             => $guiaId,
                    'id_producto'         => $item['id_producto'],
                    'cantidad'            => $item['cantidad'] ?? 0,
                    'pedido'              => $item['pedido'] ?? '',
                    'unidad'              => $item['unidad'] ?? 'NIU',
                    'descripcion_producto'=> $item['descripcion_producto'] ?? '',
                    't_neto'              => $item['t_neto'] ?? 0,
                    't_bruto'             => $item['t_bruto'] ?? 0,
                ]);
            }

            // Increment aux counter
            DB::table('aux')->where('i', 12)->increment('id');

            DB::commit();

            return response()->json([
                'Result'  => 'OK',
                'Message' => 'Guía registrada correctamente',
                'id'      => $guiaId,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'Message' => $e->getMessage()], 500);
        }
    }

    public function sendToSunat($id, GuiaRemisionService $service)
    {
        $guia = DB::table('guia_cabecera')->where('id', $id)->first();
        if (!$guia) {
            return response()->json(['Result' => 'ERROR', 'Message' => 'Guía no encontrada'], 404);
        }

        $detalles = DB::table('guia_detalle as g')
            ->leftJoin('product as p', 'p.id', '=', 'g.id_producto')
            ->select('g.*', 'p.code', 'p.description')
            ->where('g.id_guia', $id)
            ->get();

        $destinatario = DB::table('person')->where('no', $guia->ruc_destinatario)->first();
        $transportista = null;
        $conductor = null;

        if (!empty($guia->ruc_transportista)) {
            $transportista = DB::table('transportistas')->where('ruc', $guia->ruc_transportista)->first();
        }

        if (!empty($guia->ruc_conductor)) {
            $conductor = DB::table('conductores')->where('ruc', $guia->ruc_conductor)->first();
        }

        $response = $service->procesarGuia((array)$guia, $detalles->toArray(), (array)$destinatario, (array)$transportista, (array)$conductor);

        if ($response['success']) {
            DB::table('guia_cabecera')->where('id', $id)->update(['estado' => 1]);
            return response()->json(['Result' => 'SUCCESS', 'Message' => $response['message']]);
        }

        return response()->json(['Result' => 'ERROR', 'Message' => $response['message'], 'code' => $response['code'] ?? null], 500);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            DB::table('guia_detalle')->where('id_guia', $id)->delete();
            DB::table('guia_cabecera')->where('id', $id)->delete();
            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'Message' => $e->getMessage()], 500);
        }
    }
}
