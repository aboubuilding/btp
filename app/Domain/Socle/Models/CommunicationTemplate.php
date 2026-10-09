<?php
namespace App\Domain\Socle\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationTemplate extends Model
{
    protected $table = 'communication_templates';
    protected $guarded = ['id'];
    protected $casts = [
        'variables' => 'array',
        'etat'      => 'integer',
    ];

    /**
     * Remplace les variables {{ var }} dans le template.
     */
    public function render(array $data): array
    {
        $sujet = $this->sujet_template;
        $corps = $this->corps_template;

        foreach ($data as $key => $value) {
            $sujet = str_replace('{{ ' . $key . ' }}', $value, $sujet);
            $corps = str_replace('{{ ' . $key . ' }}', $value, $corps);
        }

        return ['sujet' => $sujet, 'corps' => $corps];
    }

    public function scopeActif($q)
    {
        return $q->where('etat', 1);
    }
}