<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user(){
       return  $this->belongsTo(User::class);
    }
    public function word(){
        return $this->hasMany(Word::class);
    }
}
