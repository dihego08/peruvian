<?php

namespace Database\Seeders;

use App\Models\AppMenu;
use App\Models\AppMenuUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppMenuSeeder extends Seeder
{
    /**
     * Menú del SPA React (frontend/). Rutas deben coincidir con App.jsx.
     */
    public function run(): void
    {
        DB::table('app_menu_user')->delete();
        AppMenu::query()->delete();

        $tree = [
            [
                'module_key' => 'grp_admin',
                'label' => 'Administración',
                'icon' => 'fa fa-cog',
                'sort_order' => 10,
                'children' => [
                    ['module_key' => 'users', 'label' => 'Usuarios', 'route' => '/users', 'icon' => 'fa fa-users', 'sort_order' => 1],
                    ['module_key' => 'cargos', 'label' => 'Cargos', 'route' => '/cargos', 'icon' => 'fa fa-briefcase', 'sort_order' => 2],
                    ['module_key' => 'permissions', 'label' => 'Accesos', 'route' => '/permissions', 'icon' => 'fa fa-lock', 'sort_order' => 3],
                ],
            ],
            [
                'module_key' => 'grp_catalog',
                'label' => 'Catálogos',
                'icon' => 'fa fa-folder-open',
                'sort_order' => 20,
                'children' => [
                    ['module_key' => 'clients', 'label' => 'Clientes', 'route' => '/clients', 'icon' => 'fa fa-user', 'sort_order' => 1],
                    ['module_key' => 'products', 'label' => 'Productos', 'route' => '/products', 'icon' => 'fa fa-cube', 'sort_order' => 2],
                    ['module_key' => 'providers', 'label' => 'Proveedores', 'route' => '/providers', 'icon' => 'fa fa-truck', 'sort_order' => 3],
                    ['module_key' => 'brands', 'label' => 'Marcas', 'route' => '/brands', 'icon' => 'fa fa-tags', 'sort_order' => 4],
                    ['module_key' => 'tech_sheets', 'label' => 'Fichas Técnicas', 'route' => '/tech-sheets', 'icon' => 'fa fa-file-text', 'sort_order' => 5],
                    ['module_key' => 'machines', 'label' => 'Maquinaria', 'route' => '/machines', 'icon' => 'fa fa-cogs', 'sort_order' => 6],
                    ['module_key' => 'insumos', 'label' => 'Insumos', 'route' => '/insumos', 'icon' => 'fa fa-flask', 'sort_order' => 7],
                    ['module_key' => 'unidades', 'label' => 'Unidades', 'route' => '/unidades', 'icon' => 'fa fa-balance-scale', 'sort_order' => 8],
                    ['module_key' => 'fam_class', 'label' => 'Familias y Clases', 'route' => '/fam-class', 'icon' => 'fa fa-sitemap', 'sort_order' => 9],
                ],
            ],
            [
                'module_key' => 'grp_purchases',
                'label' => 'Compras',
                'icon' => 'fa fa-shopping-basket',
                'sort_order' => 30,
                'children' => [
                    ['module_key' => 'purchases', 'label' => 'Compras', 'route' => '/purchases', 'icon' => 'fa fa-list', 'sort_order' => 1],
                    ['module_key' => 'purchases_new', 'label' => 'Nueva Compra', 'route' => '/purchases/new', 'icon' => 'fa fa-plus-circle', 'sort_order' => 2],
                ],
            ],
            [
                'module_key' => 'grp_transactions',
                'label' => 'Transacciones',
                'icon' => 'fa fa-exchange',
                'sort_order' => 40,
                'children' => [
                    ['module_key' => 'sells', 'label' => 'Ventas', 'route' => '/sells', 'icon' => 'fa fa-list-alt', 'sort_order' => 1],
                    ['module_key' => 'sells_new', 'label' => 'Nueva Venta', 'route' => '/sells/new', 'icon' => 'fa fa-plus', 'sort_order' => 2],
                    ['module_key' => 'sell_payments', 'label' => 'Ventas Pagos', 'route' => '/sell-payments', 'icon' => 'fa fa-money', 'sort_order' => 3],
                    ['module_key' => 'orders', 'label' => 'Pedidos', 'route' => '/orders', 'icon' => 'fa fa-clipboard', 'sort_order' => 4],
                    ['module_key' => 'orders_new', 'label' => 'Nuevo Pedido', 'route' => '/orders/new', 'icon' => 'fa fa-plus-square', 'sort_order' => 5],
                    ['module_key' => 'cotizations', 'label' => 'Cotizaciones', 'route' => '/cotizations', 'icon' => 'fa fa-file-o', 'sort_order' => 6],
                    ['module_key' => 'cotizations_new', 'label' => 'Nueva Cotización', 'route' => '/cotizations/new', 'icon' => 'fa fa-plus', 'sort_order' => 7],
                    ['module_key' => 'guias', 'label' => 'Guías de Remisión', 'route' => '/guias', 'icon' => 'fa fa-truck', 'sort_order' => 8],
                    ['module_key' => 'guias_new', 'label' => 'Nueva Guía', 'route' => '/guias/new', 'icon' => 'fa fa-plus', 'sort_order' => 9],
                ],
            ],
            [
                'module_key' => 'grp_sig',
                'label' => 'SIG',
                'icon' => 'fa fa-building',
                'sort_order' => 50,
                'children' => [
                    ['module_key' => 'sig_perfil_puesto', 'label' => 'Perfil de Puesto', 'route' => '/sig/perfil-puesto', 'icon' => 'fa fa-id-card', 'sort_order' => 1],
                    ['module_key' => 'sig_areas', 'label' => 'Áreas', 'route' => '/sig/areas', 'icon' => 'fa fa-map', 'sort_order' => 2],
                    ['module_key' => 'sig_puestos', 'label' => 'Puestos', 'route' => '/sig/puestos', 'icon' => 'fa fa-suitcase', 'sort_order' => 3],
                    ['module_key' => 'sig_colaboradores', 'label' => 'Personal', 'route' => '/sig/colaboradores', 'icon' => 'fa fa-users', 'sort_order' => 4],
                    ['module_key' => 'sig_documents', 'label' => 'Documentos', 'route' => '/sig/documents', 'icon' => 'fa fa-book', 'sort_order' => 5],
                ],
            ],
            [
                'module_key' => 'grp_reports',
                'label' => 'Reportes',
                'icon' => 'fa fa-bar-chart',
                'sort_order' => 60,
                'children' => [
                    ['module_key' => 'rep_sells_sunat', 'label' => 'Ventas - Sunat', 'route' => '/reports/sells-sunat', 'icon' => 'fa fa-money', 'sort_order' => 1],
                    ['module_key' => 'rep_ventas_cliente', 'label' => 'Ventas - Cliente', 'route' => '/reports/ventas-cliente', 'icon' => 'fa fa-money', 'sort_order' => 2],
                    ['module_key' => 'rep_ventas_mensuales', 'label' => 'Ventas - Mensuales', 'route' => '/reports/ventas-mensuales', 'icon' => 'fa fa-money', 'sort_order' => 3],
                    ['module_key' => 'rep_ventas_cruzado', 'label' => 'Ventas - Cruzado', 'route' => '/reports/ventas-cruzado', 'icon' => 'fa fa-money', 'sort_order' => 4],
                ],
            ],
            [
                'module_key' => 'grp_asistencias_control',
                'label' => 'Control Asistencias',
                'icon' => 'fa fa-clock-o',
                'sort_order' => 65,
                'children' => [
                    ['module_key' => 'asist_colaboradores', 'label' => 'Colaboradores', 'route' => '/colaboradores', 'icon' => 'fa fa-users', 'sort_order' => 1],
                    ['module_key' => 'asist_relojes', 'label' => 'Relojes', 'route' => '/relojes', 'icon' => 'fa fa-clock-o', 'sort_order' => 2],
                    ['module_key' => 'asist_feriados', 'label' => 'Feriados', 'route' => '/feriados', 'icon' => 'fa fa-calendar', 'sort_order' => 3],
                    ['module_key' => 'asist_permisos', 'label' => 'Permisos', 'route' => '/permisos', 'icon' => 'fa fa-check-square-o', 'sort_order' => 4],
                    ['module_key' => 'asist_horarios', 'label' => 'Horarios', 'route' => '/horarios', 'icon' => 'fa fa-calendar-times-o', 'sort_order' => 5],
                    ['module_key' => 'asist_asignar', 'label' => 'Asignar Horario', 'route' => '/asignar', 'icon' => 'fa fa-user-plus', 'sort_order' => 6],
                    ['module_key' => 'asist_tipos_permisos', 'label' => 'Tipos de Permisos', 'route' => '/tipos_permisos', 'icon' => 'fa fa-tags', 'sort_order' => 7],
                ],
            ],
            [
                'module_key' => 'grp_asistencias_reportes',
                'label' => 'Reportes Asistencias',
                'icon' => 'fa fa-pie-chart',
                'sort_order' => 70,
                'children' => [
                    ['module_key' => 'rep_asist_colaborador', 'label' => 'Por Colaborador', 'route' => '/reportes', 'icon' => 'fa fa-user', 'sort_order' => 1],
                    ['module_key' => 'rep_asist_dia', 'label' => 'Por Día', 'route' => '/reportes_dia', 'icon' => 'fa fa-calendar-o', 'sort_order' => 2],
                    ['module_key' => 'rep_asist_completo', 'label' => 'Por Completo', 'route' => '/reportes_dias', 'icon' => 'fa fa-calendar', 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($tree as $group) {
            $children = $group['children'];
            unset($group['children']);

            $parent = AppMenu::create([
                'parent_id' => 0,
                'label' => $group['label'],
                'route' => null,
                'icon' => $group['icon'],
                'sort_order' => $group['sort_order'],
                'module_key' => $group['module_key'],
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                AppMenu::create([
                    'parent_id' => $parent->id,
                    'label' => $child['label'],
                    'route' => $child['route'],
                    'icon' => $child['icon'],
                    'sort_order' => $child['sort_order'],
                    'module_key' => $child['module_key'],
                    'is_active' => true,
                ]);
            }
        }

        $this->grantAllMenusToAdmins();
    }

    /** Administradores (kind = 1) reciben todos los módulos del nuevo menú. */
    private function grantAllMenusToAdmins(): void
    {
        $menuIds = AppMenu::where('is_active', true)->pluck('id');
        $adminIds = User::where('kind', 1)->where('status', 1)->pluck('id');

        foreach ($adminIds as $userId) {
            foreach ($menuIds as $menuId) {
                AppMenuUser::firstOrCreate([
                    'app_menu_id' => $menuId,
                    'user_id' => $userId,
                ]);
            }
        }
    }
}
