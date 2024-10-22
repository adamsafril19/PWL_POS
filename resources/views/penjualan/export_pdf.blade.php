<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('images/poltek.jpeg') }}">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
            </td>
        </tr>
    </table>
    <h3 class="text-center">LAPORAN DATA PENJUALAN</h3>
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Penjualan</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Total Items</th>
                <th class="text-right">Total Harga</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->penjualan_kode }}</td>
                <td>{{ \Carbon\Carbon::parse($p->penjualan_tanggal)->format('d-m-Y') }}</td>
                <td>{{ $p->pembeli }}</td>
                <td>{{ $p->total_items }}</td>
                <td class="text-right">{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td>{{ $p->user->username }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
