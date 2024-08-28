<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TryoutResource\Pages;
use App\Filament\Resources\TryoutResource\RelationManagers;
use App\Models\Tryout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use stdClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TryoutResource extends Resource
{
    protected static ?string $model = Tryout::class;
    protected static ?string $navigationLabel = 'Tryouts';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where(function ($query) {
            $is_super_admin = Auth::user()->hasRole('super_admin');

            if (!$is_super_admin) {
                $query->where('user_id', Auth::user()->id);
            }
        })->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('package_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('started_at'),
                Forms\Components\DateTimePicker::make('finished_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth::user()->hasRole('super_admin');

                if (!$is_super_admin) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('No.')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('finished_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->getStateUsing(function ($record) {
                        return $record->tryOutAnswers->sum('score');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('package')
                    ->relationship('package', 'name')
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()->withFilename(date('d-m-Y H:i:s') . '_tryouts')->fromTable()->only([
                            'user.name',
                            'package.name',
                            'started_at',
                            'finished_at',
                            'score',
                        ]),
                    ]),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTryouts::route('/'),
            'create' => Pages\CreateTryout::route('/create'),
            'edit' => Pages\EditTryout::route('/{record}/edit'),
        ];
    }
}
