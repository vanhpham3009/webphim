<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $table = 'episodes';
    protected $fillable = [
        'movie_id',
        'episode',
        'linkphim',
        'server',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function linkmovie()
    {
        return $this->belongsTo(LinkMovie::class, 'server');
    }
}
