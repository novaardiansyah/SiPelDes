<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentResource\Pages;
use App\Filament\Resources\ResidentResource\RelationManagers;
use App\Models\Resident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ResidentResource extends Resource
{
  protected static ?string $model = Resident::class;

  protected static ?string $navigationGroup = 'Menu Utama';
  protected static ?string $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $modelLabel = 'Data Penduduk';
  protected static ?int $navigationSort = 10;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('')
          ->description('Lengkapi formulir data penduduk')
          ->columns(2)
          ->schema([
            Forms\Components\TextInput::make('nama')
              ->label('Nama Lengkap')
              ->required(),
            Forms\Components\TextInput::make('nik')
              ->label('NIK')
              ->numeric()
              ->unique(ignoreRecord: true)
              ->required(),
            Forms\Components\TextInput::make('tempat_lahir')
              ->label('Tempat Lahir'),
            Forms\Components\DatePicker::make('tanggal_lahir')
              ->label('Tanggal Lahir')
              ->displayFormat('d M Y')
              ->native(false),
            Forms\Components\Select::make('agama')
              ->label('Agama')
              ->relationship('religion', 'nama')
              ->native(false)
              ->preload(),
            Forms\Components\Select::make('jenis_kelamin')
              ->label('Jenis Kelamin')
              ->options([
                'Laki-laki' => 'Laki-laki',
                'Perempuan' => 'Perempuan',
              ])
              ->native(false),
            Forms\Components\TextInput::make('pekerjaan')
              ->label('Pekerjaan'),
            Forms\Components\Select::make('status_penduduk')
              ->label('Status Penduduk')
              ->options([
                '1' => 'Penduduk Tetap',
                '2' => 'Penduduk Tidak Tetap',
              ])
              ->native(false),
            Forms\Components\Textarea::make('alamat')
              ->label('Alamat')
              ->rows(3),
            Forms\Components\Toggle::make('status_perkawinan')
              ->label('Status Perkawinan')
              ->inline(false),
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
        Tables\Columns\TextColumn::make('nik')
          ->label('NIK')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('nama')
          ->label('Nama Lengkap')
          ->searchable()
          ->sortable()
          ->toggleable(),
        Tables\Columns\TextColumn::make('jenis_kelamin')
          ->label('Jenis Kelamin')
          ->toggleable(),
        Tables\Columns\TextColumn::make('alamat')
          ->label('Alamat')
          ->searchable()
          ->toggleable(),
      ])
      ->filters([
        //
      ])
      ->recordUrl(null)
      ->recordAction(null)
      ->defaultSort('nama')
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
      'index' => Pages\ListResidents::route('/'),
      'create' => Pages\CreateResident::route('/create'),
      'edit' => Pages\EditResident::route('/{record}/edit'),
    ];
  }
}
