<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Console\Commands;

use App\Modules\Catalog\Services\ProductImporter;
use Illuminate\Console\Command;
use Throwable;

class ImportProductsCommand extends Command
{
    protected $signature = 'shopify:import-products';

    protected $description = 'Import all products from Shopify into the local catalog DB';

    public function handle(ProductImporter $importer): int
    {
        $this->info('Importing products from Shopify…');

        try {
            $stats = $importer->importAll();
        } catch (Throwable $e) {
            $this->error('Import failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->table(
            ['Created', 'Updated', 'Skipped (no changes)'],
            [[$stats['created'], $stats['updated'], $stats['skipped']]],
        );

        return self::SUCCESS;
    }
}
