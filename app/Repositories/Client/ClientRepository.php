<?php
namespace App\Repositories\Client;


use App\Repositories\Client\ClientInterface as ClientInterface;
use App\Models\Clients;


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
}