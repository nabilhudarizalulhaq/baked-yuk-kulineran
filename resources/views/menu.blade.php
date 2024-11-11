@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h3 class="text-light mb-0">Manajemen Menu</h3>
        <button class="btn btn-light ms-auto me-0" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi-plus-circle me-2"></i>Tambah Menu Baru
        </button>
    </div>

      <div class="card-body" id="show_all">
        <h1 class="text-center text-secondary my-5">Loading...</h1>
      </div>
    </div>
  </div>
</div>
{{-- add new modal start --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Menu Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
            <div class="my-2">
              <label for="foto_kuliner">Foto Kuliner</label>
              <input type="file" name="foto_kuliner" class="form-control" required>
            </div>
          <div class="my-2">
            <label for="nama_kuliner">Nama Kuliner</label>
            <input type="text" name="nama_kuliner" class="form-control" placeholder="Nama Kuliner" required>
          </div>
          <div class="my-2">
            <label for="harga">Harga</label>
            <input type="text" name="harga" class="form-control" placeholder="Harga" required>
          </div>
          <div class="my-2">
            <label for="deskripsi">Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" required>
          </div>

          <!-- Cek jika Auth::user() ada dan periksa role user -->
          @if (Auth::check() && Auth::user()->role == "admin")
            <div class="my-2">
              <label for="id_wisata_kuliner">Wisata Kuliner</label>
              <select name="id_wisata_kuliner" class="form-control" required>
                <option value="" selected disabled>Pilih Wisata Kuliner</option>
                <!-- Pastikan $wisata_kuliners ada dan tidak kosong -->
                @if (isset($wisata_kuliners) && $wisata_kuliners->isNotEmpty())
                    @foreach($wisata_kuliners as $wisata_kuliner)
                        <option value="{{ $wisata_kuliner->id }}">{{ $wisata_kuliner->nama_wisata_kuliner }}</option>
                    @endforeach
                @else
                    <option value="" disabled>Wisata Kuliner tidak tersedia</option>
                @endif
              </select>
            </div>
          @elseif (Auth::check() && Auth::user()->role == "owner")
            <!-- Untuk owner, pastikan $wisata_kuliners ada -->
            @if (isset($wisata_kuliners))
                <input type="hidden" name="id_wisata_kuliner" value="{{ $wisata_kuliners->id }}">
            @else
                <p>Data wisata kuliner tidak ditemukan.</p>
            @endif
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" id="add_btn" class="btn btn-primary">Tambah Menu</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- add new modal end --}}

{{-- edit modal start --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="old_foto_kuliner" id="old_foto_kuliner">
        <div class="modal-body p-4 bg-light">
            <div class="my-2">
                <label for="foto_kuliner">Foto Kuliner</label>
                <input type="file" name="foto_kuliner" class="form-control">
              </div>
              <div class="mt-2" id="foto_kuliner">

            </div>
            <div class="my-2">
              <label for="nama_kuliner">Nama Kuliner</label>
              <input type="text" name="nama_kuliner" id="nama_kuliner" class="form-control" placeholder="Nama Kuliner" required>
            </div>
            <div class="my-2">
                <label for="harga">Harga</label>
                <input type="text" name="harga" id="harga" class="form-control" placeholder="Harga" required>
              </div>
              <div class="my-2">
                <label for="deskripsi">Deskripsi</label>
                <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi" required>
              </div>
              <?php if (Auth::user()->role == "admin") : ?>
          <div class="my-2">
            <label for="id_wisata_kuliner">Wisata Kuliner</label>
            <select name="id_wisata_kuliner" class="form-control" required>
                <option value="" selected disabled>Pilih Wisata Kuliner</option>
                @foreach($wisata_kuliners as $wisata_kuliner)
                    <option value="{{ $wisata_kuliner->id }}">{{ $wisata_kuliner->nama_wisata_kuliner }}</option>
                @endforeach
            </select>
          </div>
          <?php elseif (Auth::user()->role == "owner") : ?>
          <input type="hidden" name="id_wisata_kuliner" id="id_wisata_kuliner">
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" id="edit_btn" class="btn btn-success">Update Menu</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit modal end --}}
  <script>
  $(function() {
    // add new ajax request
    $("#add_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_btn").text('Adding...');
        $.ajax({
            url: '{{ route('menuStore') }}',
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
                        'Menu berhasil ditambahkan!',
                        'success'
                    )
                    fetchAll();
                }
                $("#add_btn").text('Tambah Menu');
                $("#add_form")[0].reset();
                $("#addModal").modal('hide');
            }
        });
    });

    // edit ajax request
    $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{ route('menuEdit') }}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $("#id").val(response.id);
                $("#old_foto_kuliner").val(response.foto_kuliner);
                $("#foto_kuliner").html(
                    `<img src="storage/menu/${response.foto_kuliner}" width="300px" class="img-fluid img-thumbnail">`);
                $("#nama_kuliner").val(response.nama_kuliner);
                $("#harga").val(response.harga);
                $("#deskripsi").val(response.deskripsi);

                // Set the selected value for id_user if admin
                @if (Auth::user()->role == "admin")
                $('#id_wisata_kuliner').val(response.id_wisata_kuliner);
                $('#id_wisata_kuliner option').each(function() {
                    if ($(this).val() == response.id_wisata_kuliner) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).prop('selected', false);
                    }
                });
                @elseif (Auth::user()->role == "owner")
                $("#id_wisata_kuliner").val(response.id_wisata_kuliner);
                @endif
            }
        });
    });

    // update ajax request
    $("#edit_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Updating...');
        $.ajax({
            url: '{{ route('menuUpdate') }}',
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
                        'Menu berhasil diedit!',
                        'success'
                    )
                    fetchAll();
                }
                $("#edit_btn").text('Update Menu');
                $("#edit_form")[0].reset();
                $("#editModal").modal('hide');
            }
        });
    });

    // delete ajax request
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
                    url: '{{ route('menuDelete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            'Berhasil!',
                            'Menu berhasil dihapus!',
                            'success'
                        )
                        fetchAll();
                    }
                });
            }
        })
    });

    // fetch all ajax request
    fetchAll();

    function fetchAll() {
        $.ajax({
            url: '{{ route('menuFetchAll') }}',
            method: 'get',
            success: function(response) {
                $("#show_all").html(response);
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable("#datatable")) {
                  $('#datatable').DataTable().clear().destroy();
                }
                // Initialize DataTable
                $("#datatable").DataTable({
                    order: [0, 'asc']
                });
            }
        });
    }

    // JavaScript validation to allow only numbers in the harga field
    $(document).on('input', 'input[name="harga"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

  </script>
  <!-- /.row (main row) -->
@endsection
