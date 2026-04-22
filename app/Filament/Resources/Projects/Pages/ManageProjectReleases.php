<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use BackedEnum;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\MarkdownEditor;


class ManageProjectReleases extends ManageRelatedRecords {
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = "releases"; // Pastikan relasi di Model Project bernama 'releases'

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-rocket-launch";

    protected static ?string $navigationLabel = "Release Notes";

    // PERHATIKAN: Tidak ada kata 'static' di sini
    public function form(Schema $schema): Schema {
        return $schema->schema([
            TextInput::make("version")
                ->label("Versi Rilis") // Contoh: v1.0.2
                ->required()
                ->placeholder("v1.0.0")
                ->columnSpan("full"),

            MarkdownEditor::make("changelog")
                ->label("Catatan Perubahan")
                ->required()
                ->columnSpan("full"),
        ]);
    }

    // PERHATIKAN: Tidak ada kata 'static' di sini juga
    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute("version")
            ->columns([
                TextColumn::make("version")
                    ->label("Versi")
                    ->weight("bold")
                    ->searchable(),

                TextColumn::make("created_at")
                    ->label("Tanggal Rilis")
                    ->date("d M Y")
                    ->sortable(),
            ])
            ->headerActions([
                // Tombol Create meluncur dari samping
                CreateAction::make()->slideOver(),
            ])
            ->actions([
                // Tombol Edit meluncur dari samping
                EditAction::make()->slideOver(),

                DeleteAction::make(),
            ])
            ->groupedBulkActions([DeleteBulkAction::make()]);
    }
}
