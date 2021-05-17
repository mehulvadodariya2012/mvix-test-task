<?php
namespace App\Repositories\Client;


use App\Repositories\Client\ClientInterface as ClientInterface;
use App\Models\Clients;
use Illuminate\Database\Eloquent\Model;

class ClientRepository implements ClientInterface
{
    public $client;


    function __construct(Clients $client) {
	    $this->client = $client;
    }


    public function getAll()
    {
        return $this->client->paginate(2);
    }


    public function find($id)
    {
        //
    }


    public function delete($id)
    {
        //
    }

    /**
    * @param array $attributes
    *
    * @return Model
    */
    public function create(array $attributes): Model
    {
        return $this->client->create($attributes);
    }
}