<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class SyncMongo extends Model
{
    /** @use HasFactory<\Database\Factories\SyncMongoFactory> */
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'sync_mongos';

    protected $fillable = [
        'data',
        'migrated',
        'error_migrate'
    ];

    protected $casts = [
        'data' => 'array',
        'migrated' => 'boolean',
        'error_migrate' => 'array',
    ];

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }
}
