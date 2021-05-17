<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\ClientUsers;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Http;

class APIController extends BaseController
{
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
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client = new Clients();
        $client->client_name = $request->name;
        $client->address1 = $request->address1;
        $client->address2 = $request->address2;
        $client->city = $request->city;
        $client->state = $request->state;
        $client->country = $request->country;
        $client->zip = $request->zipCode;
        $client->phone_no1 = $request->phoneNo1;
        $client->phone_no2 = $request->phoneNo2 ?? '';

        // CALL GEO API TO GET LAT-LONG
        $key = config('app.google_api_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?key='.$key.'&address='.$request->address1.' '.$request->address2);
    	$latLong = $response->json();
        \Log::info($key);
        \Log::info($latLong);

        $client->latitude = $latLong->latitude ?? '0';
        $client->longitude = $latLong->longitude ?? '0';

        $dt = Carbon::now();
        $client->start_validity = $dt->toDateString();
        $client->end_validity = $dt->addDay(15);
        $client->save();

        if(isset($client->id)){
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

        return $this->sendResponse($client, 'Client register successfully.');
    }

    public function account(Request $request) {
        $client = Clients::select('id', 'client_name as name', 'address1', 'address2', 'city', 'state', 'country', 'zip as zipCode', 'latitude', 'longitude', 'phone_no1 as phoneNo1', 'phone_no2 as phoneNo2', 'start_validity as startValidity', 'end_validity as endValidity', 'status', 'created_at as createdAt', 'updated_at as updatedAt')
        ->paginate(2);

        return $this->sendResponse(new ClientResource($client), 'Client data.');
    }
}
