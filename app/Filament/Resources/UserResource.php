<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationGroup = 'Menu Utama';
  protected static ?string $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $modelLabel = 'User';
  protected static ?int $navigationSort = 11;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('')
          ->description('Lengkapi formulir data user')
          ->columns(2)
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Nama Lengkap')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('email')
              ->label('Email')
              ->email()
              ->required()
              ->maxLength(255),
            Forms\Components\Select::make('roles')
              ->relationship(name: 'roles', titleAttribute: 'name')
              ->preload()
              ->native(false)
              ->label('Role')
              ->required(),
            Forms\Components\TextInput::make('password')
              ->label('Password')
              ->password()
              ->maxLength(255),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('index')
          ->rowIndex()
          ->label('#'),
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Lengkap')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('email')
          ->label('Email')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('roles.name')
          ->label('Role')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Dibuat pada')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Diubah pada')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->recordUrl(null)
      ->recordAction(null)
      ->defaultSort('id', 'desc')
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->color('warning'),

          Tables\Actions\EditAction::make()
            ->color('primary'),

          Tables\Actions\DeleteAction::make(),

          ActivityLogTimelineTableAction::make('Activities')
            ->color('info'),
        ])
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
