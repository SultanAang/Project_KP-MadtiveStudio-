<?php


namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// use Filament\Actions\Action;
// use Filament\Actions\BulkActionGroup;
// use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
// use Filament\Tables\Actions\EditAction;
use Filament\Tables\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
class FaqResource extends Resource {
    protected static ?string $model = Faq::class;

    // function untuk menghilangkan di sidebar
    // public static function shouldRegisterNavigation(): bool {
    //     return false;
    // }

  
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-question-mark-circle";
    protected static string|UnitEnum|null $navigationGroup = "Content";

    protected static ?string $tenantOwnershipRelationshipName = 'project';

    public static function form(Schema $schema): Schema {
        return $schema->components([
            Forms\Components\TextInput::make("question")
                ->label("Pertanyaan")
                ->required()
                ->columnSpanFull(),

            Forms\Components\RichEditor::make("answer")
                ->label("Jawaban")
                ->required()
                ->columnSpanFull(),

            Forms\Components\TextInput::make("category")
                ->label("Kategori")
                ->placeholder("Contoh: Umum, Teknis"),

            Forms\Components\Toggle::make("is_visible")->label("Tampilkan?")->default(true),
        ]);
    }


    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("question")->limit(50)->searchable(),

                TextColumn::make("category")->badge(), 

                ToggleColumn::make("is_visible")->label("Aktif"),
            ])
            ->actions([EditAction::make(), DeleteBulkAction::make()]);
    }

    public static function getRelations(): array {
        return [];
    }

    public static function getPages(): array {
        return [
            "index" => Pages\ListFaqs::route("/"),
            "create" => Pages\CreateFaq::route("/create"),
            "edit" => Pages\EditFaq::route("/{record}/edit"),
        ];
    }
}
