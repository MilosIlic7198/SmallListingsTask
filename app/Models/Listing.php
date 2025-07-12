<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'condition',
        'image_path',
        'phone',
        'location',
    ];

    /**
     * Get the user who owns the listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category this listing belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
