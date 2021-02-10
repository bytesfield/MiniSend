<?php

namespace App\QueryFilters\Search;

use App\Http\Requests\SearchMailRequest;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FilterSubject
{
    private $request;

    public function __construct(SearchMailRequest $request)
    {
        $this->request = $request;
    }

    /**
     * filter subject
     *
     * @param [type] $request
     * @param Closure $next
     * @return void
     */
    public function handle($query, Closure $next)
    {
        if (!$this->request->has('search')) {
            return $next($query);
        }
        $search = $this->request->search;

        $builder = $query->orWhere('subject', 'LIKE', "%$search%");

        return $next($builder);
    }
}
