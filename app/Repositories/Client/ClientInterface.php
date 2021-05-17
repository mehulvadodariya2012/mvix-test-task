<?php
namespace App\Repositories\Client;


interface ClientInterface {


    public function getAll();


    public function find($id);


    public function delete($id);
}