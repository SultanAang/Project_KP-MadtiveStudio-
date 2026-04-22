<?php

namespace App\Filament\Widgets;

use App\Models\BugReport;
use App\Models\Release; 
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        
        $project = Filament::getTenant();

        return [
            Stat::make('Dokumen Ditolak', $project->releases()->where('is_approve', 'rejected')->count())
                ->description('Perlu revisi segera')
                ->color('danger')
                ->icon('heroicon-m-x-circle'),

            Stat::make('Dokumen Tayang', $project->releases()->where('is_approve', 'published')->count())
                ->description('Sudah disetujui')
                ->color('success')
                ->icon('heroicon-m-check-circle'),

            Stat::make('Bug Reports', $project->bugReports()->where('status', 'pending')->count())
                ->description('Laporan baru dari client')
                ->color('warning')
                ->icon('heroicon-m-bug-ant'),
        ];
    }
}