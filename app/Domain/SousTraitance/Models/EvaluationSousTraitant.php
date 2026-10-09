<?php
namespace App\Domain\SousTraitance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSousTraitant extends Model
{
    use HasFactory;

    protected $table = 'evaluation_sous_traitants';
    protected $guarded = ['id'];
    protected $casts = [
        'date_evaluation' => 'date',
        'note_qualite'    => 'integer',
        'note_delai'      => 'integer',
        'note_securite'   => 'integer',
    ];

    public function soustraitant() { return $this->belongsTo(Soustraitant::class, 'sous_traitant_id'); }
    public function projet()       { return $this->belongsTo(\App\Domain\Execution\Models\Projet::class); }
    public function evaluePar()    { return $this->belongsTo(\App\Domain\Socle\Models\User::class, 'evalue_par'); }

    public function getNoteGlobaleAttribute(): float
    {
        return round(($this->note_qualite + $this->note_delai + $this->note_securite) / 3, 1);
    }

    public function getEstBonneAttribute(): bool
    {
        return $this->note_globale >= 3.5;
    }
}