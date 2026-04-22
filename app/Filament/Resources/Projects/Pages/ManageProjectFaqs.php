<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
class ManageProjectFaqs extends ManageRelatedRecords {
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = "faqs";

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-question-mark-circle";

    protected static ?string $navigationLabel = "Faqs";

    public function form(Schema $schema): Schema {
        return $schema->components([
            Forms\Components\TextInput::make("question")
                ->label("Pertanyaan")
                ->required()
                ->columnSpanFull(),

            Forms\Components\RichEditor::make("answer")
                ->label("Jawaban")
                ->required()
                ->columnSpanFull(),

            Forms\Components\Toggle::make("is_visible")->label("Tampilkan?")->default(true),
        ]);
    }

    public function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("question")->limit(50)->searchable(),

                TextColumn::make("category")->badge(), // Menampilkan kategori seperti lencana/label warna

                ToggleColumn::make("is_visible")->label("Aktif"),
            ])
            ->actions([EditAction::make(), DeleteBulkAction::make()]);
        // TIPS: Jika di Filament v5 'actions' tidak muncul,
        // coba ganti menjadi ->recordActions([ ... ]) sesuai snippet dokumentasi.
    }
}
