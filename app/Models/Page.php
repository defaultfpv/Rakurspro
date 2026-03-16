<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'type',
        'name',
        'slug',
        'title',
        'description',
        'h1',
        'banner_text',
        'html1',
        'html2',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tariffs()
    {
        return $this->hasMany(Tariff::class);
    }

    public function advantages()
    {
        return $this->hasMany(Advantage::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }


}