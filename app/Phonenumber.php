<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phonenumber extends Model
{
    protected $fillable = [
        'value',
        'user_id',
        'role'
    ];

    /**
     * Returns the related users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
