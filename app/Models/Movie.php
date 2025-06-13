<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $table = 'movies';
    protected $fillable = [
        'title',
        'original_title',
        'slug',
        'duration',
        'episode_number',
        'tags',
        'description',
        'image',
        'season',
        'category_id',
        'genre_id',
        'slug',
        'country_id',
        'status',
        'is_hot',
        'resolution',
        'caption',
        'year',
        'created_at',
        'updated_at',
        'trailer',
    ];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    public function episode()
    {
        return $this->hasMany(Episode::class);
    }
    public function movie_genre()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }
}
