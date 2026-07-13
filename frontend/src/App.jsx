import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, useNavigate } from 'react-router-dom';
import UsersView from './views/UsersView';
import ClientsView from './views/ClientsView';
import ProductsView from './views/ProductsView';
import ProvidersView from './views/ProvidersView';
import BrandsView from './views/BrandsView';
import SellsView from './views/transactions/SellsView';
import NewSellView from './views/transactions/NewSellView';
import SellPaymentsView from './views/transactions/SellPaymentsView';
import OrdersView from './views/transactions/OrdersView';
import NewOrderView from './views/transactions/NewOrderView';
import EditOrderView from './views/transactions/EditOrderView';
import OrderProductionView from './views/transactions/OrderProductionView';
import CotizationsView from './views/transactions/CotizationsView';
import NewCotizationView from './views/transactions/NewCotizationView';
import InsumosView from './views/InsumosView';
import UnidadesView from './views/UnidadesView';
import FamClassView from './views/FamClassView';
import LoginView from './views/LoginView';
import TechSheetView from './views/TechSheetView';
import CargosView from './views/CargosView';
import PermissionsView from './views/PermissionsView';
import MaquinasView from './views/MaquinasView';
import MachineMaintenanceView from './views/MachineMaintenanceView';
import DocumentsView from './views/DocumentsView';
import PurchasesView from './views/PurchasesView';
import NewPurchaseView from './views/NewPurchaseView';
import PerfilPuestosView from './views/PerfilPuestosView';
import AreasView from './views/AreasView';
import PuestosView from './views/PuestosView';
import ColaboradoresView from './views/ColaboradoresView';
import GuiasView from './views/GuiasView';
import NewGuiaView from './views/NewGuiaView';
import logo from './assets/logo.png';
import SidebarMenu from './components/SidebarMenu';

/** Base de despliegue (VITE_BASE_PATH). Necesario para rutas en nueva pestaña. */
const routerBasename = import.meta.env.BASE_URL.replace(/\/$/, '') || undefined;

function ProtectedRoute({ children, auth }) {
  if (!auth) return <Navigate to="/login" replace />;
  return children;
}

function MainLayout({ setAuth }) {
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem('user') || '{}');

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    setAuth(false);
    navigate('/login');
  };

  return (
    <div className="flex h-screen bg-gray-100 font-sans">
      {/* Sidebar */}
      <div className="w-64 bg-gray-900 text-white shadow-xl flex flex-col">
        <div className="p-6 border-b border-gray-800">
          <h1 className="text-2xl font-bold text-blue-400">
            <img src={logo} alt="Logo" className="w-100" />
          </h1>
          <p className="text-sm text-gray-400 mt-1 italic">Gestión Empresarial</p>
        </div>
        <nav className="flex-1 p-4 overflow-y-auto custom-scrollbar">
          <SidebarMenu />
        </nav>
        <div className="p-4 border-t border-gray-800 bg-gray-950/50">
          <button
            onClick={handleLogout}
            className="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition-colors font-bold"
          >
            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Cerrar Sesión
          </button>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
          <h2 className="text-lg font-bold text-gray-800 tracking-tight">Panel de Control</h2>
          <div className="flex items-center space-x-3">
            <div className="text-right">
              <p className="text-sm font-bold text-gray-900 leading-none">{user.name} {user.lastname}</p>
              <p className="text-[10px] text-blue-600 font-black uppercase tracking-tighter mt-1">
                {user.kind === 1 ? 'Administrador' : 'Usuario'}
              </p>
            </div>
            <div className="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black shadow-lg shadow-blue-200">
              {user.name?.[0].toUpperCase()}
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
          <Routes>
            <Route path="/" element={<div className="text-center mt-20 animate-in fade-in slide-in-from-bottom-4 duration-700"><h2 className="text-3xl font-black text-gray-800 tracking-tight">Bienvenido, {user.name}</h2><p className="text-gray-500 mt-4 max-w-md mx-auto">Seleccione un módulo del menú lateral para comenzar a gestionar su empresa.</p></div>} />
            <Route path="/users" element={<UsersView />} />
            <Route path="/cargos" element={<CargosView />} />
            <Route path="/permissions" element={<PermissionsView />} />
            <Route path="/clients" element={<ClientsView />} />
            <Route path="/products" element={<ProductsView />} />
            <Route path="/providers" element={<ProvidersView />} />
            <Route path="/brands" element={<BrandsView />} />
            <Route path="/tech-sheets" element={<TechSheetView />} />
            <Route path="/machines" element={<MaquinasView />} />
            <Route path="/machines/:mid/maintenance" element={<MachineMaintenanceView />} />
            <Route path="/purchases" element={<PurchasesView />} />
            <Route path="/purchases/new" element={<NewPurchaseView />} />
            <Route path="/sells" element={<SellsView />} />
            <Route path="/sells/new" element={<NewSellView />} />
            <Route path="/sell-payments" element={<SellPaymentsView />} />
            <Route path="/orders" element={<OrdersView />} />
            <Route path="/orders/new" element={<NewOrderView />} />
            <Route path="/orders/:codigo/edit" element={<EditOrderView />} />
            <Route path="/orders/:codigo/production" element={<OrderProductionView />} />
            <Route path="/cotizations" element={<CotizationsView />} />
            <Route path="/cotizations/new" element={<NewCotizationView />} />
            <Route path="/sig/perfil-puesto" element={<PerfilPuestosView />} />
            <Route path="/sig/areas" element={<AreasView />} />
            <Route path="/sig/puestos" element={<PuestosView />} />
            <Route path="/sig/colaboradores" element={<ColaboradoresView />} />
            <Route path="/sig/documents" element={<DocumentsView />} />
            <Route path="/guias" element={<GuiasView />} />
            <Route path="/guias/new" element={<NewGuiaView />} />
            <Route path="/insumos" element={<InsumosView />} />
            <Route path="/unidades" element={<UnidadesView />} />
            <Route path="/fam-class" element={<FamClassView />} />
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </main>
      </div>
    </div>
  );
}

function App() {
  const [auth, setAuth] = useState(!!localStorage.getItem('token'));

  return (
    <Router basename={routerBasename}>
      <Routes>
        <Route path="/login" element={!auth ? <LoginView setAuth={setAuth} /> : <Navigate to="/" replace />} />
        <Route path="/*" element={
          <ProtectedRoute auth={auth}>
            <MainLayout setAuth={setAuth} />
          </ProtectedRoute>
        } />
      </Routes>
    </Router>
  );
}

export default App;
