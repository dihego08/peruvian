import React, { useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import api from '../services/api';

function MenuIcon({ icon }) {
  if (!icon) return null;
  return <i className={`${icon} w-5 text-center text-blue-400`} aria-hidden />;
}

function NavLink({ to, children, className = '' }) {
  const location = useLocation();
  const active = to && location.pathname === to;
  const base =
    'flex items-center gap-2 px-4 py-2.5 rounded-lg transition-colors duration-200 text-sm font-medium';

  if (!to) {
    return (
      <span className={`${base} text-gray-500 cursor-default ${className}`}>
        {children}
      </span>
    );
  }

  return (
    <Link
      to={to}
      className={`${base} ${active ? 'bg-gray-800 text-white' : 'hover:bg-gray-800'} ${className}`}
    >
      {children}
    </Link>
  );
}

function MenuGroup({ item }) {
  const [open, setOpen] = useState(true);
  const hasChildren = item.hijos?.length > 0;

  if (!hasChildren) {
    const to = item.route || null;
    return (
      <NavLink to={to}>
        <MenuIcon icon={item.icon} />
        <span>{item.text}</span>
      </NavLink>
    );
  }

  return (
    <div>
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        className="w-full flex items-center gap-2 px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors text-sm font-medium text-left"
      >
        <MenuIcon icon={item.icon} />
        <span className="flex-1">{item.text}</span>
        <span className="text-gray-500 text-xs">{open ? '▾' : '▸'}</span>
      </button>
      {open && (
        <div className="ml-3 mt-0.5 space-y-0.5 border-l border-gray-700 pl-2">
          {item.hijos.map((child) => {
            const to = child.route || null;
            return (
              <NavLink key={child.id} to={to} className="text-gray-300">
                <MenuIcon icon={child.icon} />
                <span>{child.text}</span>
              </NavLink>
            );
          })}
        </div>
      )}
    </div>
  );
}

export default function SidebarMenu() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let cancelled = false;

    (async () => {
      try {
        const res = await api.get('/menu/navigation');
        if (!cancelled) {
          setItems(res.data ?? []);
          setError(null);
        }
      } catch (e) {
        if (!cancelled) {
          setError('No se pudo cargar el menú');
          setItems([]);
        }
      } finally {
        if (!cancelled) setLoading(false);
      }
    })();

    return () => {
      cancelled = true;
    };
  }, []);

  if (loading) {
    return (
      <p className="px-4 py-2 text-sm text-gray-500 animate-pulse">Cargando menú…</p>
    );
  }

  if (error) {
    return <p className="px-4 py-2 text-sm text-red-400">{error}</p>;
  }

  if (items.length === 0) {
    return (
      <p className="px-4 py-2 text-sm text-gray-500">
        Sin módulos asignados. Configure accesos en Administración.
      </p>
    );
  }

  return (
    <div className="space-y-1">
      {items.map((item) => (
        <MenuGroup key={item.id} item={item} />
      ))}
    </div>
  );
}
