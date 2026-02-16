<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_title',
        'post_description',
        'post_status',
        'author_id',
        'image',
        'updated_at'
    ];


    // Slugging route model binding to use post_title instead of id
    public function getRouteKeyName()
    {
        return 'id';
    }
    // Status returns active/inactive
      public function getStatusTextAttribute()
    {
        return $this->post_status ? 'active' : 'inactive';
    }

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }
}
