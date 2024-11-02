<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['note_id', 'user_ip', 'opened_at'];
    // protected $dates = ['opened_at'];
    // protected $casts = ['opened_at' => 'datetime'];

    public function note(){
        return $this->belongsTo(Note::class);
    }
}
