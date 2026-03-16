<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{ 
    protected $table = 'tariffs';

    protected $fillable = [
        'page_id',
        'name',
        'price',
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

    public function tariff_list()
    {
        return $this->hasMany(TariffList::class);
    }

}
