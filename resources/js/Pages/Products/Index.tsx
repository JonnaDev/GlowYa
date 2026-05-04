import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function ProductsIndex() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Productos importados
                </h2>
            }
        >
            <Head title="Productos importados" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-600 dark:text-gray-300">
                            Vista detallada de productos sincronizados desde Shopify con estado de stock. Implementación pendiente.
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
