<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id', 'access_token', 'is_opened'];

    public function user(){
        return $this->belongsTo(User::class); // Assuming User model has a foreign key 'user_id'    
    }

    // public function getAccessTokenAttribute(){
    //     return $this->access_token; // Returns the access token
    // }

    // public function log(){
    //     return $this->hasOne(Log::class);
    // }

    public static function boot(){
        parent::boot();

        static::creating(function($note){
            $note->access_token = Str::random(32); // Generates a random 32-character token
        });
    }
}
