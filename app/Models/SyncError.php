<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncError extends Model
{
    protected $table = 'sync_errors';
    protected $fillable = ['date_sync', 'error', 'sync'];
}
