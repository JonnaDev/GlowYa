import { InertiaLinkProps, Link } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

interface SidebarNavLinkProps extends Omit<InertiaLinkProps, 'className'> {
    active?: boolean;
    icon?: LucideIcon;
}

export default function SidebarNavLink({
    active = false,
    icon: Icon,
    children,
    ...props
}: SidebarNavLinkProps) {
    return (
        <Link
            {...props}
            className={
                'group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 ' +
                (active
                    ? 'bg-gradient-to-r from-sky-500/20 to-sky-400/5 text-sky-300 shadow-[inset_0_1px_0_0_rgba(125,211,252,0.1)]'
                    : 'text-slate-400 hover:bg-white/5 hover:text-slate-100')
            }
        >
            {active && (
                <span className="absolute inset-y-1 left-0 w-0.5 rounded-r-full bg-sky-400" />
            )}
            {Icon && (
                <Icon
                    className={
                        'h-4 w-4 shrink-0 transition-colors ' +
                        (active ? 'text-sky-400' : 'text-slate-500 group-hover:text-slate-300')
                    }
                />
            )}
            <span className="truncate">{children}</span>
        </Link>
    );
}
