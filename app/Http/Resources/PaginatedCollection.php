<?php

namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\ResourceCollection;
class PaginatedCollection extends ResourceCollection
{
    private $resourceClass;
    public function __construct($resource, $resourceClass)
    {
        parent::__construct($resource);
        $this->resource = $this->collectResource($resource);
        $this->resourceClass = $resourceClass;
    }

    public function toArray($request)
    {
        // return [
        //     'data' => $this->resourceClass::collection(
        //         $this->collection
        //     ),
        //     'links' => $this->resource->links()
        // ];
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->resourceClass::collection(
                        $this->collection
                    ),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}