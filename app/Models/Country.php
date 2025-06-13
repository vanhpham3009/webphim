<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];
    public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
