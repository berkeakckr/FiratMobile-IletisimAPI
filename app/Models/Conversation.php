<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['title','description','type','everyone_chat'];
    use HasFactory;
    public function messages(){
        return $this->hasMany('App\Models\Message');
    }
    public function messageCount(){
        return $this->hasMany('App\Models\Message','conversation_id','id')->count();
    }
    public function get_Users(){
        return $this->hasMany('App\Models\UserConversation','conversation_id','id');
    }

}
