<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'author',
        'description',
        'published_year',
        'stock',
        'genre_id',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'stock' => 'integer',
        'genre_id' => 'integer',
    ];


    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function scopeAuthor($query, $author)
    {
        if ($author)
            return $query->where('author', 'ilike', "%$author%");
    }

    public function scopeTitle($query, $title)
    {
        if ($title)
            return $query->where('title', 'ilike', "%$title%");
    }


    public function scopeGenre($query, $genre)
    {
        if ($genre)
            return $query->whereHas('genre', function ($query) use ($genre) {
                $query->where('name', 'ilike', "%$genre%");
            });
    }
}
