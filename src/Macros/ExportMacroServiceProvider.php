<?php

namespace Mortezamasumi\FbPersian\Macros;

use Closure;
use Filament\Actions\Exports\ExportColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Mortezamasumi\FbPersian\Facades\FbPersian;

/**
 * Interface declaring Table macros for IDE support
 *
 * @method static Column jDate(string|Closure|null $Tableat, ?string $timezone) jDate apply
 * @method static Column jDateTime(string|Closure|null $Tableat, ?string $timezone, bool|Closure $onlyDate) jDateTime apply
 * @method static ExportColumn localeDigit(?string $forceLocale) current locale apply
 */
interface ExportMacrosInterface {}

class ExportMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        ExportColumn::macro('jDate', function (string|Closure|null $format = null, ?string $timezone = null, ?string $forceLocale = null): ExportColumn {
            /** @var ExportColumn $this */
            $this->jDateTime($format, $timezone, $forceLocale, true);

            return $this;
        });

        ExportColumn::macro('jDateTime', function (string|Closure|null $format = null, ?string $timezone = null, ?string $forceLocale = null, bool|Closure $onlyDate = false): ExportColumn {
            /** @var ExportColumn $this */
            $this->formatStateUsing(static function (ExportColumn $column, Model $record, $state) use ($format, $timezone, $forceLocale, $onlyDate): ?string {
                if (blank($state)) {
                    return null;
                }

                $format = $column->evaluate($format, ['record' => $record, 'state' => $state]);
                $onlyDate = $column->evaluate($onlyDate, ['record' => $record, 'state' => $state]);
                $format ??= ($onlyDate ? __('fb-persian::fb-persian.date_format.simple') : __('fb-persian::fb-persian.date_format.time_simple'));

                return FbPersian::jDateTime($format, $state, $timezone, $forceLocale);
            });

            return $this;
        });

        ExportColumn::macro('localeDigit', function (?string $forceLocale = null): ExportColumn {
            /** @var ExportColumn $this */
            $this->formatStateUsing(static fn (mixed $state) => in_array(gettype($state), ['integer', 'double', 'string']) ? FbPersian::digit($state, $forceLocale) : $state);

            return $this;
        });

        ExportColumn::mixin(new class implements ExportMacrosInterface {});
    }
}
