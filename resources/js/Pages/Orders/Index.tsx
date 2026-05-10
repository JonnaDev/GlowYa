import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import {
    ArrowUpRight,
    ChevronLeft,
    ChevronRight,
    ExternalLink,
    Package,
    Search,
    Truck,
    X,
    XCircle,
} from 'lucide-react';
import { useState } from 'react';

type OrderItem = {
    id: number;
    title_snapshot: string;
    quantity: number;
    unit_price: string;
};

type Order = {
    id: number;
    idempotency_key: string;
    shopify_order_id: string | null;
    source: string;
    status_local: string;
    status_shopify: string | null;
    recipient_full_name: string;
    recipient_phone: string;
    recipient_email: string;
    recipient_city: string;
    recipient_department: string;
    total_amount: string;
    tracking_number: string | null;
    tracking_url: string | null;
    created_at: string;
    last_synced_at: string | null;
    items: OrderItem[];
};

type PaginatedOrders = {
    data: Order[];
    current_page: number;
    last_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

type StatusCounts = {
    all: number;
    pending_confirmation: number;
    sent_to_shopify: number;
    fulfilled: number;
    cancelled: number;
};

type Filters = {
    status: string;
    search: string;
};

const statusConfig: Record<
    string,
    { label: string; color: string; bg: string; icon: React.ElementType }
> = {
    pending_confirmation: {
        label: 'Pendiente',
        color: 'text-amber-400',
        bg: 'bg-amber-400/10 border-amber-400/20',
        icon: Package,
    },
    confirmed: {
        label: 'Confirmada',
        color: 'text-blue-400',
        bg: 'bg-blue-400/10 border-blue-400/20',
        icon: Package,
    },
    sent_to_shopify: {
        label: 'En Shopify',
        color: 'text-sky-400',
        bg: 'bg-sky-400/10 border-sky-400/20',
        icon: ArrowUpRight,
    },
    fulfilled: {
        label: 'Entregada',
        color: 'text-emerald-400',
        bg: 'bg-emerald-400/10 border-emerald-400/20',
        icon: Truck,
    },
    cancelled: {
        label: 'Cancelada',
        color: 'text-red-400',
        bg: 'bg-red-400/10 border-red-400/20',
        icon: XCircle,
    },
};

function StatusBadge({ status }: { status: string }) {
    const config = statusConfig[status] ?? {
        label: status,
        color: 'text-slate-400',
        bg: 'bg-slate-400/10 border-slate-400/20',
        icon: Package,
    };
    const Icon = config.icon;

    return (
        <span
            className={`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium ${config.bg} ${config.color}`}
        >
            <Icon className="h-3 w-3" />
            {config.label}
        </span>
    );
}

function StatusTabs({
    counts,
    current,
    onChange,
}: {
    counts: StatusCounts;
    current: string;
    onChange: (status: string) => void;
}) {
    const tabs = [
        { key: '', label: 'Todas', count: counts.all },
        { key: 'pending_confirmation', label: 'Pendientes', count: counts.pending_confirmation },
        { key: 'sent_to_shopify', label: 'En Shopify', count: counts.sent_to_shopify },
        { key: 'fulfilled', label: 'Entregadas', count: counts.fulfilled },
        { key: 'cancelled', label: 'Canceladas', count: counts.cancelled },
    ];

    return (
        <div className="flex gap-1 overflow-x-auto rounded-xl bg-slate-800/50 p-1">
            {tabs.map((tab) => (
                <button
                    key={tab.key}
                    onClick={() => onChange(tab.key)}
                    className={`flex items-center gap-2 whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium transition-all ${
                        current === tab.key
                            ? 'bg-slate-700 text-white shadow-sm'
                            : 'text-slate-400 hover:text-slate-200'
                    }`}
                >
                    {tab.label}
                    <span
                        className={`rounded-full px-2 py-0.5 text-xs ${
                            current === tab.key
                                ? 'bg-sky-500/20 text-sky-300'
                                : 'bg-slate-700/50 text-slate-500'
                        }`}
                    >
                        {tab.count}
                    </span>
                </button>
            ))}
        </div>
    );
}

export default function OrdersIndex({
    orders,
    filters,
    statusCounts,
}: {
    orders: PaginatedOrders;
    filters: Filters;
    statusCounts: StatusCounts;
}) {
    const [search, setSearch] = useState(filters.search);

    const applyFilters = (newFilters: Partial<Filters>) => {
        router.get(
            route('orders.index'),
            { ...filters, ...newFilters, page: 1 },
            { preserveState: true, replace: true },
        );
    };

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        applyFilters({ search });
    };

    const formatDate = (dateStr: string) => {
        return new Date(dateStr).toLocaleDateString('es-CO', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const formatCurrency = (amount: string) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
        }).format(parseFloat(amount));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Órdenes
                </h2>
            }
        >
            <Head title="Órdenes" />

            <div className="py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {/* Status Tabs */}
                    <StatusTabs
                        counts={statusCounts}
                        current={filters.status}
                        onChange={(status) => applyFilters({ status })}
                    />

                    {/* Search */}
                    <form onSubmit={handleSearch} className="mt-4 flex gap-3">
                        <div className="relative flex-1">
                            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Buscar por nombre, email, teléfono o ID Shopify..."
                                className="w-full rounded-lg border border-slate-700/50 bg-slate-800/50 py-2.5 pl-10 pr-10 text-sm text-slate-200 placeholder-slate-500 transition focus:border-sky-500/50 focus:outline-none focus:ring-1 focus:ring-sky-500/50"
                            />
                            {search && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setSearch('');
                                        applyFilters({ search: '' });
                                    }}
                                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300"
                                >
                                    <X className="h-4 w-4" />
                                </button>
                            )}
                        </div>
                        <button
                            type="submit"
                            className="rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-sky-500"
                        >
                            Buscar
                        </button>
                    </form>

                    {/* Table */}
                    <div className="mt-6 overflow-hidden rounded-xl border border-slate-700/50 bg-slate-800/30">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-slate-700/50 text-left">
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            #
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Cliente
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Producto
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Total
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Estado
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Shopify
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Tracking
                                        </th>
                                        <th className="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Fecha
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-700/30">
                                    {orders.data.length === 0 && (
                                        <tr>
                                            <td
                                                colSpan={8}
                                                className="px-4 py-12 text-center text-slate-500"
                                            >
                                                No hay órdenes{' '}
                                                {filters.status ? 'con este estado' : 'aún'}.
                                            </td>
                                        </tr>
                                    )}
                                    {orders.data.map((order) => (
                                        <tr
                                            key={order.id}
                                            className="transition-colors hover:bg-slate-700/20"
                                        >
                                            <td className="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-400">
                                                #{order.id}
                                            </td>
                                            <td className="px-4 py-3">
                                                <div className="font-medium text-slate-200">
                                                    {order.recipient_full_name}
                                                </div>
                                                <div className="text-xs text-slate-500">
                                                    {order.recipient_phone} ·{' '}
                                                    {order.recipient_city}
                                                </div>
                                            </td>
                                            <td className="px-4 py-3 text-xs text-slate-300">
                                                {order.items.map((item) => (
                                                    <div key={item.id}>
                                                        {item.title_snapshot} × {item.quantity}
                                                    </div>
                                                ))}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-3 font-semibold text-slate-200">
                                                {formatCurrency(order.total_amount)}
                                            </td>
                                            <td className="px-4 py-3">
                                                <StatusBadge status={order.status_local} />
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-500">
                                                {order.shopify_order_id ? (
                                                    <span className="text-sky-400">
                                                        #{order.shopify_order_id}
                                                    </span>
                                                ) : (
                                                    <span className="text-slate-600">—</span>
                                                )}
                                            </td>
                                            <td className="px-4 py-3">
                                                {order.tracking_url ? (
                                                    <a
                                                        href={order.tracking_url}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="inline-flex items-center gap-1 text-xs text-emerald-400 hover:text-emerald-300"
                                                    >
                                                        {order.tracking_number ?? 'Ver'}
                                                        <ExternalLink className="h-3 w-3" />
                                                    </a>
                                                ) : order.tracking_number ? (
                                                    <span className="text-xs text-slate-400">
                                                        {order.tracking_number}
                                                    </span>
                                                ) : (
                                                    <span className="text-xs text-slate-600">
                                                        —
                                                    </span>
                                                )}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                                                {formatDate(order.created_at)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {orders.last_page > 1 && (
                            <div className="flex items-center justify-between border-t border-slate-700/50 px-4 py-3">
                                <span className="text-xs text-slate-500">
                                    Mostrando página {orders.current_page} de{' '}
                                    {orders.last_page} · {orders.total} órdenes
                                </span>
                                <div className="flex gap-1">
                                    {orders.links.map((link, idx) => {
                                        if (!link.url) return null;
                                        const isFirst = idx === 0;
                                        const isLast = idx === orders.links.length - 1;

                                        return (
                                            <Link
                                                key={idx}
                                                href={link.url}
                                                preserveState
                                                className={`rounded-md px-3 py-1.5 text-xs transition ${
                                                    link.active
                                                        ? 'bg-sky-600 text-white'
                                                        : 'text-slate-400 hover:bg-slate-700 hover:text-slate-200'
                                                }`}
                                            >
                                                {isFirst ? (
                                                    <ChevronLeft className="h-3.5 w-3.5" />
                                                ) : isLast ? (
                                                    <ChevronRight className="h-3.5 w-3.5" />
                                                ) : (
                                                    <span
                                                        dangerouslySetInnerHTML={{
                                                            __html: link.label,
                                                        }}
                                                    />
                                                )}
                                            </Link>
                                        );
                                    })}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
