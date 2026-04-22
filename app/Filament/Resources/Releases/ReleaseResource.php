<?php

namespace App\Filament\Resources\Releases;

use App\Filament\Resources\Releases\Pages;
use App\Models\Release;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

use Filament\Schemas\Schema;


use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use BackedEnum;
use UnitEnum;

class ReleaseResource extends Resource {
    protected static ?string $model = Release::class;

    protected static string $resource = ReleaseResource::class;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-rocket-launch";

    protected static string|UnitEnum|null $navigationGroup = "Content";

     // function untuk menghilangkan di sidebar
    // public static function shouldRegisterNavigation(): bool {
    //     return false;
    // }

    protected function mutateFormDataBeforeCreate(array $data): array {

        $data["created_by"] = Auth::id();
        dd($data);
        return $data;
    }

    public static function form(Schema $schema): Schema {
        return $schema->components([
            Section::make("Release Identity")->schema([
                Grid::make(2)->schema([
                    TextInput::make("version")
                        ->label("Nomor Versi")
                        ->placeholder("2.5.0")
                        ->required(),

                    // Select::make("group")
                    //     ->label("Group Sidebar")
                    //     ->options([
                    //         "Version 2.x" => "Version 2.x",
                    //         "Version 1.x" => "Version 1.x",
                    //     ])
                    //     ->required()
                    //     ->default("Version 2.x"),
                ]),

                TextInput::make("title")
                    ->label("Judul Headline")
                    ->placeholder("Major Update & New Features")
                    ->columnSpanFull()
                    ->required(),
            ]),

            Section::make("Content Details")->schema([
                RichEditor::make("intro_text")
                    ->label("Paragraf Pembuka")
                    ->toolbarButtons(["bold", "italic", "link", "bulletList", "redo", "undo"])
                    ->columnSpanFull(),
            ]),
            Section::make("Fitur")->schema([
                Repeater::make("features")
                    ->label("Daftar Fitur (Key Features)")
                    ->schema([
                        Grid::make(1)->schema([
                            Select::make("icon")
                                ->options([
                                    "o-bolt" => "Petir (Performance)",
                                    "o-rectangle-stack" => "Tumpukan (Library)",
                                    "o-users" => "User (Collaboration)",
                                    "o-shield-check" => "Tameng (Security)",
                                    "o-bug-ant" => "Kutu (Bug Fix)",
                                    "o-code-bracket" => "Code (API)",
                                ])
                                ->required()
                                ->searchable(),

                            TextInput::make("title")->label("Judul Fitur")->required(),
                        ]),

                        Textarea::make("description")
                            ->label("Deskripsi Singkat")
                            ->rows(2)
                            ->required(),
                    ])
                    ->collapsible()
                    ->grid(2)
                    ->columnSpanFull(),
            ]),

            Section::make("Publishing")->schema([
                DatePicker::make("published_at")->label("Tanggal Rilis")->default(now()),

                Toggle::make("is_visible")
                    ->label("Tampilkan ke Publik?")
                    ->default(true)
                    ->onColor("success"),
            ]),
        ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("version")
                    ->searchable()
                    ->sortable()
                    ->weight("bold"),
                Tables\Columns\TextColumn::make("title")->limit(30)->searchable(),
                Tables\Columns\TextColumn::make("group")->badge(),
                Tables\Columns\TextColumn::make("published_at")->date()->sortable(),
                Tables\Columns\ToggleColumn::make("is_visible")->label("Visible"),
                // Hidden::make("created_by")->default(fn() => Auth::id()),
            ])
            ->actions([

                EditAction::make(),
            ])
            ->bulkActions([

                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array {
        return [];
    }

    public static function getPages(): array {
        return [
            "index" => Pages\ListReleases::route("/"),
            "create" => Pages\CreateRelease::route("/create"),
            "edit" => Pages\EditRelease::route("/{record}/edit"),
        ];
    }
}
