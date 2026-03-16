<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TariffList extends Model
{
    protected $table = 'tariff_list';

    protected $fillable = [
        'tariff_id',
        'title',
        'description',
    ];

    protected $hidden = [
        'tariff_id',
        'created_at',
        'updated_at'
    ];

    public function tariff()
    {
        return $this->belongsTo(tariff::class);
    }
}