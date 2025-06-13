<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    protected $table = 'genres';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];
    public $timestamps = false;

    public function movies()
    {
        return $this->belongsTo(Movie::class);
    }
}
