<?php

namespace Tec\Installer\Services;

use Tec\Base\Services\ClearCacheService;
use Tec\Base\Supports\Database;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ImportDatabaseService
{
    public function handle(string $path): void
    {
        try {
            Database::restoreFromPath($path);

            ClearCacheService::make()->purgeAll();
        } catch (QueryException $exception) {
            throw ValidationException::withMessages([
                'database' => [$exception->getMessage()],
            ]);
        }
    }
}
