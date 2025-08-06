<?php

namespace Mortezamasumi\FbPersian\Macros;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Mortezamasumi\FbPersian\Facades\FbPersian;

/**
 * Interface declaring Collection psort macro for IDE support
 *
 * @method static Column psort(?string $forceLocale) current locale apply
 */
interface PsortMacrosInterface {}

class PsortMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var Collection $this */
        /** @disregard */
        Collection::macro(
            'psort',
            fn (string $field, bool $descending = false) => $this->sortBy([
                fn ($a, $b) => $descending xor strtr(data_get($a, $field), FbPersian::persianConvert()) > strtr(data_get($b, $field), FbPersian::persianConvert())
            ]),
        );

        TextColumn::mixin(new class implements PsortMacrosInterface {});
    }
}
