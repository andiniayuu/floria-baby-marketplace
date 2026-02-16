<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Semua Pesanan';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'payment_uploaded')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),

                        Forms\Components\Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                           ->disabled(),

                        // Forms\Components\Select::make('status')
                        //     ->label('Status Pesanan')
                        //     ->options([
                        //         'pending' => 'Menunggu Pembayaran',
                        //         'payment_uploaded' => 'Bukti Pembayaran Diupload',
                        //         'confirmed' => 'Dikonfirmasi',
                        //         'processing' => 'Diproses',
                        //         'packed' => 'Dikemas',
                        //         'shipped' => 'Dikirim',
                        //         'delivered' => 'Sampai',
                        //         'completed' => 'Selesai',
                        //         'cancelled' => 'Dibatalkan',
                        //         'rejected' => 'Ditolak',
                        //     ])
                        //     ->required()
                        //     ->native(false),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Menunggu',
                                'paid' => 'Dibayar',
                                'failed' => 'Gagal',
                                'refunded' => 'Refund',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Placeholder::make('tracking_number')
                            ->label('No Resi')
                            ->content(fn($record) => $record?->tracking_number ?? '- Belum Dikirim -'),


                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Pengiriman')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->disabled()
                            ->rows(3),

                        Forms\Components\TextInput::make('shipping_method')
                            ->disabled()
                            ->default('-'),

                        Forms\Components\TextInput::make('shipping_amount')
                            ->label('ongkir')
                            ->disabled()
                            ->prefix('Rp')
                            ->default(0),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('grand_total')
                            ->label('Total Pembayaran')
                            ->prefix('Rp')
                            ->disabled(),

                        Forms\Components\TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->disabled(),

                        Forms\Components\FileUpload::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                // Tables\Columns\TextColumn::make('status')
                //     ->label('Status')
                //     ->badge()
                //     ->color(fn(string $state): string => match ($state) {
                //         'new' => 'gray',
                //         'pending' => 'gray',
                //         'payment_uploaded' => 'info',
                //         'confirmed' => 'primary',
                //         'processing' => 'warning',
                //         'packed' => 'warning',
                //         'shipped' => 'info',
                //         'delivered' => 'success',
                //         'completed' => 'success',
                //         'cancelled' => 'danger',
                //         'rejected' => 'danger',
                //     })
                //     ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'warning',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->native(false),

                Tables\Filters\SelectFilter::make('seller')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Order $record) => $record->status === 'payment_uploaded')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'confirmed',
                            'payment_status' => 'paid',
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pesanan Dikonfirmasi')
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Order $record) => $record->status === 'payment_uploaded')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'rejected',
                            'payment_status' => 'failed',
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Pesanan Ditolak')
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
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
