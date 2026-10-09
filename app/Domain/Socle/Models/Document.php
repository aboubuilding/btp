<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $table = 'documents';
    protected $guarded = ['id'];
    protected $casts = ['etat' => 'integer'];

    // ==================== RELATIONS ====================
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ==================== HELPERS ====================
    public function getTailleHumaineAttribute(): string
    {
        $bytes = (int) $this->taille;
        $units = ['o', 'Ko', 'Mo', 'Go'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getEstImageAttribute(): bool
    {
        return str_starts_with($this->mime ?? '', 'image/');
    }

    public function getEstPdfAttribute(): bool
    {
        return $this->mime === 'application/pdf';
    }

    public function getUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }
}