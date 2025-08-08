<?php

namespace Mortezamasumi\FbPersian;

use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportTesting\Testable;
use Mortezamasumi\FbPersian\Macros\ExportMacroServiceProvider;
use Mortezamasumi\FbPersian\Macros\FormMacroServiceProvider;
use Mortezamasumi\FbPersian\Macros\InfolistMacroServiceProvider;
use Mortezamasumi\FbPersian\Macros\PsortMacroServiceProvider;
use Mortezamasumi\FbPersian\Macros\TableMacroServiceProvider;
use Mortezamasumi\FbPersian\Rules\IranNid;
use Mortezamasumi\FbPersian\Testing\TestsFbPersian;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FbPersianServiceProvider extends PackageServiceProvider
{
    public static string $name = 'fb-persian';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->app->register(ExportMacroServiceProvider::class);
        $this->app->register(FormMacroServiceProvider::class);
        $this->app->register(InfolistMacroServiceProvider::class);
        $this->app->register(TableMacroServiceProvider::class);
        $this->app->register(PsortMacroServiceProvider::class);
    }

    public function packageBooted(): void
    {
        Testable::mixin(new TestsFbPersian);
    }
}
