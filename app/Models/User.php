<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'danisman_id',
        'name',
        'email',
        'password',
        'type',
    ];
   /* protected $appends = array('bolum');

    public static function getBolumAttribute($user_id)
    {
        return UserBolum::where('user_id',$user_id)->join('bolum', 'user_bolum.bolum_id', '=', 'bolum.id')->get(['bolum.bolum_adi']);
    }*/
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_userConversation(){
        return $this->hasMany('App\Models\UserConversation');
    }
}
