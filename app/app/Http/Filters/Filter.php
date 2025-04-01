<?php

namespace App\Http\Filters;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    protected Request $request;
    protected Builder $query;
    protected int $recordsTotal;
    protected bool $hasFilters = false;
    protected array $searchKeys = [];

    public function __construct(Request $request, Builder $query, array $searchKeys = [])
    {
        $this->request = $request;
        $this->query = $query;
        $this->searchKeys = $searchKeys;
    }

    public function apply(): self
    {
        $this->recordsTotal = $this->query->count();
        $this->applySearchFilter();
        return $this;
    }

    public function getResult()
    {
        if ($this->hasFilters)
        $recordsFiltered = $this->query->count();
        
        $this->applyPagination();
        
        return [
            'total' => $this->recordsTotal,
            'filtered' => $recordsFiltered ?? 0,
            'query' => $this->query,
        ];
    }


    protected function applySearchFilter(): void
    {
        if (!$this->request->filled('search') || empty($this->searchKeys))
        return;

        $searchValue = $this->request->get('search');

        $this->query->where(function ($query) use ($searchValue) {
            foreach ($this->searchKeys as $key) {
                $query->orWhere($key, 'like', '%' . $searchValue . '%');
            }
        });

        $this->hasFilters = true;
    }


    private function applyPagination(): self
    {
        $this->applyLength();
        $this->applyOffset();
        return $this;
    }

    private function applyLength(): void
    {
        if ($this->request->filled('length'))
        $this->query->take((int)$this->request->get('length'));
    }

    private function applyOffset(): void
    {
        if ($this->request->filled('start'))
        $this->query->skip((int)$this->request->get('start'));
    }
}
