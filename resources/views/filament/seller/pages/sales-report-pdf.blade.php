<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penjualan - Floria Baby</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #222;
    background: #fff;
  }

  .header {
    padding-bottom: 12px;
    margin-bottom: 14px;
    border-bottom: 2px solid #e91e8c;
  }

  .brand {
    font-size: 19px;
    font-weight: bold;
    color: #e91e8c;
    float: left;
  }

  .doc-right {
    float: right;
    text-align: right;
    font-size: 9.5px;
    color: #666;
    line-height: 1.7;
  }

  .info-box {
    background: #fdf2f8;
    border: 1px solid #f9a8d4;
    border-radius: 4px;
    padding: 9px 12px;
    margin-bottom: 14px;
  }

  .info-box table { width: 100%; }
  .info-box td { padding: 2px 0; font-size: 10px; color: #333; }
  .info-box td:first-child { color: #888; width: 130px; }
  .info-box td strong { color: #111; }

  .sum-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  .sum-table td { border: 1px solid #e5e7eb; padding: 9px 12px; text-align: center; width: 25%; }
  .sum-label { font-size: 8.5px; color: #888; margin-bottom: 3px; }
  .sum-val { font-size: 13px; font-weight: bold; color: #111; }
  .sum-val.pink { color: #e91e8c; font-size: 12px; }

  .section-title {
    font-size: 9.5px;
    font-weight: bold;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e91e8c;
    padding-bottom: 4px;
    margin-bottom: 8px;
    display: inline-block;
  }

  table.tx { width: 100%; border-collapse: collapse; font-size: 10px; }
  table.tx thead tr { background: #e91e8c; }
  table.tx thead th { padding: 7px 8px; color: white; text-align: left; font-weight: bold; font-size: 9px; border: none; }
  table.tx thead th.c { text-align: center; }
  table.tx thead th.r { text-align: right; }
  table.tx tbody tr { border-bottom: 1px solid #f3f4f6; }
  table.tx tbody tr.alt { background: #fdf2f8; }
  table.tx tbody td { padding: 6px 8px; border: none; vertical-align: middle; color: #333; }
  table.tx tbody td.c { text-align: center; }
  table.tx tbody td.r { text-align: right; }

  .no { color: #bbb; font-size: 9px; }
  .order-no { font-family: DejaVu Sans Mono, monospace; font-size: 9px; background: #f3f4f6; padding: 1px 5px; border-radius: 2px; }
  .cust { font-weight: bold; color: #111; }
  .cust-email { font-size: 8px; color: #aaa; margin-top: 1px; }
  .amount { font-weight: bold; color: #e91e8c; }

  .badge { display: inline-block; padding: 1px 7px; border-radius: 3px; font-size: 8px; font-weight: bold; }
  .b-completed  { background: #dcfce7; color: #166534; }
  .b-shipped    { background: #dbeafe; color: #1e40af; }
  .b-delivered  { background: #ede9fe; color: #4c1d95; }
  .b-processing { background: #fef9c3; color: #713f12; }
  .b-packed     { background: #fce7f3; color: #9d174d; }
  .b-confirmed  { background: #dbeafe; color: #1d4ed8; }

  .date { color: #333; }
  .time { font-size: 8px; color: #aaa; margin-top: 1px; }

  tr.total-row { background: #1f2937 !important; border-top: 2px solid #e91e8c; }
  tr.total-row td { padding: 8px; color: white !important; font-weight: bold; font-size: 10.5px; border: none; }
  tr.total-row td.r { color: #f9a8d4 !important; font-size: 12px; text-align: right; }

  td.empty { text-align: center; padding: 24px; color: #aaa; font-style: italic; }

  .note { margin-top: 12px; border-top: 1px solid #f3f4f6; padding-top: 8px; font-size: 8.5px; color: #aaa; }

  .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
  .sign-l { float: left; width: 150px; }
  .sign-r { float: right; width: 150px; }
  .sign-label { font-size: 9px; color: #888; margin-bottom: 38px; }
  .sign-line { border-top: 1px solid #333; padding-top: 3px; }
  .sign-name { font-weight: bold; font-size: 9.5px; }
  .sign-role { font-size: 8.5px; color: #888; margin-top: 1px; }

  .page-note { text-align: center; font-size: 8px; color: #ccc; margin-top: 10px; }
  .clearfix::after { content: ''; display: table; clear: both; }
</style>
</head>
<body>

@php
  $seller     = auth()->user();
  $total      = $orders->count();
  $pendapatan = $orders->sum('grand_total');
  $totalItem  = $orders->sum(fn($o) => $o->items->count());
  $rataRata   = $total > 0 ? $pendapatan / $total : 0;
  $tglAwal    = $orders->min('created_at');
  $tglAkhir   = $orders->max('created_at');
@endphp

<div class="header clearfix">
  <div class="brand">Floria Baby</div>
  <div class="doc-right">
    Laporan Penjualan Seller<br>
    No. {{ 'RPT/' . now()->format('Y/m') . '/' . str_pad($seller->id, 3, '0', STR_PAD_LEFT) }}<br>
    Dicetak: {{ now()->format('d M Y, H:i') }} WIB
  </div>
</div>

<div class="info-box">
  <table>
    <tr>
      <td>Nama Seller</td>
      <td>: <strong>{{ $seller->name }}</strong></td>
      <td>Periode</td>
      <td>: <strong>
        @if($tglAwal && $tglAkhir)
          {{ \Carbon\Carbon::parse($tglAwal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tglAkhir)->format('d M Y') }}
        @else
          {{ now()->format('M Y') }}
        @endif
      </strong></td>
    </tr>
    <tr>
      <td>Email</td>
      <td>: {{ $seller->email }}</td>
      <td>Status Pembayaran</td>
      <td>: <strong style="color:#166534;">Lunas (Paid)</strong></td>
    </tr>
  </table>
</div>

<div class="section-title">Ringkasan</div>
<table class="sum-table">
  <tr>
    <td>
      <div class="sum-label">Total Pesanan</div>
      <div class="sum-val">{{ number_format($total) }}</div>
    </td>
    <td>
      <div class="sum-label">Item Terjual</div>
      <div class="sum-val">{{ number_format($totalItem) }}</div>
    </td>
    <td>
      <div class="sum-label">Total Pendapatan</div>
      <div class="sum-val pink">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
    </td>
    <td>
      <div class="sum-label">Rata-rata / Pesanan</div>
      <div class="sum-val">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
    </td>
  </tr>
</table>

<div class="section-title">Detail Transaksi ({{ $total }} pesanan)</div>
<table class="tx">
  <thead>
    <tr>
      <th style="width:4%">No</th>
      <th style="width:16%">No. Pesanan</th>
      <th style="width:23%">Pelanggan</th>
      <th class="c" style="width:6%">Item</th>
      <th class="r" style="width:18%">Total</th>
      <th class="c" style="width:12%">Status</th>
      <th class="r" style="width:21%">Tanggal</th>
    </tr>
  </thead>
  <tbody>
    @forelse($orders as $order)
    @php
      $bClass = match($order->status) {
        'completed'  => 'b-completed',
        'shipped'    => 'b-shipped',
        'delivered'  => 'b-delivered',
        'processing' => 'b-processing',
        'packed'     => 'b-packed',
        'confirmed'  => 'b-confirmed',
        default      => '',
      };
      $bLabel = match($order->status) {
        'completed'  => 'Selesai',
        'shipped'    => 'Dikirim',
        'delivered'  => 'Terkirim',
        'processing' => 'Diproses',
        'packed'     => 'Dikemas',
        'confirmed'  => 'Dikonfirmasi',
        default      => ucfirst($order->status),
      };
    @endphp
    <tr class="{{ $loop->even ? 'alt' : '' }}">
      <td class="c no">{{ $loop->iteration }}</td>
      <td><span class="order-no">{{ $order->order_number }}</span></td>
      <td>
        <div class="cust">{{ $order->user?->name ?? '-' }}</div>
        @if($order->user?->email)
        <div class="cust-email">{{ $order->user->email }}</div>
        @endif
      </td>
      <td class="c">{{ $order->items->count() }}</td>
      <td class="r amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
      <td class="c"><span class="badge {{ $bClass }}">{{ $bLabel }}</span></td>
      <td class="r">
        <div class="date">{{ $order->created_at->format('d M Y') }}</div>
        <div class="time">{{ $order->created_at->format('H:i') }} WIB</div>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" class="empty">Tidak ada data pesanan.</td></tr>
    @endforelse

    @if($total > 0)
    <tr class="total-row">
      <td colspan="4">TOTAL &mdash; {{ $total }} pesanan &bull; {{ $totalItem }} item</td>
      <td class="r">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
      <td colspan="2"></td>
    </tr>
    @endif
  </tbody>
</table>

<div class="note">
  * Laporan ini hanya mencakup pesanan dengan pembayaran lunas. Total belum dikurangi biaya platform dan pajak.
</div>

<div class="footer clearfix">
  <div class="sign-l">
    <div class="sign-label">Mengetahui,</div>
    <div class="sign-line">
      <div class="sign-name">Admin Floria Baby</div>
      <div class="sign-role">Marketplace</div>
    </div>
  </div>
  <div class="sign-r">
    <div class="sign-label">{{ now()->format('d M Y') }}</div>
    <div class="sign-line">
      <div class="sign-name">{{ $seller->name }}</div>
      <div class="sign-role">Seller</div>
    </div>
  </div>
</div>

<div class="page-note">
  Floria Baby &mdash; Dokumen digenerate otomatis, sah tanpa tanda tangan basah.
</div>

</body>
</html>