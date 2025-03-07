<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContainsCaseSensitiveFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$containsc';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            $connection = strtolower(DB::connection()->getDriverName());

            $collateStatements = [
                'mariadb' => 'BINARY `%s` like ?',
                'mysql'   => 'BINARY `%s` like ?',
                'sqlite'  => '`%s` COLLATE BINARY like ?',
                'pgsql'   => '%s ILIKE ?',
                'sqlsrv'  => '`%s` COLLATE Latin1_General_BIN LIKE ?',
            ];

            if (!isset($collateStatements[$connection])) {
                throw new RuntimeException("Unsupported database driver: {$connection}");
            }

            $queryPattern = $collateStatements[$connection];

            foreach ($this->values as $value) {
                if ($connection === 'pgsql') {
                    $query->where($this->column, 'ILIKE', "%{$value}%");
                } else {
                    $query->whereRaw(sprintf($queryPattern, $this->column), ["%{$value}%"]);
                }
            }
        };
    }
}
