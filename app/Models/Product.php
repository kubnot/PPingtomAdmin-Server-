<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \DateTimeInterface;
use App\Models\Vote;
use Auth;
class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'products';

    protected $appends = [
        'photo',
        'media_asset',
    ];

    protected $dates = [
        'news_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'location_id',
        'user_id',
        'is_verified',
        'news_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function subcategories()
    {
        return $this->belongsToMany(ProductSubCategory::class);
    }
    
    public function category()
    {
        return $this->belongsToMany(ProductCategory::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(ProductTag::class);
    }

    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getMediaAssetAttribute()
    {
        return $this->getMedia('media_asset')->last();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function upVotes()
    {
        return $this->votes()->where('type', 'up');
    }

    public function bookmarks()
    {
        return $this->votes()->where('type', 'bookmark');
    }


    public function userLiked()
    {
        return $this->votes()->where([['user_id', Auth::id()],['type', 'up']]);
    }
    public function userbookmarked()
    {
        return $this->votes()->where([['user_id', Auth::id()],['type', 'bookmark']]);
    }

    public function voteCount()
    {
        return $this->upVotes() - $this->downVotes();
    }

}