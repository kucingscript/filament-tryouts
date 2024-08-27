<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\Question;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', function () {
                if (Role::where('name', 'siswa')->exists()) {
                    return User::role('siswa')->count();
                } else {
                    return '0';
                }
            }),
            Stat::make('Question Packages', Package::all()->count()),
            Stat::make('Total Questions', Question::all()->count()),
        ];
    }
}
