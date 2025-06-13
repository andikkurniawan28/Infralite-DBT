<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NativeMysqlDump extends Command
{
    protected $signature = 'native:mysqldump
        {--host=localhost : Host DB}
        {--user=root : Username DB}
        {--pass= : Password DB}
        {--db= : Nama Database}
        {--path=storage/app/tmp/backup.sql : Lokasi file output}';

    protected $description = 'Menjalankan mysqldump via native shell command';

    public function handle()
    {
        $host = $this->option('host');
        $user = $this->option('user');
        $pass = $this->option('pass');
        $db   = $this->option('db');
        $path = base_path($this->option('path'));

        if (!$db) {
            $this->error('Parameter --db harus diisi.');
            return 1;
        }

        // Pastikan direktori tujuan ada
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Path ke mysqldump.exe (sesuaikan jika perlu)
        $mysqldump = '"C:\xampp\mysql\bin\mysqldump.exe"';

        // Susun command
        $command = "{$mysqldump} -h{$host} -u{$user} -p{$pass} {$db} > \"{$path}\"";

        $this->info("Menjalankan perintah:");
        $this->line($command);

        // Jalankan
        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("✅ Backup berhasil: {$path}");
        } else {
            $this->error("❌ Backup gagal dengan exit code {$result}");
        }

        return $result;
    }
}
