import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { PageProps } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import {
    AlertTriangle,
    CheckCircle2,
    CloudDownload,
    Database,
    Loader2,
    PackageOpen,
    RefreshCw,
    Sparkles,
} from 'lucide-react';
import { useMemo, useState } from 'react';

type ShopifyProduct = {
    shopify_product_id: string;
    title: string;
    handle: string | null;
    status: string;
    price: string | null;
    sku: string | null;
    inventory_quantity: number;
    image_url: string | null;
    updated_at: string | null;
};

type LocalProduct = {
    id: number;
    shopify_product_id: string;
    title: string;
    handle: string | null;
    status: string;
    price: string;
    sku: string | null;
    inventory_quantity: number;
    image_url: string | null;
    shopify_updated_at: string | null;
    imported_at: string | null;
    content_hash: string | null;
};

type ImportPageProps = PageProps<{
    shopifyProducts: ShopifyProduct[];
    localProducts: LocalProduct[];
    shopifyError: string | null;
    flash?: {
        success?: string;
        error?: string;
    };
}>;

const formatPrice = (value: string | null) => {
    if (!value) return '—';
    const num = parseFloat(value);
    if (Number.isNaN(num)) return value;
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(num);
};

const StatusBadge = ({ status }: { status: string }) => {
    const palette =
        status === 'active'
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/20'
            : status === 'draft'
              ? 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/20'
              : 'bg-slate-50 text-slate-600 ring-slate-500/20 dark:bg-slate-500/10 dark:text-slate-300 dark:ring-slate-400/20';
    return (
        <span
            className={`inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset ${palette}`}
        >
            {status}
        </span>
    );
};

export default function CatalogImport() {
    const { shopifyProducts, localProducts, shopifyError, flash } = usePage<ImportPageProps>().props;
    const [syncing, setSyncing] = useState(false);

    const localIndex = useMemo(
        () => new Set(localProducts.map((p) => p.shopify_product_id)),
        [localProducts],
    );

    const pendingCount = useMemo(
        () => shopifyProducts.filter((p) => !localIndex.has(p.shopify_product_id)).length,
        [shopifyProducts, localIndex],
    );

    const handleSync = () => {
        setSyncing(true);
        router.post(
            route('catalog.import.sync'),
            {},
            {
                preserveScroll: true,
                onFinish: () => setSyncing(false),
            },
        );
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">
                            Importación Shopify
                        </h2>
                        <p className="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Sincroniza el catálogo de tu tienda Shopify con la base de datos local de Glofit.
                        </p>
                    </div>
                    <button
                        type="button"
                        onClick={handleSync}
                        disabled={syncing || !!shopifyError}
                        className="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition-all hover:bg-sky-500 disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none"
                    >
                        {syncing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <RefreshCw className="h-4 w-4" />
                        )}
                        {syncing ? 'Sincronizando…' : 'Sincronizar todo'}
                    </button>
                </div>
            }
        >
            <Head title="Importación Shopify" />

            <div className="py-8">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    {/* Flash messages */}
                    {flash?.success && (
                        <div className="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                            <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            <p className="text-sm text-emerald-800 dark:text-emerald-200">
                                {flash.success}
                            </p>
                        </div>
                    )}
                    {flash?.error && (
                        <div className="flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 p-4 dark:border-rose-500/30 dark:bg-rose-500/10">
                            <AlertTriangle className="mt-0.5 h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" />
                            <p className="text-sm text-rose-800 dark:text-rose-200">
                                {flash.error}
                            </p>
                        </div>
                    )}
                    {shopifyError && (
                        <div className="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
                            <AlertTriangle className="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" />
                            <div>
                                <p className="text-sm font-semibold text-amber-800 dark:text-amber-200">
                                    Error conectando con Shopify
                                </p>
                                <p className="mt-1 text-xs text-amber-700 dark:text-amber-300">
                                    {shopifyError}
                                </p>
                            </div>
                        </div>
                    )}

                    {/* Stats */}
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <StatCard
                            icon={CloudDownload}
                            label="En Shopify"
                            value={shopifyProducts.length}
                            tint="sky"
                        />
                        <StatCard
                            icon={Database}
                            label="En DB local"
                            value={localProducts.length}
                            tint="emerald"
                        />
                        <StatCard
                            icon={Sparkles}
                            label="Pendientes de importar"
                            value={pendingCount}
                            tint="amber"
                        />
                    </div>

                    {/* Two panels */}
                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        {/* Shopify */}
                        <Panel
                            title="Productos en Shopify"
                            subtitle={`${shopifyProducts.length} producto(s) en la tienda`}
                            icon={CloudDownload}
                            tint="sky"
                        >
                            {shopifyProducts.length === 0 ? (
                                <EmptyState
                                    title="Sin productos en Shopify"
                                    description="Importá productos desde Dropi al admin de Shopify para verlos acá."
                                />
                            ) : (
                                <ul className="divide-y divide-slate-200 dark:divide-slate-800">
                                    {shopifyProducts.map((p) => {
                                        const isLocal = localIndex.has(p.shopify_product_id);
                                        return (
                                            <ProductRow
                                                key={p.shopify_product_id}
                                                title={p.title}
                                                imageUrl={p.image_url}
                                                sku={p.sku}
                                                status={p.status}
                                                price={p.price}
                                                stock={p.inventory_quantity}
                                                badge={
                                                    isLocal
                                                        ? { label: 'Sincronizado', tone: 'emerald' }
                                                        : { label: 'Nuevo', tone: 'amber' }
                                                }
                                            />
                                        );
                                    })}
                                </ul>
                            )}
                        </Panel>

                        {/* Local DB */}
                        <Panel
                            title="Productos en DB local"
                            subtitle={`${localProducts.length} producto(s) sincronizados`}
                            icon={Database}
                            tint="emerald"
                        >
                            {localProducts.length === 0 ? (
                                <EmptyState
                                    title="DB local vacía"
                                    description="Hacé click en 'Sincronizar todo' para traer los productos de Shopify."
                                />
                            ) : (
                                <ul className="divide-y divide-slate-200 dark:divide-slate-800">
                                    {localProducts.map((p) => (
                                        <ProductRow
                                            key={p.id}
                                            title={p.title}
                                            imageUrl={p.image_url}
                                            sku={p.sku}
                                            status={p.status}
                                            price={p.price}
                                            stock={p.inventory_quantity}
                                            footer={
                                                p.imported_at
                                                    ? `Importado ${new Date(p.imported_at).toLocaleString('es-CO')}`
                                                    : null
                                            }
                                        />
                                    ))}
                                </ul>
                            )}
                        </Panel>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

