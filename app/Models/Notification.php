<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['reached','readed','message_id'];
    public function message(){
        $this->hasMany('\Models\Message','message_id','id');
    }
    use HasFactory;
}
