<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['text','file','conversation_id'];
    public function message(){
        $this->hasMany('\Models\Conversation','conversation_id','id');
    }
    use HasFactory;
}
