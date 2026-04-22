<?php

namespace App\Filament\Resources\Roadmaps;
use App\Filament\Resources\Roadmaps\Pages;
use App\Models\Roadmap;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use BackedEnum;
use UnitEnum;

class RoadmapResource extends Resource {
    protected static ?string $model = Roadmap::class;

    // function untuk menghilangkan di sidebar
    // public static function shouldRegisterNavigation(): bool {
    //     return false;
    // }

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-map";
    protected static string|UnitEnum|null $navigationGroup = "Content";

    public static function form(Schema $schema): Schema {
        return $schema->components([
            // Forms\Components\Select::make("project_id")
            //     ->relationship("project", "name")
            //     ->searchable()
            //     ->preload()
            //     ->required()
            //     ->label("Milik Project")

            //     ->default(fn() => request()->query("project_id"))
            //     // JIKA MAU DIKUNCI (Supaya gak bisa diganti):
            //     ->disabled(fn() => request()->query("project_id") !== null)

            //     ->dehydrated(),
            Forms\Components\TextInput::make("title")->label("Fitur")->required(),

            Forms\Components\Select::make("status")
                ->options([
                    "planned" => "Direncanakan (Planned)",
                    "in_progress" => "Sedang Dikerjakan",
                    "done" => "Selesai (Released)",
                ])
                ->required()
                ->native(false),

            Forms\Components\DatePicker::make("eta")->label("Estimasi Rilis"),

            Forms\Components\Textarea::make("description")->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make("title")->searchable()->weight("bold"),

                TextColumn::make("status")->badge()->color(
                    fn(string $state): string => match ($state) {
                        "planned" => "gray",
                        "in_progress" => "warning",
                        "done" => "success",
                        default => "gray",
                    },
                ),

                TextColumn::make("eta")->date("d M Y")->label("Estimasi"),
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getRelations(): array {
        return [];
    }

    public static function getPages(): array {
        return [
            "index" => Pages\ListRoadmaps::route("/"),
            "create" => Pages\CreateRoadmap::route("/create"),
            "edit" => Pages\EditRoadmap::route("/{record}/edit"),
        ];
    }
}
