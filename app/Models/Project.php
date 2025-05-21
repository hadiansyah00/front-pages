<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Project extends Model
{
    // use InteractsWithMedia;

    protected $fillable = [
        'judul', 'slug', 'deskripsi', 'kategori_id', 'tahun_proyek', 'status','gambar'
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            $project->slug = Str::slug($project->judul);
        });

        static::updating(function ($project) {
            $project->slug = Str::slug($project->judul);
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}