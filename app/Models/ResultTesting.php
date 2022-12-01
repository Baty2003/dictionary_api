<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultTesting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_dictionary',
        'count_words',
        'count_true',
        'count_false',
        'time_testing_seconds',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
