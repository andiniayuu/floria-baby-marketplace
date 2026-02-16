<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SellerRequestResource\Pages;
use App\Models\SellerRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class SellerRequestResource extends Resource
{
    protected static ?string $model = SellerRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Permintaan Seller';
    protected static ?string $navigationGroup = 'Manajemen User';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        Forms\Components\TextInput::make('request_number')
                            ->label('No. Pengajuan')
                            ->disabled(),

                        Forms\Components\TextInput::make('user.name')
                            ->label('Nama Pemohon')
                            ->disabled()
                            ->placeholder(fn(SellerRequest $record) => $record->user?->name),

                        Forms\Components\TextInput::make('store_name')
                            ->label('Nama Toko')
                            ->disabled(),

                        Forms\Components\Textarea::make('store_description')
                            ->label('Deskripsi Toko')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Review')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->rows(3)
                            ->visible(fn($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemohon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('store_name')
                    ->label('Nama Toko')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(SellerRequest $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pengajuan Seller')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui pengajuan ini?')
                    ->action(function (SellerRequest $record) {

                        // Update status pengajuan
                        $record->update([
                            'status' => 'approved',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);

                        // Pindahkan data toko ke tabel users
                        $record->user->update([
                            'role' => 'seller',
                            'seller_status' => 'approved',
                            'shop_name' => $record->store_name,
                            'shop_description' => $record->store_description,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pengajuan Disetujui')
                            ->body('User berhasil menjadi seller.')
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(SellerRequest $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (SellerRequest $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Pengajuan Ditolak')
                            ->body('Pengajuan seller telah ditolak.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerRequests::route('/'),
            'view' => Pages\ViewSellerRequest::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
