@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h3 class="text-light mb-0">Manajemen Pengguna</h3>
        {{-- <button class="btn btn-light ms-auto me-0" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi-plus-circle me-2"></i>Tambah Pengguna Baru
        </button> --}}
    </div>
    
      <div class="card-body" id="show_all">
        <h1 class="text-center text-secondary my-5">Loading...</h1>
      </div>
    </div>
  </div>
</div>

{{-- edit modal start --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="modal-body p-4 bg-light">
            <div class="my-2">
                <label for="full_name">Nama Lengkap</label>
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nama Lengkap" required>
              </div>
            <div class="my-2">
              <label for="name">Username</label>
              <input type="text" name="name" id="name" class="form-control" placeholder="Username" required>
            </div>
            <div class="my-2">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Email" required>
              </div>
              <div class="my-2">
                <label for="phone_number">No. HP</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="No. HP" required>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" id="edit_btn" class="btn btn-success">Update Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit modal end --}}
  <script>
  $(function() {

    // edit ajax request
    $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: '{{ route('penggunaEdit') }}',
            method: 'get',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $("#id").val(response.id);
                $("#full_name").val(response.full_name);
                $("#name").val(response.name);
                $("#email").val(response.email);
                $("#phone_number").val(response.phone_number);
            }
        });
    });

    // update ajax request
    $("#edit_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_btn").text('Updating...');
        $.ajax({
            url: '{{ route('penggunaUpdate') }}',
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
                        'Pengguna berhasil diedit!',
                        'success'
                    )
                    fetchAll();
                }
                $("#edit_btn").text('Update Pengguna');
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
                    url: '{{ route('penggunaDelete') }}',
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire(
                            'Berhasil!',
                            'Pengguna berhasil dihapus!',
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
            url: '{{ route('penggunaFetchAll') }}',
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

    // JavaScript validation to allow only numbers in the phone_number field
    $(document).on('input', 'input[name="phone_number"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

  </script>
  <!-- /.row (main row) -->
@endsection
