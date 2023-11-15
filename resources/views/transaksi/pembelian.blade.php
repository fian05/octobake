@extends('layouts.app')

@section('title')
    Data Pembelian
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    {{-- Keperluan Tombol DataTables --}}
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
@endsection

@section('content')
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Data Pembelian
                        </h1>
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                            Halaman untuk manajemen transaksi pembelian di {{ app('App\Models\Toko')::first()->nama_toko }}.
                        </h2>
                    </div>
                    <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Transaksi Pembelian
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        List Transaksi Pembelian
                    </h3>
                    <div class="block-options">
                        <a id="print-button" class="btn text-info btn-block-option">Cetak</a>
                        <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table id="example" class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all" title="Pilih semua data"></th>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelians as $pembelian)
                                    <tr>
                                        <td><input type="checkbox" class="select-checkbox" value="{{ $pembelian->id }}" title="Pilih data"></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pembelian->tanggal_pembelian }} WIB</td>
                                        <td>{{ $pembelian->nama_produk }}</td>
                                        <td>{{ $pembelian->harga_satuan }}</td>
                                        <td>{{ $pembelian->jumlah_dibeli }}</td>
                                        <td>{{ $pembelian->diskon }}%</td>
                                        <td>{{ $pembelian->total }}</td>
                                        <td>
                                            <form id="delete-form-{{ $pembelian->id }}" action="{{ route('pembelian_hapus', $pembelian->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $pembelian->id }}" title="Hapus Transaksi"><i class="fa fa-trash"></i></a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="formTambahData" method="POST" action="{{ route('pembelian_tambah') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Transaksi Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="datetime-local" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" required>
                            </div>
                            <div class="col-md mb-3">
                                <label for="nama_produk" class="form-label">Produk</label>
                                <select class="form-select" id="nama_produk" name="nama_produk" required>
                                    <option value="" selected disabled>- Pilih Produk -</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" data-stok="{{ $produk->stok_produk }}" data-harga="{{ $produk->harga_produk }}">{{ $produk->nama_produk }} (Stok {{ number_format($produk->stok_produk, 0, ',', '.')}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="harga_satuan" name="harga_satuan" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-md mb-3">
                                <label for="jumlah_dibeli" class="form-label">Jumlah Dibeli</label>
                                <input type="number" class="form-control" id="jumlah_dibeli" name="jumlah_dibeli" min="1" required>
                            </div>
                            <div class="col-md mb-3">
                                <label for="diskon" class="form-label">Diskon</label>
                                <select class="form-select" id="diskon" name="diskon">
                                    <option value="0" selected>Tanpa Diskon</option>
                                    <option value="20">20%</option>
                                    <option value="50">50%</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md mb-3">
                                <label for="total" class="form-label">Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="total" name="total" autocomplete="off"  readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function strRepeat(str, count) {
                return new Array(count + 1).join(str);
            }

            var selectedData = [];
            $('#select-all').on('change', function() {
                if (this.checked) {
                    $('.select-checkbox').prop('checked', true);
                    selectedData = $('.select-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get();
                } else {
                    $('.select-checkbox').prop('checked', false);
                    selectedData = [];
                }
            });
            $('.table').on('change', '.select-checkbox', function() {
                var id = $(this).val();
                if ($(this).prop('checked')) {
                    selectedData.push(id);
                } else {
                    selectedData = selectedData.filter(function(value) {
                        return value !== id;
                    });
                }
                if ($('.select-checkbox:checked').length === $('.select-checkbox').length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });

            $("#print-button").click(function() {
                if (selectedData.length > 0) {
                    cetak();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Informasi',
                        text: 'Pilih setidaknya satu item untuk dicetak.',
                    });
                }
            });

            function cetak() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('pembelian_cetak') }}",
                    data: {
                        dataDipilih: selectedData,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            var toko = response.toko;
                            var pembelians = response.pembelians;

                            var now = new Date();
                            var day = String(now.getDate()).padStart(2, '0');
                            var month = String(now.getMonth() + 1).padStart(2, '0');
                            var year = now.getFullYear();
                            var hours = String(now.getHours()).padStart(2, '0');
                            var minutes = String(now.getMinutes()).padStart(2, '0');
                            var seconds = String(now.getSeconds()).padStart(2, '0');
                            var currentDateTime = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;

                            // Preview
                            var totalItem2 = 0;
                            var grandTotal2 = 0;
                            var totalKeseluruhan2 = 0;
                            var previewHtml = '';
                            previewHtml += "<table cellpadding='0' cellspacing='0' style='width:100%''><tr><td style='text-align: left;'>Item</td><td style='text-align: right;'>Diskon</td><td style='text-align: right;'>Satuan</td><td style='text-align: right;'>Total</td></tr><tr><td colspan='4'><hr style='border-bottom: 1px dashed #000'></td></tr>";
                            pembelians.forEach(function(data) {
                                var harga = data.harga_satuan;
                                var total = data.total;
                                grandTotal2 += total;
                                var jumlahItem = data.jumlah_dibeli;
                                totalKeseluruhan2 += jumlahItem*harga;
                                totalItem2 += jumlahItem;
                                previewHtml += '<tr><td colspan="4" style="text-align: left;">'+data.nama_produk+'</td></tr><tr><td style="text-align: left;">'+jumlahItem+'&nbsp;</td><td style="text-align: right;">&nbsp;'+data.diskon+'%</td><td style="text-align: right;">&nbsp;Rp'+harga.toLocaleString('id-ID')+'</td><td style="text-align: right;">&nbsp;Rp'+total.toLocaleString('id-ID')+'</td></tr>';
                            });
                            totalHemat2 = totalKeseluruhan2-grandTotal2
                            previewHtml += "</table><hr style='border-bottom: 1px dashed #000'><table width='100%'><tr><td style='text-align: left;'>Total item &nbsp; "+totalItem2+"</td><td style='text-align: right;'>Rp"+totalKeseluruhan2.toLocaleString('id-ID')+"</td></tr><tr><td style='text-align: left;'>Diskon</td><td style='text-align: right;'>Rp"+totalHemat2.toLocaleString('id-ID')+"</td></tr><tr><td style='text-align: left; font-weight: bold'>Total setelah diskon</td><td style='text-align: right; font-weight: bold'>Rp<span id='total-pembelian'>"+grandTotal2.toLocaleString('id-ID')+"</span></td></tr></table>\
                            <p style='text-align: left; margin: 0; padding: 0;'>Uang pembeli:</p><input type='number' class='swal2-input mx-0 my-3 w-100' autocapitalize='none' step='1' min='0' max='9999999999999' maxlength='13' id='swal-input' placeholder='Input uang pembeli' required>\
                            <table id='kembalian' width='100%'><tr><td style='text-align: left;'>Kembalian</td><td style='text-align: right;'><span id='nominal-kembalian'></span></td></tr></table>";

                            var uangPembeli = 0;
                            var kembalian = 0;

                            Swal.fire({
                                title: "Konfirmasi Cetak Nota",
                                html: previewHtml,
                                showCancelButton: true,
                                confirmButtonText: "Cetak Nota",
                                allowOutsideClick: false,
                                didOpen: () => {
                                    const inputContainer = Swal.getContainer().querySelector('#swal-input');
                                    const additionalText = Swal.getContainer().querySelector('#kembalian');
                                    inputContainer.parentNode.insertBefore(additionalText, inputContainer.nextSibling);
                                    $('#swal-input').focus();
                                    $('#swal-input').on('input', function() {
                                        if (this.value.length > this.maxLength){
                                            this.value = this.value.slice(0, this.maxLength);
                                        }
                                        var totalBelanja = parseFloat($('#total-pembelian').text().replace(/\./g, ''));
                                        uangPembeli = parseFloat($(this).val());
                                        if (isNaN(uangPembeli)) {
                                            $('#nominal-kembalian').text('-');
                                            return;
                                        }
                                        kembalian = uangPembeli-totalBelanja;
                                        if (kembalian < 0) {
                                        $('#nominal-kembalian').text('Uang pembeli kurang!');
                                        return;
                                        }
                                        $('#nominal-kembalian').text('Rp'+kembalian.toLocaleString('id-ID'));
                                    });
                                    $('#swal-input').on('keyup', function (event) {
                                        if (event.key === 'Enter') {
                                            Swal.clickConfirm();
                                        }
                                    });
                                },
                            }).then((result) => {
                                var isUangPembeliFilled = $('#swal-input').is(':valid');
                                if (result.isConfirmed && isUangPembeliFilled) {
                                    if (kembalian < 0) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Nominal uang pembeli kurang!',
                                            confirmButtonText: 'Tutup',
                                        });
                                    } else {
                                        // Cetak
                                        var totalItem = 0;
                                        var grandTotal = 0;
                                        var totalKeseluruhan = 0;
                                        var printHtml = '';
                                        toko.forEach(function(data) {
                                            printHtml += '<!DOCTYPE html>\
                                                <html>\
                                                    <head>\
                                                        <title>Cetak Nota</title>\
                                                        <style>@page {margin: 0}body {margin: 0;font-size: 10px;font-family: monospace;}td {font-size: 10px;}.sheet {margin: 0;overflow: hidden;position: relative;box-sizing: border-box;page-break-after: always;}/** Paper sizes **/body.struk .sheet {width: 58mm;}body.struk .sheet {padding: 2mm;}.txt-left {text-align: left;}.txt-center {text-align: center;}.txt-right {text-align: right;}/** For screen preview **/@media screen {body {background: #e0e0e0;font-family: monospace;}.sheet {background: white;box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);margin: 5mm;}}/** Fix for Chrome issue #273306 **/@media print {body {font-family: monospace;}body.struk {width: 57.5mm;}body.struk .sheet {padding: 2mm;}.txt-left {text-align: left;}.txt-center {text-align: center;}.txt-right {text-align: right;}}</style>\
                                                    </head>\
                                                    <body class="struk" onload="printOut()">\
                                                        <section class="sheet">\
                                                            <table cellpadding="0" cellspacing="0" class="txt-center" width="100%">\
                                                                <tr>\
                                                                    <td>'+data.nama_toko+'</td>\
                                                                </tr>\
                                                                <tr>\
                                                                    <td>'+data.alamat_toko+'</td>\
                                                                </tr>\
                                                                <tr>\
                                                                    <td><svg xmlns="http://www.w3.org/2000/svg" class="bi bi-whatsapp" fill="currentColor" width="9" height="9" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.920l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.430.050-.197-.100-.836-.308-1.592-.985-.590-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.100-.114.133-.198.198-.330.065-.134.034-.248-.015-.347-.050-.099-.445-1.076-.612-1.470-.160-.389-.323-.335-.445-.340-.114-.007-.247-.007-.380-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.710 1.916.810 2.049.098.133 1.394 2.132 3.383 2.992.470.205.840.326 1.129.418.475.152.904.129 1.246.080.380-.058 1.171-.480 1.338-.943.164-.464.164-.860.114-.943-.049-.084-.182-.133-.380-.232z"/></svg> 0'+data.nohp_toko+'</td>\
                                                                </tr>\
                                                                <tr>\
                                                                    <td><svg xmlns="http://www.w3.org/2000/svg" class="bi bi-instagram" fill="currentColor" width="9" height="9" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg> @'+data.ig_toko+'</td>\
                                                                </tr>\
                                                            </table>\
                                                            <hr style="border-bottom: 1px dashed #000">\
                                                            <table cellpadding="0" cellspacing="0" style="width:100%">\
                                                                <tr>\
                                                                    <td class="txt-left">Kasir</td>\
                                                                    <td class="txt-left">:&nbsp;</td>\
                                                                    <td class="txt-left">{{ Auth::user()->nama }}</td>\
                                                                </tr>\
                                                                <tr>\
                                                                    <td class="txt-left">Tgl.&nbsp;</td>\
                                                                    <td class="txt-left">:&nbsp;</td>\
                                                                    <td class="txt-left">'+currentDateTime+' WIB</td>\
                                                                </tr>\
                                                            </table><br>\
                                                            <table cellpadding="0" cellspacing="0" style="width:100%">\
                                                                <tr>\
                                                                    <td class="txt-left">Item</td>\
                                                                    <td class="txt-right">Diskon</td>\
                                                                    <td class="txt-right">Satuan</td>\
                                                                    <td class="txt-right">Total</td>\
                                                                </tr>\
                                                                <tr>\
                                                                    <td colspan="4"><hr style="border-bottom: 1px dashed #000"></td>\
                                                                </tr>';
                                        });
                                        pembelians.forEach(function(data) {
                                            var harga = data.harga_satuan;
                                            var total = data.total;
                                            grandTotal += total;
                                            var jumlahItem = data.jumlah_dibeli;
                                            totalKeseluruhan += jumlahItem*harga;
                                            totalItem += jumlahItem;
                                            printHtml += '<tr><td colspan="4">'+data.nama_produk+'</td></tr><tr><td class="txt-left">'+jumlahItem+'&nbsp;</td><td class="txt-right">&nbsp;'+data.diskon+'%</td><td class="txt-right">&nbsp;Rp'+harga.toLocaleString('id-ID')+'</td><td class="txt-right">&nbsp;Rp'+total.toLocaleString('id-ID')+'</td></tr>';
                                        });
                                        totalHemat = totalKeseluruhan-grandTotal
                                        printHtml += '</table><hr style="border-bottom: 1px dashed #000"><table cellpadding="0" cellspacing="0" style="width:100%"><tr><td class="txt-left">Total item &nbsp; '+totalItem+'</td><td class="txt-right">Rp'+totalKeseluruhan.toLocaleString('id-ID')+'</td></tr><tr><td class="txt-left">Diskon</td><td class="txt-right">Rp'+totalHemat.toLocaleString('id-ID')+'</td></tr><tr><td class="txt-left" style="font-weight: bold">Total setelah diskon</td><td class="txt-right" style="font-weight: bold">Rp'+grandTotal.toLocaleString('id-ID')+'</td></tr><tr><td class="txt-left">Bayar</td><td class="txt-right">Rp'+uangPembeli.toLocaleString('id-ID')+'</td></tr><tr><td class="txt-left">Kembalian</td><td class="txt-right">Rp'+kembalian.toLocaleString('id-ID')+'</td></tr></table><p class="txt-center">* Terima kasih atas kunjungan anda *</p></section></body></html>';
                                        
                                        var dpi = 100; // DPI perangkat
                                        // Buat sementara elemen div untuk menempatkan HTML
                                        var tempDiv = document.createElement('div');
                                        tempDiv.innerHTML = printHtml;
                                        // Tambahkan elemen ke dalam dokumen, tetapi luar jangkauan pengguna
                                        tempDiv.style.position = 'absolute';
                                        tempDiv.style.left = '-9999px';
                                        document.body.appendChild(tempDiv);
                                        // Hitung tinggi elemen
                                        var htmlHeight = tempDiv.offsetHeight;
                                        // Konversi tinggi dalam sentimeter
                                        var inchToCm = 2.54;
                                        var htmlHeightInCm = (htmlHeight / dpi) * inchToCm;
                                        // Hapus elemen sementara
                                        document.body.removeChild(tempDiv);
                                        // Tampilkan hasil di console
                                        console.log("Tinggi Object yang diprint: "+htmlHeight+" pixel, atau "+htmlHeightInCm+" cm");
                                        
                                        var printWindow = window.open();
                                        printWindow.document.open();
                                        printWindow.document.write(printHtml);
                                        printWindow.document.close();
                                        printWindow.print();
                                        printWindow.close();
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Nominal uang pembeli belum diinput!',
                                        confirmButtonText: 'Tutup',
                                    });
                                }
                            });
                        }
                    },
                    error: function(error) {
                        console.error("Error:", error);
                    }
                });
            }

            document.getElementById("btnTambahData").addEventListener("click", function () {
                var inputTanggalPembelian = document.getElementById("tanggal_pembelian");
                var now = new Date();
                var year = now.getFullYear();
                var month = (now.getMonth() + 1).toString().padStart(2, '0');
                var day = now.getDate().toString().padStart(2, '0');
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');
                var seconds = now.getSeconds().toString().padStart(2, '0');
                inputTanggalPembelian.value = year + "-" + month + "-" + day + "T" + hours + ":" + minutes + ":" + seconds;
            });

            $('#nama_produk option').each(function() {
                var stok = $(this).data('stok');
                if (stok === 0) {
                    $(this).prop('disabled', true);
                }
            });
            $('#nama_produk').on('change', function() {
                var selectedOption = $('option:selected', this);
                var stok = selectedOption.data('stok');
                if (stok === 0) {
                    selectedOption.prop('disabled', true);
                }
                var hargaProduk = parseFloat(selectedOption.data('harga'));
                $('#harga_satuan').val(formatAngka(hargaProduk));
                var maxStok = selectedOption.data('stok');
                $('#jumlah_dibeli').attr('max', maxStok);
                $('#jumlah_dibeli').val(1);
                hitungTotal();
            });

            $('#jumlah_dibeli').on('input', function() {
                this.value = formatAngka(this.value);
            });

            $('#jumlah_dibeli, #diskon').on('input', function() {
                hitungTotal();
            });

            $('#btnSimpan').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah data yang Anda input sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#harga_satuan').val(parseFloat($('#harga_satuan').val().replace(/\./g, '')));
                        $('#jumlah_dibeli').val(parseInt($('#jumlah_dibeli').val().replace(/\./g, '')));
                        $('#total').val(parseFloat($('#total').val().replace(/\./g, '')));
                        $('#formTambahData').submit();
                    }
                });
            });

            function formatAngka(angka) {
                if (typeof angka !== 'string') {
                    angka = angka.toString(); // Convert to string if it's not already
                }
                if (!angka) return '';
                angka = angka.replace(/[^\d,]/g, ''); // Hapus karakter selain angka dan koma
                angka = angka.replace(/,/g, ''); // Hapus semua koma yang ada
                angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik untuk ribuan
                return angka;
            }

            function hitungTotal() {
                var hargaSatuan = parseFloat($('#harga_satuan').val().replace(/[^\d]/g, ''));
                var jumlahDibeli = parseInt($('#jumlah_dibeli').val().replace(/[^\d]/g, ''));
                var diskon = parseInt($('#diskon').val());

                if (!isNaN(hargaSatuan) && !isNaN(jumlahDibeli)) {
                    var total = (hargaSatuan * jumlahDibeli) - ((hargaSatuan * jumlahDibeli * diskon) / 100);
                    $('#total').val(formatAngka(total));
                } else {
                    $('#total').val('');
                }
            }

            $('.table').DataTable({
                columnDefs: [
                    { orderable: false, targets: [0, 3, 4, 5, 6, 7, 8] },
                ],
                order: [[2, 'desc']],
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari",
                    decimal: ",",
                    thousands: ".",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                },
                lengthChange: false,
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export Data ke Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                    },
                    filename: 'Data Pembelian Octobake - {{ date("d F Y H.i.s") }} WIB',
                    title: 'Data Pembelian Octobake',
                },],
            }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
        $(document).on('click', '.delete-link', function(e) {
            e.preventDefault();
            var id = $(this).attr('id').split('-')[2];
            Swal.fire({
                title: 'Konfirmasi',
                html: 'Anda yakin ingin menghapus data transaksi ini ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus transaksi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $('#delete-form-' + id).submit();
                }
            });
        });
    </script>
@endsection