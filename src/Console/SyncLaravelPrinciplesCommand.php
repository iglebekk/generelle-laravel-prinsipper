<?php

namespace Andersiglebekk\LaravelPrinciples\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class SyncLaravelPrinciplesCommand extends Command
{
    protected $signature = 'laravel-prinsipper:sync';

    protected $description = 'Sync Laravel principles into project documentation and AI instruction files';

    public function __construct(private readonly Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $config = config('laravel-prinsipper', []);
        $sourcePath = $this->resolveSourcePath($config['source'] ?? null);

        if (! $this->files->exists($sourcePath)) {
            $this->error("Source file not found: {$sourcePath}");
            return self::FAILURE;
        }

        $targets = $config['targets'] ?? [];
        if ($targets === [] || ! is_array($targets)) {
            $this->error('No targets configured for laravel-prinsipper sync.');
            return self::FAILURE;
        }

        $source = rtrim($this->files->get($sourcePath));
        $sectionHeader = rtrim($config['section_header'] ?? '## Generelle Laravel-prinsipper', "\n");
        $startMarker = $config['start_marker'] ?? '<!-- LARAVEL-PRINSIPPER:START -->';
        $endMarker = $config['end_marker'] ?? '<!-- LARAVEL-PRINSIPPER:END -->';

        $overwriteTargets = $config['overwrite'] ?? [];
        $overwriteResolved = $this->resolveTargets($overwriteTargets);

        foreach ($this->resolveTargets($targets) as $targetPath) {
            $this->syncTarget(
                $targetPath,
                $source,
                $sectionHeader,
                $startMarker,
                $endMarker,
                in_array($targetPath, $overwriteResolved, true),
            );
        }

        $this->info('Laravel principles synced.');

        return self::SUCCESS;
    }

    private function syncTarget(
        string $targetPath,
        string $source,
        string $sectionHeader,
        string $startMarker,
        string $endMarker,
        bool $overwrite,
    ): void {
        $this->ensureDirectory($targetPath);

        if ($overwrite) {
            $this->files->put($targetPath, $source . "\n");
            $this->line("Updated {$targetPath}");
            return;
        }

        $existing = $this->files->exists($targetPath)
            ? $this->files->get($targetPath)
            : '';

        $section = $sectionHeader . "\n\n" . $source . "\n";
        $updated = $this->replaceSection($existing, $section, $startMarker, $endMarker);

        $this->files->put($targetPath, $updated);
        $this->line("Updated {$targetPath}");
    }

    private function replaceSection(string $existing, string $section, string $startMarker, string $endMarker): string
    {
        $startPos = strpos($existing, $startMarker);
        $endPos = strpos($existing, $endMarker);

        $wrappedSection = $startMarker . "\n" . $section . $endMarker;

        if ($startPos === false || $endPos === false || $endPos < $startPos) {
            $trimmed = rtrim($existing);
            if ($trimmed !== '') {
                $trimmed .= "\n\n";
            }

            return $trimmed . $wrappedSection . "\n";
        }

        $before = substr($existing, 0, $startPos);
        $after = substr($existing, $endPos + strlen($endMarker));

        return rtrim($before) . "\n\n" . $wrappedSection . "\n" . ltrim($after);
    }

    private function resolveSourcePath(?string $source): string
    {
        if (is_string($source) && $source !== '') {
            return $this->resolvePath($source);
        }

        return realpath(__DIR__ . '/../../resources/docs/laravel-prinsipper.md') ?: __DIR__ . '/../../resources/docs/laravel-prinsipper.md';
    }

    /**
     * @param array<int, string> $targets
     * @return array<int, string>
     */
    private function resolveTargets(array $targets): array
    {
        $resolved = [];
        foreach ($targets as $target) {
            if (! is_string($target) || $target === '') {
                continue;
            }
            $resolved[] = $this->resolvePath($target);
        }

        return $resolved;
    }

    private function resolvePath(string $path): string
    {
        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return base_path($path);
    }

    private function isAbsolutePath(string $path): bool
    {
        if (Str::startsWith($path, DIRECTORY_SEPARATOR)) {
            return true;
        }

        return (bool) preg_match('/^[A-Za-z]:\\\\/', $path);
    }

    private function ensureDirectory(string $targetPath): void
    {
        $directory = dirname($targetPath);
        if (! $this->files->isDirectory($directory)) {
            $this->files->ensureDirectoryExists($directory);
        }
    }
}
