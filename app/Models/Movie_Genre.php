<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie_Genre extends Model
{
    use HasFactory;
    protected $table = 'movie_genre';
    protected $fillable = [
        'movie_id',
        'genre_id',
    ];
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