const tintMap = {
    sky: {
        bg: 'bg-sky-50 dark:bg-sky-500/10',
        text: 'text-sky-700 dark:text-sky-300',
        ring: 'ring-sky-200 dark:ring-sky-500/30',
    },
    emerald: {
        bg: 'bg-emerald-50 dark:bg-emerald-500/10',
        text: 'text-emerald-700 dark:text-emerald-300',
        ring: 'ring-emerald-200 dark:ring-emerald-500/30',
    },
    amber: {
        bg: 'bg-amber-50 dark:bg-amber-500/10',
        text: 'text-amber-700 dark:text-amber-300',
        ring: 'ring-amber-200 dark:ring-amber-500/30',
    },
} as const;

function StatCard({
    icon: Icon,
    label,
    value,
    tint,
}: {
    icon: typeof CloudDownload;
    label: string;
    value: number;
    tint: keyof typeof tintMap;
}) {
    const t = tintMap[tint];
    return (
        <div className="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div className={`flex h-11 w-11 items-center justify-center rounded-lg ${t.bg} ${t.text}`}>
                <Icon className="h-5 w-5" />
            </div>
            <div>
                <p className="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    {label}
                </p>
                <p className="text-2xl font-bold text-slate-800 dark:text-slate-100">{value}</p>
            </div>
        </div>
    );
}

function Panel({
    title,
    subtitle,
    icon: Icon,
    tint,
    children,
}: {
    title: string;
    subtitle: string;
    icon: typeof CloudDownload;
    tint: keyof typeof tintMap;
    children: React.ReactNode;
}) {
    const t = tintMap[tint];
    return (
        <section className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <header className="flex items-center gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <div className={`flex h-9 w-9 items-center justify-center rounded-lg ${t.bg} ${t.text}`}>
                    <Icon className="h-4 w-4" />
                </div>
                <div>
                    <h3 className="text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {title}
                    </h3>
                    <p className="text-xs text-slate-500 dark:text-slate-400">{subtitle}</p>
                </div>
            </header>
            <div className="max-h-[600px] overflow-y-auto">{children}</div>
        </section>
    );
}

function ProductRow({
    title,
    imageUrl,
    sku,
    status,
    price,
    stock,
    badge,
    footer,
}: {
    title: string;
    imageUrl: string | null;
    sku: string | null;
    status: string;
    price: string | null;
    stock: number;
    badge?: { label: string; tone: 'emerald' | 'amber' };
    footer?: string | null;
}) {
    const badgePalette =
        badge?.tone === 'emerald'
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/20'
            : 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/20';

    return (
        <li className="flex gap-4 p-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
            <div className="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                {imageUrl ? (
                    <img
                        src={imageUrl}
                        alt={title}
                        className="h-full w-full object-cover"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center text-slate-400">
                        <PackageOpen className="h-6 w-6" />
                    </div>
                )}
            </div>
            <div className="min-w-0 flex-1">
                <div className="flex items-start justify-between gap-2">
                    <p className="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">
                        {title}
                    </p>
                    {badge && (
                        <span
                            className={`shrink-0 rounded-full px-2 py-0.5 text-[10px] font-medium ring-1 ring-inset ${badgePalette}`}
                        >
                            {badge.label}
                        </span>
                    )}
                </div>
                <div className="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                    {sku && <span>SKU: {sku}</span>}
                    <span>Stock: {stock}</span>
                    <StatusBadge status={status} />
                </div>
                <div className="mt-1.5 flex items-center justify-between gap-2">
                    <p className="text-sm font-semibold text-slate-700 dark:text-slate-200">
                        {formatPrice(price)}
                    </p>
                    {footer && (
                        <p className="text-[10px] text-slate-400 dark:text-slate-500">
                            {footer}
                        </p>
                    )}
                </div>
            </div>
        </li>
    );
}

function EmptyState({ title, description }: { title: string; description: string }) {
    return (
        <div className="flex flex-col items-center gap-2 px-6 py-12 text-center">
            <PackageOpen className="h-10 w-10 text-slate-300 dark:text-slate-600" />
            <p className="text-sm font-semibold text-slate-700 dark:text-slate-200">{title}</p>
            <p className="max-w-xs text-xs text-slate-500 dark:text-slate-400">{description}</p>
        </div>
    );
}
