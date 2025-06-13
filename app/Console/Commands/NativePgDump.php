<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NativePgDump extends Command
{
    protected $signature = 'native:pgdump
        {--host=localhost : Database host}
        {--port=5432 : Database port}
        {--user=postgres : Database username}
        {--pass= : Database password}
        {--db= : Database name}
        {--path=storage/app/tmp/backup.sql : Output file path}';

    protected $description = 'Run pg_dump via native shell command to backup PostgreSQL database';

    public function handle()
    {
        $host = $this->option('host');
        $port = $this->option('port');
        $user = $this->option('user');
        $pass = $this->option('pass');
        $db   = $this->option('db');
        $path = base_path($this->option('path'));

        if (!$db) {
            $this->error('Parameter --db is required.');
            return 1;
        }

        // Ensure output directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Path to pg_dump binary (adjust if needed)
        $pgdump = 'pg_dump'; // Or full path if on Windows or custom install
        // $pgdump = '"C:\Program Files\PostgreSQL\13\bin\pg_dump.exe"'; // Windows example

        // Construct command
        $envPart = "PGPASSWORD=\"{$pass}\"";
        $command = "{$envPart} {$pgdump} -h {$host} -p {$port} -U {$user} -F p -d {$db} -f \"{$path}\"";

        $this->info("Running command:");
        $this->line($command);

        // Run the command
        $output = null;
        $result = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("✅ Backup successful: {$path}");
        } else {
            $this->error("❌ Backup failed with exit code {$result}");
        }

        return $result;
    }
}
