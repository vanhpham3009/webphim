<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie_View extends Model
{
    use HasFactory;
    protected $table = 'movie_views';
    protected $fillable = [
        'movie_id',
        'created_at',
    ];
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
