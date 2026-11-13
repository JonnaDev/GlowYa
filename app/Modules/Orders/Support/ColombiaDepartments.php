<?php

declare(strict_types=1);

namespace App\Modules\Orders\Support;

/*
Fuente única de departamentos: la usan los <select> de las landings y la
validación del pedido público, para que el servidor rechace cualquier valor
que no sea una opción real del formulario.
 */
final class ColombiaDepartments
{
    /** @var list<string> */
    public const LIST = [
        'Amazonas', 'Antioquia', 'Arauca', 'Atlántico', 'Bogotá D.C.',
        'Bolívar', 'Boyacá', 'Caldas', 'Caquetá', 'Casanare', 'Cauca',
        'Cesar', 'Chocó', 'Córdoba', 'Cundinamarca', 'Guainía', 'Guaviare',
        'Huila', 'La Guajira', 'Magdalena', 'Meta', 'Nariño',
        'Norte de Santander', 'Putumayo', 'Quindío', 'Risaralda',
        'San Andrés y Providencia', 'Santander', 'Sucre', 'Tolima',
        'Valle del Cauca', 'Vaupés', 'Vichada',
    ];
}
