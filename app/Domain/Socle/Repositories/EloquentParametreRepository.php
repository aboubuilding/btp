<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Parametre;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EloquentParametreRepository extends BaseRepository implements ParametreRepositoryInterface
{
    protected array $colonnesSearch = ['cle', 'description'];
    protected string $orderBy = 'cle';
    protected string $orderDir = 'asc';

    private const CACHE_PREFIX = 'btp.param.';
    private const CACHE_TTL = 3600;

    public function __construct(Parametre $model)
    {
        parent::__construct($model);
    }

    public function findByCle(string $cle): ?Parametre
    {
        return $this->newQuery()->where('cle', $cle)->first();
    }

    public function getValeur(string $cle, mixed $default = null): mixed
    {
        return Cache::remember(self::CACHE_PREFIX . $cle, self::CACHE_TTL, function () use ($cle, $default) {
            $p = $this->findByCle($cle);
            return $p ? $p->valeur_typee : $default;
        });
    }

    public function setValeur(string $cle, mixed $valeur): Parametre
    {
        $parametre = $this->findByCle($cle);
        $valeurStr = is_array($valeur) ? json_encode($valeur) : (string) $valeur;

        if ($parametre) {
            $parametre->update(['valeur' => $valeurStr]);
        } else {
            $parametre = $this->create(['cle' => $cle, 'valeur' => $valeurStr]);
        }

        Cache::forget(self::CACHE_PREFIX . $cle);
        return $parametre;
    }

    public function parGroupe(string $prefixe): Collection
    {
        return $this->newQuery()
            ->where('cle', 'like', "{$prefixe}.%")
            ->orderBy('cle')
            ->get();
    }

    public function tousActifs(): Collection
    {
        return $this->newQuery()->where('etat', 1)->orderBy('cle')->get();
    }
}