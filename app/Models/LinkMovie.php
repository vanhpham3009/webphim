<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkMovie extends Model
{
    use HasFactory;
    protected $table = 'linkmovies';
    protected $fillable = [
        'title',
        'description',
        'status'
    ];
    public $timestamps = false;
}
