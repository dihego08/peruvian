<?php

namespace App\Http\Controllers;

use App\Models\AppMenu;
use App\Models\AppMenuUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Árbol de permisos del menú del nuevo frontend (app_menus).
     */
    public function getMenus(Request $request)
    {
        if (! DB::getSchemaBuilder()->hasTable('app_menus')) {
            return response()->json([
                'message' => 'Ejecute la migración app_menus: php artisan migrate --seed --class=AppMenuSeeder',
            ], 503);
        }

        $idUsuario = $request->query('idUsuario');
        $menus = AppMenu::where('is_active', true)
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        $userPermissions = [];
        if ($idUsuario) {
            $userPermissions = AppMenuUser::where('user_id', $idUsuario)
                ->pluck('app_menu_id')
                ->toArray();
        }

        $result = $menus->map(function (AppMenu $m) use ($userPermissions) {
            return [
                'id' => $m->id,
                'parent_id' => $m->parent_id,
                'text' => $m->label,
                'label' => $m->label,
                'route' => $m->route,
                'module_key' => $m->module_key,
                'checked' => in_array($m->id, $userPermissions),
            ];
        });

        return response()->json($result);
    }

    public function savePermissions(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|integer',
            'menuIds' => 'array',
        ]);

        if (! DB::getSchemaBuilder()->hasTable('app_menu_user')) {
            return response()->json(['message' => 'Tabla app_menu_user no existe'], 503);
        }

        $idUsuario = (int) $request->idUsuario;
        $menuIds = $request->menuIds ?? [];

        DB::transaction(function () use ($idUsuario, $menuIds) {
            AppMenuUser::where('user_id', $idUsuario)->delete();

            foreach ($menuIds as $idMenu) {
                AppMenuUser::create([
                    'app_menu_id' => (int) $idMenu,
                    'user_id' => $idUsuario,
                ]);
            }
        });

        return response()->json(['Result' => 'OK']);
    }
}
