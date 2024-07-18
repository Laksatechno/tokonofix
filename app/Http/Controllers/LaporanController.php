<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Kas;
use App\Models\Biaya;
use App\Models\PenerimaanKas;
use App\Models\PengeluaranKas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    //
    public function index(Request $request)
    {
        $daterange = $request->input('daterange');
        $dates = explode(' - ', $daterange);
    
        $tgl1 = $dates[0] ?? date('Y-m-d');
        $tgl2 = $dates[1] ?? date('Y-m-d');
    
        $penjualan = Penjualan::whereBetween('tanggal_jual', [$tgl1, $tgl2])->get();
        $pembelian = Pembelian::whereBetween('tanggal_beli', [$tgl1, $tgl2])->get();
        $buku_besar = Kas::whereBetween('tanggal', [$tgl1, $tgl2])->get();
        $biaya = Biaya::whereBetween('tanggal_biaya', [$tgl1, $tgl2])->get();
        $penerimaan_kas = PenerimaanKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $pengeluaran_kas = PengeluaranKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $kas = Kas::whereBetween('tanggal', [$tgl1, $tgl2])->get();
        $kas = collect();

        foreach ($penerimaan_kas as $penerimaan) {
            $kas->push([
                'keterangan' => $penerimaan->keterangan,
                'total' => $penerimaan->jumlah,
            ]);
        }

        foreach ($pengeluaran_kas as $pengeluaran) {
            $kas->push([
                'keterangan' => $pengeluaran->keterangan,
                'total' => -$pengeluaran->jumlah,
            ]);
        }

        // Kelompokkan data berdasarkan keterangan dan hitung totalnya
        $kas = $kas->groupBy('keterangan')->map(function ($group) {
            return $group->sum('total');
        });
        
        $total_biaya = $biaya->sum('total_biaya'); // asumsikan kolom 'jumlah' untuk total biaya
        return view('laporan.laporan', compact('penjualan', 'pembelian', 'buku_besar', 'biaya', 'penerimaan_kas', 'pengeluaran_kas', 'total_biaya', 'tgl1', 'tgl2',  'kas'));
    }
    

    public function cetak_penjualan(Request $request, $tgl1, $tgl2)
        {
            $tgl1 = date('Y-m-d', strtotime($tgl1));
            $tgl2 = date('Y-m-d', strtotime($tgl2));
            $periode = $tgl1 . ' - ' . $tgl2;
            $data = Penjualan::whereBetween('tanggal_jual', [$tgl1, $tgl2])->get();
            $penjualan = Penjualan::whereBetween('tanggal_jual', [$tgl1, $tgl2])->get();
            $total_penjualan = $penjualan->sum('total_jual');

            $pdf = Pdf::loadView('laporan.cetak_laporan_penjualan', compact('penjualan', 'total_penjualan', 'tgl1', 'tgl2', 'periode', 'data'));
            return $pdf->stream();
        }

    public function cetak_pembelian(Request $request, $tgl1, $tgl2)
    {
        $tgl1 = date('Y-m-d', strtotime($tgl1));
        $tgl2 = date('Y-m-d', strtotime($tgl2));

        $data = Pembelian::whereBetween('tanggal_beli', [$tgl1, $tgl2])->get();
        $periode = $tgl1 . ' - ' . $tgl2;

        // Your logic to handle pembelian report between $tgl1 and $tgl2
        $pembelian = Pembelian::whereBetween('tanggal_beli', [$tgl1, $tgl2])->get();

        $pdf = Pdf::loadView('laporan.cetak_laporan_pembelian', compact('pembelian', 'tgl1', 'tgl2', 'periode', 'data'));

        return $pdf->stream();
    }

    function cetak_buku_besar(Request $request, $tgl1, $tgl2)
    {
        $tgl1 = date('Y-m-d', strtotime($tgl1));
        $tgl2 = date('Y-m-d', strtotime($tgl2));

        $data = Kas::whereBetween('tanggal', [$tgl1, $tgl2])->get();
        $periode = $tgl1 . ' - ' . $tgl2;
        $buku_besar = Kas::whereBetween('tanggal', [$tgl1, $tgl2])->get();
        $total_buku_besar = $buku_besar->sum('debit') - $buku_besar->sum('kredit');
        $pdf = Pdf::loadView('laporan.cetak_buku_besar', compact('buku_besar', 'total_buku_besar', 'tgl1', 'tgl2', 'periode', 'data'));

        return $pdf->stream();
    }

    function cetak_penerimaan_kas(Request $request, $tgl1, $tgl2)
    {
        $tgl1 = date('Y-m-d', strtotime($tgl1));
        $tgl2 = date('Y-m-d', strtotime($tgl2));
        $data = PenerimaanKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $periode = $tgl1 . ' - ' . $tgl2;
        $penerimaan_kas = PenerimaanKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $total_penerimaan_kas = $penerimaan_kas->sum('jumlah');
        $pdf = Pdf::loadView('laporan.cetak_penerimaan_kas', compact('penerimaan_kas', 'total_penerimaan_kas', 'tgl1', 'tgl2', 'periode', 'data'));

        return $pdf->stream();
    }

    function cetak_pengeluaran_kas(Request $request, $tgl1, $tgl2)
    {
        $tgl1 = date('Y-m-d', strtotime($tgl1));
        $tgl2 = date('Y-m-d', strtotime($tgl2));

        $data = PengeluaranKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $periode = $tgl1 . ' - ' . $tgl2;
        $pengeluaran_kas = PengeluaranKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $total_pengeluaran = $pengeluaran_kas->sum('jumlah');
        $pdf = Pdf::loadView('laporan.cetak_pengeluaran_kas', compact('pengeluaran_kas','total_pengeluaran', 'tgl1', 'tgl2', 'periode', 'data'));

        return $pdf->stream();
    }

    public function cetak_kas(Request $request, $tgl1, $tgl2)
    {
        $tgl1 = date('Y-m-d', strtotime($tgl1));
        $tgl2 = date('Y-m-d', strtotime($tgl2));
    
        $biaya = Biaya::whereBetween('tanggal_biaya', [$tgl1, $tgl2])->get();
        $periode = $tgl1 . ' - ' . $tgl2;
        $penerimaan_kas = PenerimaanKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $pengeluaran_kas = PengeluaranKas::whereBetween('tanggal_transaksi', [$tgl1, $tgl2])->get();
        $kas = collect();
    
        foreach ($penerimaan_kas as $penerimaan) {
            $kas->push([
                'keterangan' => $penerimaan->keterangan,
                'total' => $penerimaan->jumlah,
            ]);
        }
    
        foreach ($pengeluaran_kas as $pengeluaran) {
            $kas->push([
                'keterangan' => $pengeluaran->keterangan,
                'total' => -$pengeluaran->jumlah,
            ]);
        }
    
        // Mengelompokkan data berdasarkan keterangan dan menghitung total
        $kas = $kas->groupBy('keterangan')->map(function ($group) {
            return $group->sum('total');
        });
    
        $total_biaya = $biaya->sum('total_biaya'); // Asumsi kolom 'total_biaya' untuk total biaya
        $pdf = Pdf::loadView('laporan.cetak_kas', compact('kas', 'total_biaya', 'tgl1', 'tgl2', 'periode'));
    
        return $pdf->stream();
    }
    

}
