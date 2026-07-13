<?php

namespace App\Http\Controllers;

use App\Models\AppMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Menú lateral del SPA según app_menus + app_menu_user.
     */
    public function navigation(Request $request)
    {
        $userId = $this->resolveUserId($request);
        if (!$userId) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        if (! $this->appMenuTablesExist()) {
            return response()->json([
                'message' => 'Tablas app_menus no instaladas. Ejecute: php artisan migrate --seed --class=AppMenuSeeder',
            ], 503);
        }

        $allowedIds = DB::table('app_menu_user')
            ->where('user_id', $userId)
            ->pluck('app_menu_id');

        if ($allowedIds->isEmpty()) {
            return response()->json([]);
        }

        $parents = AppMenu::query()
            ->where('parent_id', 0)
            ->where('is_active', true)
            ->whereIn('id', $allowedIds)
            ->orderBy('sort_order')
            ->get();

        $items = [];
        foreach ($parents as $parent) {
            $children = AppMenu::query()
                ->where('parent_id', $parent->id)
                ->where('is_active', true)
                ->whereIn('id', $allowedIds)
                ->orderBy('sort_order')
                ->get();

            $items[] = $this->formatMenuNode($parent, $children);
        }

        return response()->json($items);
    }

    private function formatMenuNode(AppMenu $node, $children = null): array
    {
        $row = [
            'id' => $node->id,
            'text' => $node->label,
            'label' => $node->label,
            'parent_id' => $node->parent_id,
            'route' => $node->route,
            'icon' => $node->icon,
            'module_key' => $node->module_key,
            'hijos' => [],
        ];

        if ($children !== null) {
            $row['hijos'] = $children->map(fn (AppMenu $c) => $this->formatMenuNode($c))->values()->all();
        }

        return $row;
    }

    private function appMenuTablesExist(): bool
    {
        return DB::getSchemaBuilder()->hasTable('app_menus')
            && DB::getSchemaBuilder()->hasTable('app_menu_user');
    }

    private function resolveUserId(Request $request): ?int
    {
        $header = $request->header('Authorization', '');
        if (preg_match('/Bearer\s+(.+)/i', $header, $m)) {
            $decoded = base64_decode($m[1], true);
            if ($decoded !== false && str_contains($decoded, '|')) {
                $id = (int) explode('|', $decoded, 2)[0];
                return $id > 0 ? $id : null;
            }
        }

        $idUsuario = $request->query('idUsuario');
        return $idUsuario ? (int) $idUsuario : null;
    }
}
