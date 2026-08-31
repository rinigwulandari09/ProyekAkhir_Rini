<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Columns of detail_biaya_operasional:\n";
    print_r(Schema::getColumnListing("detail_biaya_operasional"));
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

