<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->workspace = sys_get_temp_dir() . '/laravel-prinsipper-test-' . uniqid();
    File::ensureDirectoryExists($this->workspace);

    $this->app->setBasePath($this->workspace);

    config()->set('laravel-prinsipper.docs_target', 'docs/laravel-prinsipper.md');
    config()->set('laravel-prinsipper.reference_targets', ['AGENTS.md', 'CLAUDE.md']);
});

afterEach(function () {
    if (isset($this->workspace) && is_dir($this->workspace)) {
        File::deleteDirectory($this->workspace);
    }
});

it('writes full docs to docs_target', function () {
    $this->artisan('laravel-prinsipper:sync')->assertSuccessful();

    $docs = $this->workspace . '/docs/laravel-prinsipper.md';
    expect(File::exists($docs))->toBeTrue();
    expect(File::get($docs))->not->toBeEmpty();
});

it('writes reference block into agent files, not full content', function () {
    $this->artisan('laravel-prinsipper:sync')->assertSuccessful();

    $agents = File::get($this->workspace . '/AGENTS.md');

    expect($agents)
        ->toContain('<!-- LARAVEL-PRINSIPPER:START -->')
        ->toContain('<!-- LARAVEL-PRINSIPPER:END -->')
        ->toContain('docs/laravel-prinsipper.md');
});

it('replaces existing section between markers', function () {
    $agentsPath = $this->workspace . '/AGENTS.md';
    File::put($agentsPath, <<<MD
    # My agent

    Existing content.

    <!-- LARAVEL-PRINSIPPER:START -->
    ## Old section

    Old body.
    <!-- LARAVEL-PRINSIPPER:END -->

    Trailing.
    MD);

    $this->artisan('laravel-prinsipper:sync')->assertSuccessful();

    $agents = File::get($agentsPath);

    expect($agents)
        ->toContain('# My agent')
        ->toContain('Existing content.')
        ->toContain('Trailing.')
        ->not->toContain('Old body.');
});

it('appends section when markers are missing', function () {
    $agentsPath = $this->workspace . '/AGENTS.md';
    File::put($agentsPath, "# Agent\n\nSome text.\n");

    $this->artisan('laravel-prinsipper:sync')->assertSuccessful();

    $agents = File::get($agentsPath);

    expect($agents)
        ->toStartWith("# Agent")
        ->toContain('<!-- LARAVEL-PRINSIPPER:START -->')
        ->toContain('<!-- LARAVEL-PRINSIPPER:END -->');
});
