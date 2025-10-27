<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:database {--table=} {--limit=} {--offset=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Structure and Data';

    private $result;

    public function handle()
    {
        $this->result = new \stdClass();
        $this->result->tables = [];

        $tableName = $this->option('table');
        $limit = $this->option('limit') ?? 100;
        $offset = $this->option('offset') ?? 0;
        if ($tableName !== null) {
            $this->showTable($tableName, $limit, $offset);
        } else {
            $this->showTables($limit, $offset);
        }

        echo json_encode($this->result);
    }

    private function showTables(int $limit = 100, int $offset = 0)
    {
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $table = (array)$table;
            $tableName = $table['Tables_in_' . config('database.connections.mysql.database')];
            $this->showTable($tableName, $limit, $offset);
        }
    }

    private function showTable(string $tableName, int $limit = 100, int $offset = 0)
    {
        $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
        $this->result->tables[$tableName]['columns'] = $columns;

        $rows = DB::select("select * from `$tableName` limit $limit offset $offset");

        foreach ($rows as $_row) {
            $this->result->tables[$tableName]['rows'][] = $_row;
        }
    }
}
