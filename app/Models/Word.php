<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'english',
        'russian',
        'transcription',
        'dictionary_id'
    ];

    public function dictionary(){
        return $this->belongsTo(Dictionary::class);
    }

    public function wordError(){
        return $this->hasOne(ErrorWord::class);
    }
}
