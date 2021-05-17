<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'data' => $this->collection,
            'links' => [
                'path' => $this->url($this->currentPage()),
                'firstPageUrl' => $this->url($this->firstItem()),
                'lastPageUrl' => $this->url($this->lastPage()),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
            ],
            'meta' => [
                'currentPage' => $this->currentPage(),
                'from' => $this->firstItem(),
                'lastPage' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
                'count' => $this->count(),
            ],
        ];
    }  

    
}
