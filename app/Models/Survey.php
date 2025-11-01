<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function answer(){
        return $this->hasMany(Answer::class);
    }
}
