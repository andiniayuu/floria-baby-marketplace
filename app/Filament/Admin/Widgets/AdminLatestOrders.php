<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdminLatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest())
            ->heading('Pesanan Terbaru')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR'),

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
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultPaginationPageOption(5);
    }
}
