<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\Question;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', User::role('siswa')->count()),
            Stat::make('Question Packages', Package::all()->count()),
            Stat::make('Total Questions', Question::all()->count()),
        ];
    }
}
