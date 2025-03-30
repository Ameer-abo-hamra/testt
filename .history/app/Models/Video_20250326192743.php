<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ["id", "title", "original_name", "disk", "path", "converted_for_downloading_at", "converted_for_streaming_at"]
    ;

}
