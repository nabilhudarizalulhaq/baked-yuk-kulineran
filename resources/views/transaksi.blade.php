@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h3 class="text-light mb-0">Manajemen Transaksi</h3>
        <button class="btn btn-light ms-auto me-0" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi-plus-circle me-2"></i>Tambah Transaksi Baru
        </button>
      </div>
      <div class="card-body" id="show_all">
        <h1 class="text-center text-secondary my-5">Loading...</h1>
      </div>
    </div>
  </div>
</div>

@if ($wisataKuliners || $wisataKuliner)
<!-- add new modal start -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">
                  <div class="my-2">
                      <label for="id_wisata_kuliner">Wisata Kuliner</label>
                      <select name="id_wisata_kuliner" id="id_wisata_kuliner" class="form-control" required>
                          <option value="" selected disabled>Pilih Wisata Kuliner</option>
                          @foreach ($wisataKuliners as $wisataKuliner)
                          <option value="{{ $wisataKuliner->id }}">{{ $wisataKuliner->nama_wisata_kuliner }}</option>
                          @endforeach
                      </select>
                  </div>
                    <div id="menuDropdown" style="display: none;">
                        <div class="my-2">
                            <label for="menus">Menus</label>
                            <select multiple name="menus[]" id="menus" class="form-control" required>
                                <!-- Menus will be populated dynamically via JavaScript -->
                            </select>
                        </div>
                        <div id="menu_amounts">
                            <!-- Menu amounts input will be dynamically added here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" id="add_btn" class="btn btn-primary">Tambah Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add new modal end -->
@endif

<!-- edit modal start -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body p-4 bg-light">
                    <div class="my-2">
                        <label for="edit_status_transaksi">Status Transaksi</label>
                        <select name="status_transaksi" id="edit_status_transaksi" class="form-control" required>
                            <option value="0">In Progress</option>
                            <option value="1">Completed</option>
                            <option value="2">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" id="edit_btn" class="btn btn-success">Update Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- edit modal end -->

<script>
  $(function() {
    // Add new ajax request
    $("#add_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_btn").text('Adding...');
        $.ajax({
            url: '{{ route('transaksiStore') }}',
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    Swal.fire(
                        'Berhasil!',
                        'Transaksi berhasil ditambahkan!',
                        'success'
                    )
                    fetchAll();
                }
                $("#add_btn").text('Tambah Transaksi');
                $("#add_form")[0].reset();
                $("#addModal").modal('hide');
            }
        });
    });

    // Fetch menus when a wisata kuliner is selected
$('#id_wisata_kuliner').change(function() {
    var id_wisata_kuliner = $(this).val();
    if (id_wisata_kuliner) {
        $.ajax({
            url: '{{ route('transaksiFetchMenus') }}',
            method: 'get',
            data: { id_wisata_kuliner: id_wisata_kuliner },
            success: function(response) {
                $('#menus').empty();
                $.each(response, function(key, menu) {
                    $('#menus').append(`<option value="${menu.id}" data-price="${menu.harga}">${menu.nama_kuliner} - ${menu.harga}</option>`);
                });
                $('#menuDropdown').show(); // Show menu dropdown after selecting a wisata kuliner
            }
        });
    } else {
        $('#menuDropdown').hide(); // Hide menu dropdown if no wisata kuliner is selected
    }
});

// Add menu amount input based on selected options
$('#menus').change(function() {
    $('#menu_amounts').empty();
    $(this).find('option:selected').each(function() {
        $('#menu_amounts').append(`<input type="number" name="amounts[]" class="form-control mb-2" placeholder="Amount" min="1" value="1">`);
    });
});

    // edit ajax request
    $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{ route('transaksiEdit') }}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $("#id").val(response.id);
                $("#status_transaksi").val(response.status_transaksi);
            }
        });
    });

// Update ajax request
$("#edit_form").submit(function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    $("#edit_btn").text('Updating...');
    $.ajax({
        url: '{{ route('transaksiUpdate') }}',
        method: 'post',
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status == 200) {
                Swal.fire(
                    'Berhasil!',
                    'Transaksi berhasil diedit!',
                    'success'
                )
                fetchAll();
            }
            $("#edit_btn").text('Update Transaksi');
            $("#edit_form")[0].reset();
            $("#editModal").modal('hide');
        }
    });
});

    // Delete ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak bisa mengembalikan data ini jika menghapusnya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, saya yakin!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('transaksiDelete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        Swal.fire(
                            'Berhasil!',
                            'Transaksi berhasil dihapus!',
                            'success'
                        )
                        fetchAll();
                    }
                });
            }
        })
    });

    // Fetch all ajax request
    fetchAll();

    function fetchAll() {
        $.ajax({
            url: '{{ route('transaksiFetchAll') }}',
            method: 'get',
            success: function(response) {
                $("#show_all").html(response);
                if ($.fn.DataTable.isDataTable("#datatable")) {
                  $('#datatable').DataTable().clear().destroy();
                }
                $("#datatable").DataTable({
                    order: [0, 'asc']
                });
            }
        });
    }
});
</script>
@endsection
