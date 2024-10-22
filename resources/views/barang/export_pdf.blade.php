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
        <h3 class="text-center">LAPORAN DATA BARANG</h3>
        <table class="border-all">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th class="text-right">Harga Beli</th>
                    <th class="text-right">Harga Jual</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $b)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $b->barang_kode }}</td>
                    <td>{{ $b->barang_nama }}</td>
                    <td class="text-right">{{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ $b->kategori->kategori_nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
