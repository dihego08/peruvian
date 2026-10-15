<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CotizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::with('person')
            ->select(
                '*',
                DB::raw("DATE_FORMAT(fecha_creacion, '%d-%m-%Y') as fec_cre")
            )
            ->orderBy('fecha_creacion', 'DESC');

        if ($request->has('person_id') && $request->person_id != 0) {
            $query->where('person_id', $request->person_id);
        }

        $cotizations = $query->get();

        foreach ($cotizations as $cot) {
            $cot->name = $cot->person_id == 0 ? $cot->cliente : ($cot->person ? $cot->person->name : 'N/A');
            
            // Get first image from details for thumbnail
            $detail = CotizacionDetalle::where('codigo_cotizacion', $cot->codigo)->first();
            $cot->imagen = $detail ? $detail->imagen : null;
        }

        return response()->json([
            'Result' => 'OK',
            'Records' => $cotizations
        ]);
    }

    public function show($codigo)
    {
        $cabecera = Cotizacion::with('person')->where('codigo', $codigo)->first();

        if (!$cabecera) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Cotización no encontrada'], 404);
        }

        $detalle = CotizacionDetalle::with('product')->where('codigo_cotizacion', $codigo)->get();

        return response()->json([
            'Result' => 'OK',
            'cabecera' => $cabecera,
            'detalle' => $detalle
        ]);
    }

    public function store(Request $request)
    {
        $items = json_decode($request->input('items', '[]'), true);
        if (!is_array($items) || count($items) === 0) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Debe agregar al menos un producto'], 422);
        }

        DB::beginTransaction();
        try {
            // Get correlative from kind_doc id=4 (bloqueado para evitar códigos duplicados en guardados simultáneos)
            $correlativo_doc = DB::table('kind_doc')->where('id', 4)->lockForUpdate()->first();
            if (!$correlativo_doc) {
                throw new Exception('No está configurado el correlativo de cotizaciones (kind_doc id=4)');
            }
            $numero = (int)$correlativo_doc->numero;
            // Saltar números ya usados (por si el correlativo quedó desfasado)
            while (Cotizacion::where('codigo', (string)$numero)->exists()) {
                $numero++;
            }
            $codigo = (string)$numero;

            $cotizacion = Cotizacion::create(array_merge($this->headerData($request), [
                'codigo' => $codigo,
                'fecha_creacion' => now(),
            ]));

            $subtotal = $this->saveItems($request, $codigo, $items);
            $cotizacion->update($this->totals($subtotal, $request->input('aplica_igv') === 'yes'));

            // Update correlative in kind_doc
            DB::table('kind_doc')->where('id', 4)->update(['numero' => $numero + 1]);

            DB::commit();
            return response()->json(['Result' => 'OK', 'codigo' => $codigo]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Se recibe por POST (no PUT) porque PHP no procesa archivos multipart en PUT.
     * El código y la fecha de creación se conservan; los ítems se reemplazan.
     */
    public function update(Request $request, $codigo)
    {
        $cotizacion = Cotizacion::where('codigo', $codigo)->first();
        if (!$cotizacion) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Cotización no encontrada'], 404);
        }

        $items = json_decode($request->input('items', '[]'), true);
        if (!is_array($items) || count($items) === 0) {
            return response()->json(['Result' => 'ERROR', 'message' => 'Debe agregar al menos un producto'], 422);
        }

        $imagenesAnteriores = $this->uploadedImages($codigo);

        DB::beginTransaction();
        try {
            $cotizacion->update($this->headerData($request));

            CotizacionDetalle::where('codigo_cotizacion', $codigo)->delete();
            $subtotal = $this->saveItems($request, $codigo, $items);
            $cotizacion->update($this->totals($subtotal, $request->input('aplica_igv') === 'yes'));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }

        // Borra las imágenes subidas a esta cotización que fueron reemplazadas o quitadas
        $huerfanas = array_diff($imagenesAnteriores, $this->uploadedImages($codigo));
        foreach ($huerfanas as $filename) {
            @unlink(public_path('storage/products/' . $filename));
        }

        return response()->json(['Result' => 'OK', 'codigo' => $codigo]);
    }

    private function headerData(Request $request)
    {
        return [
            'tiempo_entrega' => $request->input('tiempo_entrega', ''),
            'obervacion' => $request->input('observacion', ''),
            'servicios' => $request->input('servicios', ''),
            'person_id' => $request->input('person_id', 0),
            'cliente' => $request->input('cliente_extra', ''),
            'validez' => $request->input('validez', ''),
            'forma_pago' => $request->input('forma_pago', ''),
            'tallas_especiales' => $request->input('tallas_especiales', ''),
            'asesor_comercial' => $request->input('asesor_comercial', ''),
            'asesor_celular' => $request->input('asesor_celular', ''),
            'igv_incluye' => $request->input('aplica_igv') === 'yes' ? 1 : 0,
        ];
    }

    /**
     * Crea los ítems de la cotización y devuelve el subtotal.
     * Si un ítem trae archivo nuevo se usa ese; si no, se conserva el nombre enviado en img_m / img_b.
     */
    private function saveItems(Request $request, $codigo, array $items)
    {
        $subtotal = 0;
        $destinationPath = public_path('storage/products');

        foreach ($items as $index => $item) {
            $cantidad = (float)($item['cantidad'] ?? 0);
            $costo = (float)($item['costo'] ?? 0);

            $imagen = !empty($item['img_m']) ? $item['img_m'] : null;
            $imagen_2 = !empty($item['img_b']) ? $item['img_b'] : null;

            // Handle image uploads (las claves van por índice del item, no por producto)
            if ($request->hasFile("imagen_$index")) {
                $imagen = $this->storeImage($request->file("imagen_$index"), $destinationPath, "{$index}_1");
            }

            if ($request->hasFile("imagen_b_$index")) {
                $imagen_2 = $this->storeImage($request->file("imagen_b_$index"), $destinationPath, "{$index}_2");
            }

            CotizacionDetalle::create([
                'codigo_cotizacion' => $codigo,
                'id_producto' => $item['product_id'],
                'cantidad' => $cantidad,
                'imagen' => $imagen,
                'imagen_2' => $imagen_2,
                'costo' => (string)$costo,
                'descripcion' => $item['descripcion'] ?? '',
                'nombre_producto' => $item['nombre_producto'] ?? '',
                'servicios' => '' // Can be expanded if needed
            ]);

            $subtotal += ($cantidad * $costo);
        }

        return $subtotal;
    }

    private function totals($subtotal, $aplicaIgv)
    {
        $igv = $aplicaIgv ? $subtotal * 0.18 : 0;
        return [
            'sub_total' => (string)round($subtotal, 2),
            'igv' => (string)round($igv, 2),
            'total' => (string)round($subtotal + $igv, 2),
        ];
    }

    /**
     * Imágenes subidas desde cotizaciones (prefijo cot_); las de productos nunca se borran.
     */
    private function uploadedImages($codigo)
    {
        return CotizacionDetalle::where('codigo_cotizacion', $codigo)->get(['imagen', 'imagen_2'])
            ->flatMap(fn ($d) => [$d->imagen, $d->imagen_2])
            ->filter(fn ($f) => $f && str_starts_with($f, 'cot_'))
            ->unique()->values()->all();
    }

    /**
     * Guarda la imagen con un nombre corto: cotizacion_detalle.imagen_2 es varchar(100)
     * y el nombre original (ej. fotos de WhatsApp) puede exceder ese largo.
     */
    private function storeImage($file, $destinationPath, $suffix)
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $filename = 'cot_' . time() . "_{$suffix}_" . Str::random(6) . '.' . $extension;
        $file->move($destinationPath, $filename);
        return $filename;
    }

    public function destroy($codigo)
    {
        DB::beginTransaction();
        try {
            CotizacionDetalle::where('codigo_cotizacion', $codigo)->delete();
            Cotizacion::where('codigo', $codigo)->delete();
            DB::commit();
            return response()->json(['Result' => 'OK']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['Result' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }
}
