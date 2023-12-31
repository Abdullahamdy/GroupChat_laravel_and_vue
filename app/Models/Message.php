<?php

namespace App\Models;

use App\Helpers\ImageTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;
    use ImageTrait;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
    public function Conversation()
    {
        return $this->belongsTo(Conversation::class);
    }


}
