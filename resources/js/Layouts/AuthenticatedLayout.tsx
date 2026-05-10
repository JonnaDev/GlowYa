import ApplicationLogo from '@/Components/ApplicationLogo';
import SidebarNavLink from '@/Components/SidebarNavLink';
import { Link, usePage } from '@inertiajs/react';
import {
    BarChart3,
    ChevronUp,
    DollarSign,
    Download,
    Globe,
    Home,
    LucideIcon,
    Menu,
    Package,
    ShoppingCart,
    Tag,
    X,
} from 'lucide-react';
import { PropsWithChildren, ReactNode, useState } from 'react';

type SidebarLink = {
    label: string;
    href: string;
    routeName?: string;
    icon: LucideIcon;
};

type SidebarSection = {
    title: string;
    links: SidebarLink[];
};

const sections: SidebarSection[] = [
    {
        title: 'Catálogo',
        links: [
            { label: 'Importación Shopify', href: '/catalog/import', routeName: 'catalog.import', icon: Download },
            { label: 'Categorías', href: '/catalog/categories', routeName: 'catalog.categories', icon: Tag },
        ],
    },
    {
        title: 'Admin',
        links: [
            { label: 'Landings', href: '/admin/landings', routeName: 'admin.landings.index', icon: Globe },
            { label: 'Órdenes generadas', href: '/orders', routeName: 'orders.index', icon: ShoppingCart },
            { label: 'Productos importados', href: '/products', routeName: 'products.index', icon: Package },
            { label: 'Ventas totales', href: '/sales', routeName: 'sales.index', icon: DollarSign },
            { label: 'Analítica', href: '/analytics', routeName: 'analytics.index', icon: BarChart3 },
        ],
    },
];

export default function Authenticated({
    header,
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const user = usePage().props.auth.user;
    const [mobileSidebarOpen, setMobileSidebarOpen] = useState(false);
    const [userMenuOpen, setUserMenuOpen] = useState(false);

    const isActive = (routeName?: string) => {
        if (!routeName) return false;
        try {
            return route().current(routeName);
        } catch {
            return false;
        }
    };

    return (
        <div className="min-h-screen bg-slate-100 dark:bg-slate-950">
            {/* Sidebar */}
            <aside
                className={
                    'fixed inset-y-0 left-0 z-30 flex w-64 transform flex-col border-r border-slate-800/80 bg-gradient-to-b from-slate-900 via-slate-950 to-slate-950 transition-transform duration-300 lg:translate-x-0 ' +
                    (mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0')
                }
            >
                {/* Logo */}
                <div className="flex h-16 shrink-0 items-center justify-between border-b border-slate-800/80 px-5">
                    <Link href={route('dashboard')} className="flex items-center gap-2.5">
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-400 to-sky-600 shadow-lg shadow-sky-500/25">
                            <ApplicationLogo className="h-5 w-auto fill-current text-slate-950" />
                        </div>
                        <div className="flex flex-col leading-tight">
                            <span className="text-sm font-semibold tracking-tight text-slate-100">
                                GlowYa
                            </span>
                            <span className="text-[10px] font-medium uppercase tracking-wider text-slate-500">
                                Admin
                            </span>
                        </div>
                    </Link>
                    <button
                        type="button"
                        onClick={() => setMobileSidebarOpen(false)}
                        className="rounded-md p-1.5 text-slate-400 hover:bg-white/5 hover:text-slate-200 lg:hidden"
                        aria-label="Cerrar menú"
                    >
                        <X className="h-5 w-5" />
                    </button>
                </div>

                {/* Navigation */}
                <nav className="flex-1 space-y-6 overflow-y-auto px-3 py-5">
                    <div>
                        <SidebarNavLink
                            href={route('dashboard')}
                            active={isActive('dashboard')}
                            icon={Home}
                        >
                            Inicio
                        </SidebarNavLink>
                    </div>

                    {sections.map((section) => (
                        <div key={section.title}>
                            <p className="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500">
                                {section.title}
                            </p>
                            <ul className="space-y-0.5">
                                {section.links.map((link) => (
                                    <li key={link.href}>
                                        <SidebarNavLink
                                            href={link.href}
                                            active={isActive(link.routeName)}
                                            icon={link.icon}
                                        >
                                            {link.label}
                                        </SidebarNavLink>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </nav>

                {/* User menu */}
                <div className="relative border-t border-slate-800/80 p-3">
                    {/* Upward panel — visible when userMenuOpen */}
                    {userMenuOpen && (
                        <>
                            <div
                                className="fixed inset-0 z-40"
                                onClick={() => setUserMenuOpen(false)}
                            />
                            <div className="absolute bottom-full left-3 right-3 z-50 mb-1 overflow-hidden rounded-lg border border-slate-700/60 bg-slate-800 shadow-xl shadow-black/30">
                                <Link
                                    href={route('profile.edit')}
                                    className="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-200 transition-colors hover:bg-white/5"
                                    onClick={() => setUserMenuOpen(false)}
                                >
                                    Mi perfil
                                </Link>
                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-red-400 transition-colors hover:bg-white/5"
                                    onClick={() => setUserMenuOpen(false)}
                                >
                                    Cerrar sesión
                                </Link>
                            </div>
                        </>
                    )}

                    <button
                        type="button"
                        onClick={() => setUserMenuOpen((prev) => !prev)}
                        className="group flex w-full items-center justify-between rounded-lg p-2 transition-all duration-200 hover:bg-white/5"
                    >
                        <span className="flex items-center gap-3 overflow-hidden">
                            <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 text-xs font-semibold uppercase text-slate-200 ring-2 ring-slate-700">
                                {user.name.charAt(0)}
                            </span>
                            <span className="flex flex-col items-start overflow-hidden leading-tight">
                                <span className="w-full truncate text-sm font-semibold text-slate-100">
                                    {user.name}
                                </span>
                                <span className="w-full truncate text-xs text-slate-500">
                                    {user.email}
                                </span>
                            </span>
                        </span>
                        <ChevronUp
                            className={`h-4 w-4 shrink-0 text-slate-500 transition-all group-hover:text-slate-300 ${userMenuOpen ? '' : 'rotate-180'}`}
                        />
                    </button>
                </div>
            </aside>

            {/* Mobile overlay */}
            {mobileSidebarOpen && (
                <div
                    className="fixed inset-0 z-20 bg-slate-950/70 backdrop-blur-sm lg:hidden"
                    onClick={() => setMobileSidebarOpen(false)}
                />
            )}

            {/* Main */}
            <div className="lg:pl-64">
                {/* Mobile topbar */}
                <header className="sticky top-0 z-10 flex h-16 items-center border-b border-slate-200 bg-white/80 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 lg:hidden">
                    <button
                        type="button"
                        onClick={() => setMobileSidebarOpen(true)}
                        className="rounded-md p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                        aria-label="Abrir menú"
                    >
                        <Menu className="h-6 w-6" />
                    </button>
                    <span className="ml-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        GlowYa Admin
                    </span>
                </header>

                {header && (
                    <header className="border-b border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {header}
                        </div>
                    </header>
                )}

                <main>{children}</main>
            </div>
        </div>
    );
}
