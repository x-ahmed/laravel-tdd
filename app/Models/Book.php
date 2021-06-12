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

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function checkout(User $user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now(),
            // 'checked_in_at' => null,
        ]);
    }

    public function checkin(User $user)
    {
        $this->reservations()
            ->whereUserId($user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->get()->pipe(function ($reservations) {
                if (\is_null($reservations->first())) {
                    throw new \Exception;
                }
                return $reservations->first();
            })
            ->update([
                // 'checked_out_at' => null,
                'checked_in_at' => now(),
            ]);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
}
