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

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending'    => 'Menunggu',
                                'confirmed'  => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'packed'     => 'Dikemas',
                                'shipped'    => 'Dikirim',
                                'delivered'  => 'Terkirim',
                                'completed'  => 'Selesai',
                                'cancelled'  => 'Dibatalkan',
                            ])
                            ->native(false),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending'  => 'Menunggu',  // ✅ FIX
                                'paid'     => 'Lunas',
                                'failed'   => 'Gagal',
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
                            ->label('Metode Pengiriman')
                            ->disabled()
                            ->default('-'),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim')
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

                // ✅ FIX: seller.name dari relasi seller (user)
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->default('—'),

                // ✅ FIX: gunakan grand_total bukan total_amount
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'          => 'Menunggu',
                        'payment_uploaded' => 'Bukti Dikirim',
                        'confirmed'        => 'Dikonfirmasi',
                        'processing'       => 'Diproses',
                        'packed'           => 'Dikemas',
                        'shipped'          => 'Dikirim',
                        'delivered'        => 'Terkirim',
                        'completed'        => 'Selesai',
                        'cancelled'        => 'Dibatalkan',
                        'rejected'         => 'Ditolak',
                        default            => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending'          => 'gray',
                        'payment_uploaded' => 'warning',
                        'confirmed'        => 'primary',
                        'processing'       => 'warning',
                        'packed'           => 'warning',
                        'shipped'          => 'info',
                        'delivered'        => 'success',
                        'completed'        => 'success',
                        'cancelled'        => 'danger',
                        'rejected'         => 'danger',
                        default            => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending'  => 'Menunggu',  // ✅ FIX
                        'paid'     => 'Lunas',
                        'failed'   => 'Gagal',
                        'refunded' => 'Refund',
                        default    => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending'  => 'gray',
                        'paid'     => 'success',
                        'failed'   => 'danger',
                        'refunded' => 'warning',
                        default    => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->formatStateUsing(
                        fn($state) =>
                        $state
                            ? $state->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
                            : '-'
                    )
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'          => 'Menunggu',
                        'payment_uploaded' => 'Bukti Dikirim',
                        'confirmed'        => 'Dikonfirmasi',
                        'processing'       => 'Diproses',
                        'packed'           => 'Dikemas',
                        'shipped'          => 'Dikirim',
                        'delivered'        => 'Terkirim',
                        'completed'        => 'Selesai',
                        'cancelled'        => 'Dibatalkan',
                    ])
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
                            'status'         => 'confirmed',
                            'payment_status' => 'paid',
                        ]);
                        Notification::make()->success()->title('Pesanan Dikonfirmasi')->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Order $record) => $record->status === 'payment_uploaded')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status'         => 'rejected',
                            'payment_status' => 'failed',
                        ]);
                        Notification::make()->danger()->title('Pesanan Ditolak')->send();
                    }),

                // ✅ Tambah action COD delivered untuk admin
                Tables\Actions\Action::make('cod_delivered')
                    ->label('COD Diterima')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn(Order $record) => $record->payment_method === 'cod'
                        && $record->status === 'shipped'
                        && !$record->isPaid())
                    ->requiresConfirmation()
                    ->modalDescription('Konfirmasi bahwa pembeli sudah membayar dan barang sudah diterima.')
                    ->action(function (Order $record) {
                        $record->markCodDelivered();
                        Notification::make()->success()->title('COD Lunas — Pesanan Selesai')->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
