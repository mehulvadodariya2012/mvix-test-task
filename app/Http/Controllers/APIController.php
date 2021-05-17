<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\ClientUsers;
use App\Repositories\Client\ClientInterface;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class APIController
{
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
    
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zipCode' => 'required',
            'phoneNo1' => 'required',
            'user' => 'required',
            'user.firstName' => 'required',
            'user.lastName' => 'required',
            'user.email' => 'required|email',
            'user.password' => 'required',
            'user.passwordConfirmation' => 'required|same:user.password',
            'user.phone' => 'required',
        ]);

        if($validator->fails()){
            return response()->json( $validator->errors(), 400);
        }

        
        
        

        // CALL GEO API TO GET LAT-LONG
        $key = config('app.google_api_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?key='.$key.'&address='.$request->address1.', '.$request->address2.', '.$request->city.', '.$request->zipCode);
    	$latLong = $response->json();

        $dt = Carbon::now();
        $clientInfo = array(
            'client_name' => $request->name,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip' => $request->zipCode,
            'phone_no1' => $request->phoneNo1,
            'phone_no2' => $request->phoneNo2 ?? '',
            'latitude' => $latLong->latitude ?? '0',
            'longitude' => $latLong->longitude ?? '0',
            'start_validity' => $dt->toDateString(),
            'end_validity' => $dt->addDay(15),
        );
        $client = $this->client->create($clientInfo);
        
        if(isset($client->id)){

            //Cache API Response
            Redis::set('client-geo-'.$client->id, $response->json());
            $client_user = new ClientUsers();
            $client_user->client_id = $client->id;
            $client_user->first_name = $request->user['firstName'];
            $client_user->last_name = $request->user['lastName'];
            $client_user->email = $request->user['email'];
            $client_user->password = bcrypt($request->user['password']);
            $client_user->phone = $request->user['phone'];
            $client_user->profile_uri = NULL;
            $client_user->last_password_reset = $dt->toDateTimeString();
            $client_user->save();
        }

        return response()->json(['msg' => 'Client register successfully.', 'client' => $client] );
    }

    public function account(Request $request) {
        
        $client = $this->client->getAll();
        return response()->json(new ClientCollection($client));
    }
}
