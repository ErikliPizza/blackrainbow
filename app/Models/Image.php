<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'filename',
    ];
    use HasFactory;
    public function art()
    {
        return $this->belongsTo(Art::class);
    }

}
