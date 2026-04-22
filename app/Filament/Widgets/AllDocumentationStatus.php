<?php

namespace App\Filament\Widgets;

use App\Models\DocumentationSummary;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AllDocumentationStatus extends BaseWidget {
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = "full"; // Lebar penuh

    public function table(Table $table): Table {
        return $table
            ->heading("Status Semua Dokumentasi")
            ->query(
                // Filter berdasarkan Project Aktif
                DocumentationSummary::query()->where("project_id", Filament::getTenant()->id),
            )
            ->defaultSort("updated_at", "desc")
            ->columns([
                // Kolom TYPE (Release, FAQ, dll)
                Tables\Columns\TextColumn::make("type")->label("Tipe")->badge()->color(
                    fn(string $state): string => match ($state) {
                        "Release" => "info",
                        "Roadmap" => "primary",
                        "FAQ" => "warning",
                        "Panduan" => "gray",
                        default => "gray",
                    },
                ),

                Tables\Columns\TextColumn::make("title")
                    ->label("Judul / Pertanyaan")
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make("is_approve")->label("Status")->badge()->color(
                    fn(string $state): string => match ($state) {
                        "draft" => "gray",
                        "review" => "warning",
                        "published" => "success",
                        "rejected" => "danger",
                    },
                ),

                Tables\Columns\TextColumn::make("rejection_note")
                    ->label("Catatan Reviewer")
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 0 ? $state : null;
                    }),
                // ->visible(fn($record) => $record->is_approve === "rejected"),

                Tables\Columns\TextColumn::make("updated_at")->dateTime()->label("Update Terakhir"),
            ])
            ->filters([
                // Filter agar Tim Dokumentasi bisa pilih mau lihat tipe apa
                Tables\Filters\SelectFilter::make("type")->options([
                    "Release" => "Release",
                    "Roadmap" => "Roadmap",
                    "FAQ" => "FAQ",
                    "Panduan" => "Panduan",
                ]),

                Tables\Filters\SelectFilter::make("is_approve")
                    ->label("Status Review")
                    ->options([
                        "rejected" => "Ditolak (Perlu Revisi)",
                        "published" => "Tayang",
                        "review" => "Sedang Review",
                    ]),
            ]);
    }
}
