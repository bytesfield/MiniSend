<?php

namespace App\Models;

use App\Models\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    public function email()
    {
        return $this->belongsTo(Email::class);
    }
}
