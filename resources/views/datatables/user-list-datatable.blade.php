<table id="usersTable" class="table table-striped table-bordered w-100">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

@push('scripts')
<script>
$(function () {
    let table = $('#usersTable').DataTable({
        ajax: '{{ route("users.datatable") }}',
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'created_at' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    // Open modal with user data
    $(document).on('click', '.editUser', function () {
        $('#editUserId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
        $('#editEmail').val($(this).data('email'));
        $('#editUserModal').modal('show');
    });

    // Update via AJAX
    $('#editUserForm').submit(function (e) {
        e.preventDefault();
        let id = $('#editUserId').val();

        $.ajax({
            url: '/users/' + id,
            method: 'PUT',
            data: {
                name: $('#editName').val(),
                email: $('#editEmail').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                toastr.success(res.message, 'Success', {positionClass: "toast-top-right"});
                $('#editUserModal').modal('hide');
                table.ajax.reload();
            },
            error: function (xhr) {
                if (xhr.responseJSON?.errors) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        toastr.error(value[0], 'Validation Error', {positionClass: "toast-top-right"});
                    });
                } else {
                    toastr.error('Something went wrong', 'Error', {positionClass: "toast-top-right"});
                }
            }
        });
    });
});
</script>
@endpush
