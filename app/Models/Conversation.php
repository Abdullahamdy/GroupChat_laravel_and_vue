<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['last_message_at'];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('read_at')
            ->withTimestamps()
            ->oldest();
    }

    public function others(): BelongsToMany
    {
        return $this->users()->where('user_id', '!=', auth()->id());
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)
            ->oldest();
    }
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest();
    }
}
