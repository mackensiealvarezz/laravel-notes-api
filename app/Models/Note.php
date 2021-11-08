<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['id', 'user_id', 'title', 'note'];

    /**
     * Get the user that the note belongs to
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
