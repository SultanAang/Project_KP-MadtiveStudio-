<?php

namespace App\Filament\Resources\KnowledgeBases;

use App\Filament\Resources\KnowledgeBases\Pages;
use App\Models\KnowledgeBase;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema; // [Wajib v5]
use Filament\Schemas\Components\Utilities\Set;
// Import Tambahan untuk Auto-Slug & Actions
use Illuminate\Support\Str;
// use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use App\Models\Project;

use BackedEnum;
use UnitEnum;

class KnowledgeBaseResource extends Resource {
    protected static ?string $model = KnowledgeBase::class;
    protected static ?string $model1 = Project::class;

     // function untuk menghilangkan di sidebar
    // public static function shouldRegisterNavigation(): bool {
    //     return false;
    // }


    protected static ?string $navigationLabel = "Panduan";

    // perubahan dari knoeledge base ke panduan 
    protected static ?string $modelLabel = "Panduan";
    protected static ?string $pluralModelLabel = "Panduan";

    protected static ?string $slug = "panduan";

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-book-open";
    protected static string|UnitEnum|null $navigationGroup = "Content";

    public static function form(Schema $schema): Schema {
        return $schema->components([
            Forms\Components\TextInput::make("title")
                ->label("Judul Artikel")
                ->required()
                ->live(onBlur: true) 
                ->afterStateUpdated(
                    fn(Set $set, ?string $state) => $set("slug", Str::slug($state)),
                ), // Auto isi Slug

            Forms\Components\TextInput::make("slug")
                ->required()
                ->disabled()
                ->dehydrated(), // supaya dikirim ke database walupun inpus di disable

            Forms\Components\TextInput::make("category")
                ->label("Kategori")
                ->placeholder("Misal: Tutorial, Akun, Billing"),

            Forms\Components\RichEditor::make("content")->label("Isi Artikel")->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("title")->searchable()->limit(50)->weight("bold"),

                TextColumn::make("category")->badge(),

                TextColumn::make("updated_at")->date("d M Y")->label("Terakhir Update"),
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getRelations(): array {
        return [];
    }

    public static function getPages(): array {
        return [
            "index" => Pages\ListKnowledgeBases::route("/"),
            "create" => Pages\CreateKnowledgeBase::route("/create"),
            "edit" => Pages\EditKnowledgeBase::route("/{record}/edit"),
        ];
    }
}
