<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class NotContainsSensitiveFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$notContainsc';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            $connection = DB::connection()->getDriverName();

            foreach ($this->values as $value) {
                switch ($connection) {
                    case 'mariadb':
                    case 'mysql':
                        $query->whereRaw("BINARY `{$this->column}` not like ?", ["%{$value}%"]);
                        break;
                    case 'sqlite':
                        $query->whereRaw("`{$this->column}` COLLATE BINARY not like ?", ["%{$value}%"]);
                        break;
                    case 'pgsql':
                        $query->whereNot($this->column, 'LIKE', "%{$value}%");
                        break;
                    case 'sqlsrv':
                        $query->whereRaw("`{$this->column}` COLLATE Latin1_General_BIN not LIKE ?", ["%{$value}%"]);
                        break;
                    default:
                        throw new RuntimeException("Unsupported database driver: {$connection}");
                }
            }
        };
    }
}
