<?php

namespace App\Filament\Widgets;

use App\Models\BugReport;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Support\Enums\TextSize;

use Filament\Actions\ViewAction; 
use Filament\Infolists\Infolist;
// use Filament\Infolists\Components\Section;
// use Filament\Infolists\Components\Grid;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

// use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
class LatestBugReports extends BaseWidget {
    protected int|string|array $columnSpan = "full";
    protected static ?int $sort = 2;

    public function table(Table $table): Table {
        return $table
            ->query(BugReport::query()->where("project_id", Filament::getTenant()->id))
            ->defaultSort("created_at", "desc")
            ->columns([
                Tables\Columns\TextColumn::make("title")->label("Judul")->limit(30)->searchable(),
                Tables\Columns\TextColumn::make("user.name")
                    ->label("Pelapor")
                    ->icon("heroicon-m-user"),
                Tables\Columns\TextColumn::make("priority")->badge()->color(
                    fn(string $state) => match ($state) {
                        "low" => "info",
                        "medium" => "warning",
                        "high" => "orange",
                        "critical" => "danger",
                        default => "gray", // Fallback aman
                    },
                ),
                Tables\Columns\TextColumn::make("status")
                    ->badge()
                    
                    ->color(
                        fn(string $state) => match ($state) {
                            "resolved" => "success",
                            "process" => "primary",
                            "rejected" => "danger",
                            default => "gray", // Pending / Lainnya
                        },
                    ),
                Tables\Columns\TextColumn::make("created_at")->label("Waktu")->dateTime(),
            ])
            ->actions([
                ViewAction::make()->label("Detail")->modalHeading("Detail Laporan Bug")->infolist(
                    fn($infolist) => $infolist->schema([
                        // 1. Section Informasi Dasar
                        Section::make("Informasi Dasar")
                            ->icon("heroicon-m-information-circle")
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make("title")
                                        ->label("Judul Masalah")
                                        ->columnSpanFull()
                                        ->weight(FontWeight::Bold)
                                        ->size(TextSize::Large),

                                    TextEntry::make("user.name")
                                        ->label("Dilaporkan Oleh")
                                        ->icon("heroicon-m-user"),

                                    TextEntry::make("priority")->label("Prioritas")->badge()->color(
                                        fn(string $state) => match ($state) {
                                            "low" => "info",
                                            "medium" => "warning",
                                            "high" => "orange",
                                            "critical" => "danger",
                                            default => "gray",
                                        },
                                    ),

                                    TextEntry::make("created_at")->label("Waktu Lapor")->dateTime(),
                                ]),
                                TextEntry::make("description")
                                    ->label("Kronologi & Deskripsi") 
                                    ->prose()
                                    ->markdown()
                                    ->columnSpanFull(),

                                //  Bukti Gambar
                                ImageEntry::make("screenshot_path")
                                    ->label("Bukti Screenshot") 
                                    ->height(400)
                                    ->checkFileExistence(true)
                                    ->disk("public")
                                    ->visibility("public")
                                    ->columnSpanFull(),
                            ]),
                    ]),
                ),
                Action::make("updateStatus")
                    ->label("Update Progress")
                    ->icon("heroicon-m-clipboard-document-check")
                    ->color("success")
                    ->modalHeading("Update Status & Beri Catatan")
                    ->modalDescription("Informasi ini akan dilihat oleh klien di halaman mereka.")
                    ->form([
                        Radio::make("status")
                            ->label("Status Pengerjaan")
                            ->options([
                                "pending" => "Pending (Menunggu Antrean)",
                                "process" => "Process (Sedang Dikerjakan)",
                                "resolved" => "Resolved (Sudah Diatasi)",
                                "rejected" => "Rejected (Bukan Bug / Ditolak)",
                            ])
                            ->inline()
                            ->required(),

                        Textarea::make("admin_note")
                            ->label("Catatan untuk Klien (Opsional)")
                            ->placeholder(
                                "Contoh: Sedang kami cek pada browser Chrome versi terbaru...",
                            )
                            ->rows(4),
                    ])
                    // tarik data lama untuk default value
                    ->fillForm(
                        fn(BugReport $record): array => [
                            "status" => $record->status,
                            "admin_note" => $record->admin_note,
                        ],
                    )
                    // Aksi simpan ke database
                    ->action(function (array $data, BugReport $record): void {
                        $record->update([
                            "status" => $data["status"],
                            "admin_note" => $data["admin_note"],
                        ]);

                        Notification::make()
                            ->title("Progress berhasil diperbarui!")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
