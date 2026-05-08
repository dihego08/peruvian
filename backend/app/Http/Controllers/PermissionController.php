<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuEntity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function getMenus(Request $request)
    {
        $idUsuario = $request->query('idUsuario');
        $menus = Menu::all();
        
        $userPermissions = [];
        if ($idUsuario) {
            $userPermissions = MenuEntity::where('idUsuario', $idUsuario)
                ->pluck('idMenu')
                ->toArray();
        }

        // Return flat list with parent info for easier React handling
        $result = $menus->map(function($m) use ($userPermissions) {
            return [
                'id' => $m->id,
                'parent_id' => $m->parent_id,
                'text' => $m->text,
                'checked' => in_array($m->id, $userPermissions)
            ];
        });

        return response()->json($result);
    }

    public function savePermissions(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|integer',
            'menuIds' => 'array'
        ]);

        $idUsuario = $request->idUsuario;
        $menuIds = $request->menuIds;

        DB::transaction(function () use ($idUsuario, $menuIds) {
            MenuEntity::where('idUsuario', $idUsuario)->delete();
            if ($menuIds) {
                foreach ($menuIds as $idMenu) {
                    MenuEntity::create([
                        'idUsuario' => $idUsuario,
                        'idMenu' => $idMenu
                    ]);
                }
            }
        });

        return response()->json(['Result' => 'OK']);
    }
}
