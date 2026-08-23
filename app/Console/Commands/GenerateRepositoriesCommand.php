<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateRepositoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositories
                            {--model= : Générer pour un modèle spécifique}
                            {--force : Forcer l\'écrasement des fichiers existants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les interfaces et repositories pour tous les modèles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->option('model');
        $force = $this->option('force');

        // Récupérer tous les modèles
        $models = $this->getModels($modelName);

        if (empty($models)) {
            $this->error('Aucun modèle trouvé !');
            return Command::FAILURE;
        }

        $this->info("📦 Génération des interfaces et repositories...\n");

        $generated = 0;
        $skipped = 0;

        foreach ($models as $model) {
            $modelClass = $model['class'];
            $modelName = $model['name'];
            $modelPath = $model['path'];

            $interfaceName = $modelName . 'RepositoryInterface';
            $repositoryName = $modelName . 'Repository';

            // Générer l'interface
            $interfacePath = app_path("Repositories/Interfaces/{$interfaceName}.php");
            $interfaceCreated = $this->generateInterface($interfacePath, $interfaceName, $modelName, $force);

            // Générer le repository
            $repositoryPath = app_path("Repositories/Eloquent/{$repositoryName}.php");
            $repositoryCreated = $this->generateRepository($repositoryPath, $repositoryName, $interfaceName, $modelName, $force);

            if ($interfaceCreated || $repositoryCreated) {
                $generated++;
                $this->line("✅ {$modelName} : Interface et Repository générés");
            } else {
                $skipped++;
                $this->line("⏭️  {$modelName} : Fichiers déjà existants (ignorés)");
            }
        }

        $this->newLine();
        $this->info("✨ Génération terminée !");
        $this->line("   📁 {$generated} modèle(s) généré(s)");
        if ($skipped > 0) {
            $this->line("   ⏭️  {$skipped} modèle(s) ignoré(s) (fichiers existants)");
        }

        // Proposer d'enregistrer les bindings
        if ($this->confirm('Voulez-vous enregistrer les bindings dans le RepositoryServiceProvider ?')) {
            $this->registerBindings($models);
        }

        return Command::SUCCESS;
    }

    /**
     * Récupère tous les modèles du projet
     */
    private function getModels(?string $specificModel = null): array
    {
        $models = [];
        $modelPaths = [
            app_path('Models'),
            app_path('Models/Contracts'),
        ];

        if ($specificModel) {
            $modelClass = 'App\\Models\\' . $specificModel;
            if (class_exists($modelClass)) {
                return [[
                    'name' => $specificModel,
                    'class' => $modelClass,
                    'path' => $this->getModelPath($modelClass)
                ]];
            }
            return [];
        }

        foreach ($modelPaths as $path) {
            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);
            foreach ($files as $file) {
                $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $namespace = $this->getNamespaceFromPath($path, $file);
                $fullClass = $namespace . '\\' . $className;

                if (class_exists($fullClass) && $this->isModelClass($fullClass)) {
                    $models[] = [
                        'name' => $className,
                        'class' => $fullClass,
                        'path' => $file->getPathname()
                    ];
                }
            }
        }

        // Trier par nom
        usort($models, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $models;
    }

    /**
     * Vérifie si une classe est un modèle Eloquent
     */
    private function isModelClass(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $reflection = new \ReflectionClass($class);
        if ($reflection->isAbstract()) {
            return false;
        }

        return $reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class);
    }

    /**
     * Récupère le namespace à partir du chemin
     */
    private function getNamespaceFromPath(string $basePath, $file): string
    {
        $relativePath = str_replace($basePath, '', $file->getPath());
        $relativePath = trim($relativePath, DIRECTORY_SEPARATOR);
        $namespace = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

        // Déterminer le namespace de base
        if (strpos($basePath, 'Models') !== false) {
            return 'App\\Models' . ($namespace ? '\\' . $namespace : '');
        }

        return 'App\\' . ($namespace ? str_replace('/', '\\', $namespace) : '');
    }

    /**
     * Récupère le chemin complet d'un modèle
     */
    private function getModelPath(string $class): string
    {
        $reflection = new \ReflectionClass($class);
        return $reflection->getFileName();
    }

    /**
     * Génère le fichier d'interface
     */
    private function generateInterface(string $path, string $interfaceName, string $modelName, bool $force): bool
    {
        if (File::exists($path) && !$force) {
            return false;
        }

        $content = $this->getInterfaceTemplate($interfaceName, $modelName);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);

        return true;
    }

    /**
     * Génère le fichier repository
     */
    private function generateRepository(string $path, string $repositoryName, string $interfaceName, string $modelName, bool $force): bool
    {
        if (File::exists($path) && !$force) {
            return false;
        }

        $content = $this->getRepositoryTemplate($repositoryName, $interfaceName, $modelName);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);

        return true;
    }

    /**
     * Template de l'interface
     */
    private function getInterfaceTemplate(string $interfaceName, string $modelName): string
    {
        return <<<PHP
<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseRepositoryInterface;

/**
 * Interface {$interfaceName}
 *
 * @package App\Repositories\Interfaces
 */
interface {$interfaceName} extends BaseRepositoryInterface
{
    /**
     * Ajoutez ici les méthodes spécifiques pour le modèle {$modelName}
     *
     * Exemple :
     * public function findByEmail(string \$email): ?{$modelName};
     * public function getActiveUsers(): Collection;
     */
}
PHP;
    }

    /**
     * Template du repository
     */
    private function getRepositoryTemplate(string $repositoryName, string $interfaceName, string $modelName): string
    {
        return <<<PHP
<?php

namespace App\Repositories\Eloquent;

use App\Models\\{$modelName};
use App\Repositories\Interfaces\\{$interfaceName};
use App\Repositories\Eloquent\BaseRepository;

/**
 * Class {$repositoryName}
 *
 * @package App\Repositories\Eloquent
 */
class {$repositoryName} extends BaseRepository implements {$interfaceName}
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return {$modelName}::class;
    }

    /**
     * Ajoutez ici les implémentations spécifiques pour le modèle {$modelName}
     *
     * Exemple :
     * public function findByEmail(string \$email): ?{$modelName}
     * {
     *     return \$this->activeQuery()->where('email', \$email)->first();
     * }
     */
}
PHP;
    }

    /**
     * Enregistre les bindings dans le RepositoryServiceProvider
     */
    private function registerBindings(array $models): void
    {
        $providerPath = app_path('Providers/RepositoryServiceProvider.php');

        if (!File::exists($providerPath)) {
            $this->createRepositoryServiceProvider();
        }

        $bindings = [];
        foreach ($models as $model) {
            $name = $model['name'];
            $bindings[] = "        \$this->app->bind({$name}RepositoryInterface::class, {$name}Repository::class);";
        }

        $content = File::get($providerPath);
        $registerMethod = 'public function register(): void';
        $newContent = str_replace(
            $registerMethod . "\n    {\n",
            $registerMethod . "\n    {\n" . implode("\n", $bindings) . "\n",
            $content
        );

        File::put($providerPath, $newContent);
        $this->info('✅ Bindings enregistrés dans RepositoryServiceProvider');
    }

    /**
     * Crée le RepositoryServiceProvider s'il n'existe pas
     */
    private function createRepositoryServiceProvider(): void
    {
        $path = app_path('Providers/RepositoryServiceProvider.php');
        $content = <<<'PHP'
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Les bindings seront ajoutés automatiquement
    }

    public function boot(): void
    {
        //
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info('✅ RepositoryServiceProvider créé');
    }
}
