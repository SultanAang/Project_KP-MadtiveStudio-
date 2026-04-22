<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages;
// use App\Filament\Resources\Faqs\Pages;
// use App\Filament\Resources\Faqs\FaqResource;
use App\Models\Project;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

use Filament\Pages\SubNavigationPosition;

use Filament\Resources\Pages\Page; 
use BackedEnum;
use UnitEnum;
class ProjectResource extends Resource {
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-briefcase";

     // function untuk menghilangkan di sidebar
    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public static function getRecordSubNavigation(Page $page): array {
        return $page->generateNavigationItems([
            // Halaman Edit Utama
            Pages\EditProject::class,
            // Halaman Tambahan (Sidebar)
            // Pages\ManageProjectReleases::class,
            // Pages\ManageProjectFaqs::class,
            // Pages\ManageProjectRoadmaps::class,
            // Pages\ManageProjectKnowledge::class,
        ]);
    }
    public static function from(Schema $schema): Schema {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make("Informasi Project")
                            ->schema([
                                Forms\Components\Select::make("client_id")
                                    ->relationship("client", "name")
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label("Client Owner"),

                                Forms\Components\TextInput::make("name")
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        fn(string $operation, $state, $set) => $operation ===
                                        "create"
                                            ? $set("slug", \Illuminate\Support\Str::slug($state))
                                            : null,
                                    ),

                                Forms\Components\TextInput::make("slug")
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\Textarea::make("description")->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(["lg" => 2]),

                Group::make()
                    ->schema([
                        Section::make("Status & Media")->schema([
                            Forms\Components\Select::make("status")
                                ->options([
                                    "pending" => "Pending",
                                    "ongoing" => "Ongoing",
                                    "finished" => "Finished",
                                ])
                                ->default("pending")
                                ->required()
                                ->native(false),

                            Forms\Components\DatePicker::make("deadline")->native(false),
                            Forms\Components\FileUpload::make("logo")
                                ->image()
                                ->directory("project-logos")
                                ->imageEditor(),
                        ]),
                    ])
                    ->columnSpan(["lg" => 1]),
            ])
            ->columns(3);
    }
    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("logo")->circular(),

                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable()
                    ->weight("bold")
                    ->description(fn(Project $record): string => $record->slug),

                Tables\Columns\TextColumn::make("client.name")
                    ->label("Client")
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make("status")->badge()->color(
                    fn(string $state): string => match ($state) {
                        "pending" => "gray",
                        "ongoing" => "warning",
                        "finished" => "success",
                    },
                ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options([
                    "pending" => "Pending",
                    "ongoing" => "Ongoing",
                    "finished" => "Finished",
                ]),
            ])
            ->actions([

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()])])
            ->recordUrl(
                fn(Project $record): string => ProjectResource::getUrl("edit", [
                    "record" => $record,
                ]),
            );
    }

    // public static function getRelations(): array {
    //     return [
    //             //         RelationManagers\RoadmapsRelationManager::class,
    //         RelationManagers\FaqsRelationManager::class,
    //         RelationManagers\ReleaseNotesRelationManager::class,
    //         RelationManagers\KnowledgeBasesRelationManager::class,
    //     ];
    // }

    public static function getPages(): array {
        return [
            "index" => Pages\ListProjects::route("/"),
            // "create" => Pages\CreateProject::route("/create"),
            "edit" => Pages\EditProject::route("/{record}/edit"),

            "faqs" => Pages\ManageProjectFaqs::route("/{record}/faqs"),
            "roadmaps" => Pages\ManageProjectRoadmaps::route("/{record}/roadmaps"),

            "knowledge" => Pages\ManageProjectKnowledge::route("/{record}/knowledge"),
            "releases" => Pages\ManageProjectReleases::route("/{record}/releases"),
        ];
    }
}
