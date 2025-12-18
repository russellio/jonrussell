<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sql
        {sql : SQL to run (wrap in quotes)}
        {--connection= : Connection name (defaults to default connection)}
        {--json : Output JSON}
        {--limit=200 : Max rows returned for SELECT/WITH}
        {--unsafe : Allow non-read queries (UPDATE/DELETE/INSERT/etc)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a SQL query against the configured database (safe by default).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sql = trim((string) $this->argument('sql'));

        // Reject multiple statements. Allow one trailing semicolon.
        $normalized = rtrim($sql);
        if (str_contains(substr($normalized, 0, -1), ';')) {
            $this->error('Multiple statements are not allowed.');

            return self::FAILURE;
        }

        $firstWord = strtolower(strtok(ltrim($sql), " \t\r\n(") ?: '');
        $readOnly = in_array($firstWord, ['select', 'with', 'pragma', 'explain'], true);

        if (! $readOnly && ! $this->option('unsafe')) {
            $this->error('Blocked non-read query. Re-run with --unsafe if you really mean it.');

            return self::FAILURE;
        }

        $conn = $this->option('connection')
            ? DB::connection($this->option('connection'))
            : DB::connection();

        try {
            if ($readOnly) {
                $rows = $conn->select($this->applyLimitIfNeeded($sql));

                return $this->outputRows($rows);
            }

            $affected = $conn->affectingStatement($sql);
            $payload = ['affected' => $affected];

            if ($this->option('json')) {
                $this->line(json_encode($payload, JSON_PRETTY_PRINT));
            } else {
                $this->info("Affected rows: {$affected}");
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function applyLimitIfNeeded(string $sql): string
    {
        $limit = (int) $this->option('limit');
        if ($limit <= 0) {
            return $sql;
        }

        $lower = strtolower($sql);
        if (str_contains($lower, ' limit ')) {
            return $sql;
        }

        $firstWord = strtolower(strtok(ltrim($sql), " \t\r\n(") ?: '');
        if (! in_array($firstWord, ['select', 'with'], true)) {
            return $sql;
        }

        // Wrap to enforce a limit without trying to parse SQL.
        return "select * from ({$sql}) as _q limit {$limit}";
    }

    private function outputRows(array $rows): int
    {
        // $rows is array of stdClass objects.
        $arr = array_map(fn ($r) => (array) $r, $rows);

        if ($this->option('json')) {
            $this->line(json_encode($arr, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        if (empty($arr)) {
            $this->info('0 rows.');

            return self::SUCCESS;
        }

        $headers = array_keys($arr[0]);
        $this->table($headers, $arr);

        return self::SUCCESS;
    }
}
