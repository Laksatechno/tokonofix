@extends('layouts.scripts')
@extends('layouts.master')
@section('content')

{{-- resources\views\laporan\laporan.blade.php --}}

@php    
$tgl1 = request()->input('daterange') ? explode(' - ', request()->input('daterange'))[0] : date('Y-m-d');
$tgl2 = request()->input('daterange') ? explode(' - ', request()->input('daterange'))[1] : date('Y-m-d');
@endphp

<form action="{{ route('laporan.index') }}" method="GET">
    @csrf
    <div class="card mt-4">
        <div class="card-body">
            <div class="form-group d-flex align-items-center">
                <input type="text" name="daterange" id="daterange" class="form-control mr-2" value="{{ request()->input('daterange', "$tgl1 - $tgl2") }}">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <button type="button" class="btn btn-warning" onclick="window.location.href='{{ route('laporan.index') }}'">Reload</button>
            </div>
        </div>
    </div>
</form>


<div class="container mt-4">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="penjualan-tab" data-toggle="tab" href="#penjualan" role="tab" aria-controls="penjualan" aria-selected="true">Penjualan</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pembelian-tab" data-toggle="tab" href="#pembelian" role="tab" aria-controls="pembelian" aria-selected="false">Pembelian</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="penerimaan-kas-tab" data-toggle="tab" href="#penerimaan-kas" role="tab" aria-controls="penerimaan-kas" aria-selected="false">Penerimaan Kas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pengeluaran-kas-tab" data-toggle="tab" href="#pengeluaran-kas" role="tab" aria-controls="pengeluaran-kas" aria-selected="false">Pengeluaran Kas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="buku-besar-tab" data-toggle="tab" href="#buku-besar" role="tab" aria-controls="buku-besar" aria-selected="false">Buku Besar</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="penjualan" role="tabpanel" aria-labelledby="penjualan-tab">
            <!-- Penjualan content -->


            <!-- Penjualan card -->
            @if($penjualan->count() > 0)
            @php
            $i = 1;
            $total_penjualan = 0;
            @endphp
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Laporan Penjualan</h4>
                    <a href="{{ route('laporan.cetak_penjualan', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i></a>
                </div>
                <div class="card-body table-responsive">
                    <!-- Penjualan table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>ID Jual</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through penjualan data -->
                            @foreach($penjualan as $penj)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $penj->tanggal_jual }}</td>
                                <td>{{ $penj->id_jual }}</td>
                                <td>{{ number_format($penj->total_jual, 0, '.', '.') }}</td>
                            </tr>
                            @php
                            $total_penjualan += $penj->total_jual;
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Penjualan</th>
                                <th>{{ number_format($total_penjualan, 0, '.', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            @if($penjualan->count() == 0)
            <div class="alert alert-danger">
                Tidak ada data Penjualan atau silahkan filter data terlebih dahulu
            </div>
            @endif

        </div>

        <div class="tab-pane fade" id="pembelian" role="tabpanel" aria-labelledby="pembelian-tab">
            <!-- Pembelian content -->


            <!-- Pembelian card -->
            @if($pembelian->count() > 0)
            @php
            $i = 1;
            $total_pembelian = 0;
            @endphp
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Laporan Pembelian</h4>
                    <a href="{{ route('laporan.cetak_pembelian', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i></a>
                </div>
                <div class="card-body">
                    <!-- Pembelian table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>ID Beli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through pembelian data -->
                            @foreach($pembelian as $pemb)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $pemb->tanggal_beli }}</td>
                                <td>{{ $pemb->id_beli }}</td>
                                <td>{{ number_format($pemb->total_beli, 0, '.', '.') }}</td>
                            </tr>
                            @php
                            $total_pembelian += $pemb->total_beli;
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Pembelian</th>
                                <th>{{ number_format($total_pembelian, 0, '.', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            @if($pembelian->count() == 0)
            <div class="alert alert-danger">
                Tidak ada data Pembelian atau silahkan filter data terlebih dahulu
            </div>
            @endif

        </div>


        <div class="tab-pane fade" id="penerimaan-kas" role="tabpanel" aria-labelledby="penerimaan-kas-tab">
            <!-- Penerimaan Kas content -->

            <!-- Penerimaan Kas card -->
            @if($penerimaan_kas->count() > 0)
            @php
            $i = 1;
            $total_penerimaan_kas = 0;
            @endphp
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Laporan Penerimaan Kas</h4>
                    <a href="{{ route('laporan.cetak_penerimaan_kas', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i></a>
                </div>
                <div class="card-body">
                    <!-- Penerimaan Kas table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>ID Beli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through penerimaan_kas data -->
                            @foreach($penerimaan_kas as $penerimaan)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $penerimaan->tanggal_transaksi }}</td>
                                <td>{{ $penerimaan->id_jual }}</td>
                                <td>{{ number_format($penerimaan->jumlah, 0, '.', '.') }}</td>
                            </tr>
                            @php
                            $total_penerimaan_kas += $penerimaan->jumlah;
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Penerimaan Kas</th>
                                <th>{{ number_format($total_penerimaan_kas, 0, '.', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            @if($penerimaan_kas->count() == 0)
            <div class="alert alert-danger">
                Tidak ada data Penerimaan Kas atau silahkan filter data terlebih dahulu
            </div>
            @endif

        </div>

        <div class="tab-pane fade" id="pengeluaran-kas" role="tabpanel" aria-labelledby="pengeluaran-kas-tab">
            <!-- Pengeluaran Kas content -->

            <!-- Pengeluaran Kas card -->
            @if($pengeluaran_kas->count() > 0)
            @php
            $i = 1;
            $total_pengeluaran_kas = 0;
            @endphp
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Laporan Pengeluaran Kas</h4>
                    <a href="{{ route('laporan.cetak_pengeluaran_kas', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i></a>
                </div>
                <div class="card-body">
                    <!-- Pengeluaran Kas table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>ID Beli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through pengeluaran_kas data -->
                            @foreach($pengeluaran_kas as $pengeluaran)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $pengeluaran->tanggal_transaksi }}</td>
                                <td>       
                                     @if($pengeluaran->id_beli == 0)
                                    {{ $pengeluaran->id_biaya }}
                                @else
                                    {{ $pengeluaran->id_beli }}
                                @endif
                            </td>
                                <td>{{ number_format($pengeluaran->jumlah, 0, '.', '.') }}</td>
                            </tr>
                            @php
                            $total_pengeluaran_kas += $pengeluaran->jumlah;
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Pengeluaran Kas</th>
                                <th>{{ number_format($total_pengeluaran_kas, 0, '.', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            @if($pengeluaran_kas->count() == 0)
            <div class="alert alert-danger">
                Tidak ada data Pengeluaran Kas atau silahkan filter data terlebih dahulu
            </div>
            @endif
        </div>   
        <div class="tab-pane fade" id="buku-besar" role="tabpanel" aria-labelledby="buku-besar-tab">
            <!-- Buku Besar content -->

            <!-- Buku Besar card -->
            @if($buku_besar->count() > 0)
            @php
            $i = 1;
            $total_buku_besar = 0;
            @endphp
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Laporan Buku Besar</h4>
                    <a href="{{ route('laporan.cetak_buku_besar', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i></a>
                </div>
                <div class="card-body">
                    <!-- Buku Besar table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through buku_besar data -->
                            @foreach($buku_besar as $k)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $k->tanggal }}</td>
                                <td>{{ $k->keterangan }}</td>
                                <td>{{ $k->debit == 0 ? '-' : number_format($k->debit, 0, '.', '.') }}</td>
                                <td>{{ $k->kredit == 0 ? '-' : number_format($k->kredit, 0, '.', '.') }}</td>
                                <td>{{ number_format(abs($k->saldo), 0, '.', '.') }}</td>
                            </tr>
                            @php
                            $total_buku_besar += $k->debit - $k->kredit;
                            $i++;
                            @endphp
                            @endforeach
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">Total Buku Besar</th>
                                <th>{{ number_format($total_buku_besar, 0, '.', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            @if($buku_besar->count() == 0)
            <div class="alert alert-danger">
                Tidak ada data Buku Besar atau silahkan filter data terlebih dahulu
            </div>
            @endif
        </div> 
    </div>
</div>

@endsection
