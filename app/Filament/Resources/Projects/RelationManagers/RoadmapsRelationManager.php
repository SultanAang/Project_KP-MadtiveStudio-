<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;

class RoadmapsRelationManager extends RelationManager
{
    protected static string $relationship = 'roadmaps';

    public function form(Schema $schema): Schema {
        return $schema->components([
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

    public function table(Table $table): Table {
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
}
