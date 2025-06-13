<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class NativeSqliteDump extends Command
{
    protected $signature = 'native:sqlitedump
        {--source= : Full path to the SQLite database file}
        {--path=storage/app/tmp/backup.sqlite : Output backup file path}';

    protected $description = 'Backup SQLite database by copying the database file';

    public function handle()
    {
        $source = $this->option('source');
        $target = base_path($this->option('path'));

        if (!$source || !file_exists($source)) {
            $this->error("❌ Invalid or missing --source. File does not exist.");
            return 1;
        }

        // Pastikan direktori tujuan ada
        $dir = dirname($target);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Lakukan copy
        if (File::copy($source, $target)) {
            $this->info("✅ Backup successful: {$target}");
            return 0;
        } else {
            $this->error("❌ Backup failed. Could not copy the file.");
            return 1;
        }
    }
}
