<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'usertype'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pcv_requests(){
        return $this->hasMany("App\PettyCashVoucher", "requested_by", 'id');
    }

    public function pcv_approve(){
        return $this->hasMany("App\PettyCashVoucher", 'approved_by', 'id');
    }

    public function pcv_receive(){
        return $this->hasMany("App\PettyCashVoucher", 'received_by', 'id');
    }
}
