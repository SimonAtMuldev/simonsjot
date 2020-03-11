<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $dates = ['dob'];

    public function path()
    {
        return url('/contacts/' . $this->id);
    }
    public function setDobAttribute($dob)
    {
        $this->attributes['dob'] = Carbon::parse($dob);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
