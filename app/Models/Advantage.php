<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    protected $table = 'advantages';
    
    protected $fillable = [
        'page_id',
        'title',
        'description',
    ];  

    protected $hidden = [
        'page_id',
        'created_at',
        'updated_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
