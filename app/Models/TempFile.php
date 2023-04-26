<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFile extends Model
{
    use HasFactory;

    protected $table = 'temp_file';

    protected $fillable = [
        'folder',
        'file'
    ];

}
