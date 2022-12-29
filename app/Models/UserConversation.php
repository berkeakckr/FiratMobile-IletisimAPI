<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConversation extends Model
{
    protected $fillable = ['conversation','is_admin','send_message'];
    use HasFactory;

    public function deneme(){
        return $this->hasMany('App\Models\User');
    }
   /* public function deneme2(){
        return $this->hasMany('App\Models\Conversation');
    }*/
}
