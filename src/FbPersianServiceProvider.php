<?php

namespace Mortezamasumi\FbPersian;

use Livewire\Features\SupportTesting\Testable;
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

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Testing
        Testable::mixin(new TestsFbPersian);
    }
}
