import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { PageProps } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import {
    AlertTriangle,
    CheckCircle2,
    ExternalLink,
    EyeOff,
    Globe,
    Layers,
    Loader2,
} from 'lucide-react';
import { useState } from 'react';

type LandingItem = {
    id: number;
    slug: string;
    title: string;
    blade_view: string;
    description: string | null;
    is_active: boolean;
    product_id: number | null;
    public_url: string;
    updated_at: string | null;
};

type LandingIndexProps = PageProps<{
    landings: LandingItem[];
    flash?: {
        success?: string;
        error?: string;
    };
}>;

export default function LandingIndex() {
    const { landings, flash } = usePage<LandingIndexProps>().props;
    const [togglingSlug, setTogglingSlug] = useState<string | null>(null);

    const handleToggle = (slug: string) => {
        setTogglingSlug(slug);
        router.post(
            route('admin.landings.toggle', { landing: slug }),
            {},
            {
                preserveScroll: true,
                onFinish: () => setTogglingSlug(null),
            },
        );
    };

    const activeCount = landings.filter((l) => l.is_active).length;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 className="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">
                            Landings
                        </h2>
                        <p className="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Activá o desactivá las landing pages públicas. Si una landing está desactivada,
                            su URL pública responde 404.
                        </p>
                    </div>
                    <div className="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                        <Layers className="h-4 w-4 text-sky-500" />
                        {activeCount} de {landings.length} activas
                    </div>
                </div>
            }
        >
            <Head title="Landings" />

            <div className="py-8">
                <div className="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
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

                    {landings.length === 0 ? (
                        <div className="flex flex-col items-center gap-2 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900">
                            <EyeOff className="h-10 w-10 text-slate-300 dark:text-slate-600" />
                            <p className="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Sin landings registradas
                            </p>
                            <p className="max-w-xs text-xs text-slate-500 dark:text-slate-400">
                                Las landings se registran vía migration o seeder. La primera (NOIL)
                                debería aparecer acá automáticamente tras ejecutar las migrations.
                            </p>
                        </div>
                    ) : (
                        <ul className="space-y-3">
                            {landings.map((landing) => (
                                <LandingCard
                                    key={landing.id}
                                    landing={landing}
                                    isToggling={togglingSlug === landing.slug}
                                    onToggle={() => handleToggle(landing.slug)}
                                />
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function LandingCard({
    landing,
    isToggling,
    onToggle,
}: {
    landing: LandingItem;
    isToggling: boolean;
    onToggle: () => void;
}) {
    return (
        <li className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
            <div className="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div className="flex min-w-0 flex-1 items-start gap-4">
                    <div
                        className={
                            'flex h-11 w-11 shrink-0 items-center justify-center rounded-lg ring-1 ring-inset ' +
                            (landing.is_active
                                ? 'bg-emerald-50 text-emerald-600 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/30'
                                : 'bg-slate-100 text-slate-400 ring-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:ring-slate-700')
                        }
                    >
                        <Globe className="h-5 w-5" />
                    </div>
                    <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center gap-2">
                            <h3 className="truncate text-base font-semibold text-slate-800 dark:text-slate-100">
                                {landing.title}
                            </h3>
                            <StatusPill active={landing.is_active} />
                        </div>
                        {landing.description && (
                            <p className="mt-1 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">
                                {landing.description}
                            </p>
                        )}
                        <div className="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span>
                                <span className="font-medium text-slate-600 dark:text-slate-300">slug:</span>{' '}
                                <code className="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[11px] text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    /{landing.slug}
                                </code>
                            </span>
                            <span>
                                <span className="font-medium text-slate-600 dark:text-slate-300">view:</span>{' '}
                                <code className="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[11px] text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {landing.blade_view}
                                </code>
                            </span>
                            {landing.is_active && (
                                <a
                                    href={landing.public_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex items-center gap-1 font-medium text-sky-600 hover:text-sky-500 dark:text-sky-400 dark:hover:text-sky-300"
                                >
                                    Ver pública
                                    <ExternalLink className="h-3 w-3" />
                                </a>
                            )}
                        </div>
                    </div>
                </div>

                <div className="flex shrink-0 items-center gap-3 self-end sm:self-center">
                    <ToggleSwitch
                        checked={landing.is_active}
                        loading={isToggling}
                        onChange={onToggle}
                        label={landing.is_active ? 'Activa' : 'Inactiva'}
                    />
                </div>
            </div>
        </li>
    );
}

function StatusPill({ active }: { active: boolean }) {
    return active ? (
        <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/20">
            <span className="h-1.5 w-1.5 rounded-full bg-emerald-500" />
            Activa
        </span>
    ) : (
        <span className="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-slate-600 ring-1 ring-inset ring-slate-500/20 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-600/30">
            <span className="h-1.5 w-1.5 rounded-full bg-slate-400" />
            Pausada
        </span>
    );
}

function ToggleSwitch({
    checked,
    loading,
    onChange,
    label,
}: {
    checked: boolean;
    loading: boolean;
    onChange: () => void;
    label: string;
}) {
    return (
        <button
            type="button"
            role="switch"
            aria-checked={checked}
            onClick={onChange}
            disabled={loading}
            className={
                'relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors disabled:cursor-not-allowed disabled:opacity-60 ' +
                (checked
                    ? 'bg-emerald-500'
                    : 'bg-slate-300 dark:bg-slate-700')
            }
        >
            <span className="sr-only">{label}</span>
            <span
                className={
                    'inline-flex h-5 w-5 transform items-center justify-center rounded-full bg-white shadow transition-transform ' +
                    (checked ? 'translate-x-5' : 'translate-x-0.5')
                }
            >
                {loading && <Loader2 className="h-3 w-3 animate-spin text-slate-500" />}
            </span>
        </button>
    );
}
