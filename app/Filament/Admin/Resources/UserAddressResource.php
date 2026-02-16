<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserAddressResource\Pages\ListUserAddresses;
use App\Filament\Admin\Resources\UserAddressResource\Pages;
use App\Models\UserAddress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserAddressResource extends Resource
{
    protected static ?string $model = UserAddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'User Addresses';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => $record->name ?? 'User #' . $record->id
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Address Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->placeholder('e.g., Home, Office')
                                    ->maxLength(255),

                                Forms\Components\Toggle::make('is_primary')
                                    ->label('Primary Address')
                                    ->default(false),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('recipient_name')
                                    ->label('Recipient Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('province')
                                    ->label('Province')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('district')
                                    ->label('District')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('subdistrict')
                                    ->label('Subdistrict')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->required()
                            ->maxLength(10),

                        Forms\Components\Textarea::make('full_address')
                            ->label('Full Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Recipient')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->searchable(),

                Tables\Columns\TextColumn::make('province')
                    ->label('Province')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->name ?? 'User #' . $record->id
                    )
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_primary')
                    ->label('Primary Address')
                    ->placeholder('All addresses')
                    ->trueLabel('Primary only')
                    ->falseLabel('Non-primary only'),

                Tables\Filters\SelectFilter::make('province')
                    ->options(
                        fn() => UserAddress::query()
                            ->whereNotNull('province') 
                            ->where('province', '!=', '')
                            ->distinct()
                            ->pluck('province', 'province')
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUserAddresses::route('/'),
            'create' => Pages\CreateUserAddress::route('/create'),
            'edit' => Pages\EditUserAddress::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
