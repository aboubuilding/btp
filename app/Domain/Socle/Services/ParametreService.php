<?php
namespace App\Domain\Socle\Services;

use App\Domain\Socle\Repositories\ParametreRepositoryInterface;
use App\Domain\Socle\Models\Parametre;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ParametreService
{
    private const CACHE_PREFIX = 'btp.param.';
    private const CACHE_TTL    = 3600;

    public function __construct(
        private ParametreRepositoryInterface $repo,
    ) {}

    public function get(string $cle, mixed $default = null): mixed
    {
        return Cache::remember(self::CACHE_PREFIX . $cle, self::CACHE_TTL, function () use ($cle, $default) {
            $parametre = $this->repo->findByCle($cle);
            return $parametre?->valeur_typee ?? $default;
        });
    }

    public function set(string $cle, mixed $valeur): Parametre
    {
        $parametre = $this->repo->setValeur($cle, $valeur);
        Cache::forget(self::CACHE_PREFIX . $cle);
        return $parametre;
    }

    public function oublier(string $cle): void
    {
        Cache::forget(self::CACHE_PREFIX . $cle);
    }

    public function toutReinitialiser(): void
    {
        foreach ($this->repo->all() as $parametre) {
            Cache::forget(self::CACHE_PREFIX . $parametre->cle);
        }
    }

    public function parGroupe(string $prefixe): Collection
    {
        return $this->repo->parGroupe($prefixe);
    }

    public function getTauxTva(): float
    {
        return (float) $this->get('tva.taux', 18);
    }

    public function getTauxRetenueGarantie(): float
    {
        return (float) $this->get('rg.taux_default', 5);
    }

    public function getTauxAvance(): float
    {
        return (float) $this->get('avance.taux_default', 15);
    }

    public function getSeuilValidationDG(string $type = 'bc'): float
    {
        return (float) $this->get("seuil.{$type}_validation_dg", 5_000_000);
    }

    public function getDureeJournaliere(): int
    {
        return (int) $this->get('duree.journaliere_heures', 8);
    }

    public function getCotisationsSalariales(): float
    {
        return (float) $this->get('cotisations.salariales', 5);
    }

    public function getCotisationsPatronales(): float
    {
        return (float) $this->get('cotisations.patronales', 16);
    }

    public function getTauxImpot(): float
    {
        return (float) $this->get('impot.taux', 10);
    }
}