<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zip',
        'phone_no1',
        'phone_no2',
    ];

    protected $appends = [
        'all_user'
    ];

    public function clientUsers()
    {
        return $this->hasMany(ClientUsers::class, 'client_id', 'id');
    }

    public function getAllUserAttribute()
    {
        return array (
            'all' => $this->hasMany(ClientUsers::class, 'client_id', 'id')->count(),
            'active' => $this->hasMany(ClientUsers::class, 'client_id', 'id')->whereStatus('Active')->count(),
            'inactive' => $this->hasMany(ClientUsers::class, 'client_id', 'id')->whereStatus('Inactive')->count()
        );
        
    }
}
