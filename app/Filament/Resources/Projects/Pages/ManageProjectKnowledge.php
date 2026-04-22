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
use Filament\Forms;
// use Filament\Schemas\Schema; // [Wajib v5]
use Filament\Schemas\Components\Utilities\Set;


class ManageProjectKnowledge extends ManageRelatedRecords {
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'knowledge';
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-book-open";
    protected static ?string $navigationlabel = "Knowledge";

    public function form(Schema $schema): Schema {
        return $schema->components([
            Forms\Components\TextInput::make("title")
                ->label("Judul Artikel")
                ->required()
                // ->live(onBlur: true) // Deteksi saat selesai ketik
                ->columnSpan('full'),
                // ->afterStateUpdated(
                //     fn(Set $set, ?string $state) => $set("slug", Str::slug($state)),
                // ), // Auto isi Slug

            Forms\Components\TextInput::make("slug")
                ->required()
                ->disabled() // Supaya tidak diubah manual
                ->dehydrated(), // Tetap dikirim ke database meski disabled

            Forms\Components\TextInput::make("category")
                ->label("Kategori")
                ->placeholder("Misal: Tutorial, Akun, Billing"),

            Forms\Components\RichEditor::make("content")->label("Isi Artikel")->columnSpanFull(),
        ]);
    }

   public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime(),
            ])
            ->headerActions([
                CreateAction::make()->slideOver(),
            ])
            ->actions([
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ]);
    }
}
