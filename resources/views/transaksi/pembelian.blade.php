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
                            Halaman untuk manajemen transaksi pembelian di Octobake.
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
                        <a role="button" id="print-button" class="btn text-info btn-block-option">Cetak</a>
                        <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive mb-3">
                        <table id="example" class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th></th>
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
                                        <td><input type="checkbox" class="select-checkbox"></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pembelian->tanggal_pembelian }} WIB</td>
                                        <td>{{ $pembelian->nama_produk }}</td>
                                        <td>{{ $pembelian->harga_satuan }}</td>
                                        <td>{{ number_format($pembelian->jumlah_dibeli, 0, ',', '.') }}</td>
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

    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
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
            function printSelectedData() {
                var selectedRows = [];
                // Mengumpulkan data yang dipilih
                $('.table tbody input[type="checkbox"]:checked').each(function () {
                    var row = $(this).closest('tr');
                    var data = {
                        No: row.find('td:eq(0)').text(),
                        Tanggal: row.find('td:eq(1)').text(),
                        NamaProduk: row.find('td:eq(2)').text(),
                        Harga: row.find('td:eq(3)').text(),
                        Jumlah: row.find('td:eq(4)').text(),
                        Diskon: row.find('td:eq(5)').text(),
                        Total: row.find('td:eq(6)').text(),
                    };
                    selectedRows.push(data);
                });

                // Membuat template cetak HTML
                var printHtml = '<html><head><title>Struk Nota Belanja</title></head><body>';
                printHtml += '<h2>Struk Nota Belanja</h2>';
                printHtml += '<p>Nama Kasir: ' + '{{ Auth::user()->nama }}' + '</p>';
                printHtml += '<p>Nama Toko: Octobake</p>';
                printHtml += '<table>';
                printHtml += '<tr><th>No.</th><th>Tanggal</th><th>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Diskon</th><th>Total</th></tr>';

                for (var i = 0; i < selectedRows.length; i++) {
                    printHtml += '<tr>';
                    printHtml += '<td>' + selectedRows[i].No + '</td>';
                    printHtml += '<td>' + selectedRows[i].Tanggal + '</td>';
                    printHtml += '<td>' + selectedRows[i].NamaProduk + '</td>';
                    printHtml += '<td>' + selectedRows[i].Harga + '</td>';
                    printHtml += '<td>' + selectedRows[i].Jumlah + '</td>';
                    printHtml += '<td>' + selectedRows[i].Diskon + '</td>';
                    printHtml += '<td>' + selectedRows[i].Total + '</td>';
                    printHtml += '</tr>';
                }

                printHtml += '</table>';
                printHtml += '</body></html>';

                var printWindow = window.open('', '', 'width=600,height=600');
                printWindow.document.open();
                printWindow.document.write(printHtml);
                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            }

            $('#print-button').click(function () {
                printSelectedData();
            });


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
                    { orderable: false, targets: [2, 3, 4, 5, 6, 7] },
                ],
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