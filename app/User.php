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
        'name', 'email', 'password', 'usertype', 'status', 'last_deactivated'
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

    public function accessedPA(){
        return $this->hasMany('App\AccessedPrimaryAccounts', 'user_id', 'id');
    }

    public function accessedSA(){
        return $this->hasMany('App\AccessedSecondaryAccounts', 'user_id', 'id');
    }

    public function accessedTA(){
        return $this->hasMany('App\AccessedTertiaryAccounts', 'user_id', 'id');
    }

    public function approvedRequestOfAccessPA(){
        return $this->hasMany('App\AccessedPrimaryAccounts', 'approved_by', 'id');
    }

    public function approvedRequestOfAccessSA(){
        return $this->hasMany('App\AccessedSecondaryAccounts', 'approved_by', 'id');
    }

    public function approvedRequestOfAccessTA(){
        return $this->hasMany('App\AccessedTertiaryAccounts', 'approved_by', 'id');
    }

    public function otherTransactions(){
        return $this->hasMany('App\OtherTransactions', 'user_id', 'id');
    }

    public function mrfs(){
        return $this->hasMany('App\MaterialRequisitionForms', 'requested_by', 'id');
    }
}
