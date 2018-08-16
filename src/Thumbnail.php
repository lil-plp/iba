<?php

namespace PirateLP\IBA;

use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    //
    protected $fillable = ['name', 'path', 'photographer', 'link'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function thumbnailable()
    {
        return $this->morphTo();
    }
}
