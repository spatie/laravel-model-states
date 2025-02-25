<?php

namespace Spatie\ModelStates;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface HasStatesContract
{
    public static function bootHasStates(): void;

    public function initializeHasStates(): void;

    public static function getStates(): Collection;

    public static function getDefaultStates(): Collection;

    public static function getDefaultStateFor(string $fieldName): ?string;

    public static function getStatesFor(string $fieldName): Collection;

    public function scopeWhereState(Builder $builder, string $column, $states): Builder;

    public function scopeWhereNotState(Builder $builder, string $column, $states): Builder;

    public function scopeOrWhereState(Builder $builder, string $column, $states): Builder;

    public function scopeOrWhereNotState(Builder $builder, string $column, $states): Builder;
}
