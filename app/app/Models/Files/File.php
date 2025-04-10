<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'link',
        'file_type',
        'attachmentable_type',
        'attachmentable_id'
    ];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function attachmentable(): MorphTo
    {
        return $this->morphTo();
    }


    public function scopeOfType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeWhereNameLike($query, string $name)
    {
        return $query->where('name', 'LIKE', "%{$name}%");
    }

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

}
