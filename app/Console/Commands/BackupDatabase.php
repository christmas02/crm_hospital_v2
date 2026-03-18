<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database to a SQL file';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $filename = 'backup_' . $database . '_' . date('Y-m-d_His') . '.sql';
        $path = storage_path('app/backups');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filepath = $path . '/' . $filename;

        // Try to find mysqldump
        $mysqldump = null;
        $possiblePaths = [
            '/Applications/MAMP/Library/bin/mysql80/bin/mysqldump',
            '/Applications/MAMP/Library/bin/mysql57/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/bin/mysqldump',
            'mysqldump',
        ];

        foreach ($possiblePaths as $p) {
            if (file_exists($p) || $p === 'mysqldump') {
                $mysqldump = $p;
                break;
            }
        }

        if (!$mysqldump) {
            $this->error('mysqldump not found');
            return 1;
        }

        $command = sprintf(
            '%s --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
            $mysqldump, $host, $port, $username, $password, $database, $filepath
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $size = round(filesize($filepath) / 1024, 1);
            $this->info("Backup created: {$filename} ({$size} KB)");

            // Keep only last 10 backups
            $files = glob($path . '/backup_*.sql');
            if (count($files) > 10) {
                usort($files, fn($a, $b) => filemtime($a) - filemtime($b));
                $toDelete = array_slice($files, 0, count($files) - 10);
                foreach ($toDelete as $f) {
                    unlink($f);
                }
                $this->info('Old backups cleaned up. Keeping last 10.');
            }

            return 0;
        }

        $this->error('Backup failed: ' . implode("\n", $output));
        return 1;
    }
}
