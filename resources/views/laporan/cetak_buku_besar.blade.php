<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Buku Besar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 20px;
            text-align: left;
        }
        .invoice-footer {
            width: 100%;
        }
        .invoice-footer td {
            padding: 5px;
            text-align: right;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table td, table th {
            border: 1px solid #000000;
            padding: 8px;
        }
        table tr:nth-child(even){background-color: #ffffff;}
        table tr:hover {background-color: #ffffff;}
        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #ffffff;
            color: rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Laporan Buku Besar</h1>
    </div>
    <div class="invoice-details">
        <p>Periode: {{ $periode }}</p>
    </div>
    <div class="content">
    <table>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->tanggal}}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->debit == 0 ? '-' : number_format($item->debit, 0, '.', '.') }}</td>
            <td>{{ $item->kredit == 0 ? '-' : number_format($item->kredit, 0, '.', '.') }}</td>
            <td>{{ number_format(abs($item->saldo), 0, '.', '.') }}</td>
        </tr>
        @endforeach
    </table>
    </div>
    <div class="invoice-footer">
        <table>
            <tr>
                <td>Total</td>
                <td>{{ number_format($total_buku_besar, 0, '.', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>