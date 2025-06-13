<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'rating';
    protected $fillable = ['rating', 'movie_id', 'user_ip'];
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
