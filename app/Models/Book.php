<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $append = [
        'show_route',
        'update_route',
        'destroy_route',
    ];

    public function getShowRouteAttribute()
    {
        return route('book.show', [
            'book' => $this,
            'slug' => $this->slug,
        ]);
    }

    public function getUpdateRouteAttribute()
    {
        return route('book.update', [
            'book' => $this,
            'slug' => $this->slug,
        ]);
    }

    public function getDestroyRouteAttribute()
    {
        return route('book.destroy', [
            'book' => $this,
            'slug' => $this->slug,
        ]);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($this->attributes['title']);
    }

    public function setAuthorIdAttribute($value)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $value,
        ]))->id;
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}
