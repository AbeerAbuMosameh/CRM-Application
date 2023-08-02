@extends('Dashboard.master')

@section('title')
    Contacts
@endsection

@section('css')

@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
				<span class="card-icon">
                    <i class="flaticon2-favourite text-primary"></i>
				</span>
                <h3 class="card-label">Contacts Data</h3>
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job</th>
                    <th>Education</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Language</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($contacts as $contact)
                    <tr data-entry-id="{{ $contact->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>@if ($contact->image)
                                <img class="pr-4" src="{{ $contact->image ? asset($contact->image) : 'https://placehold.co/600x400' }}" height="50px"
                                     width="50px"
                                     alt="Logo">
                            @endif </td>
                        <td>{{ $contact->fname }} {{ $contact->lname }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->job }}</td>
                        <td>{{ $contact->education }}</td>
                        <td>{{ $contact->address }}</td>
                        <td>{{ $contact->city }}</td>
                        <td>{{ $contact->language }}</td>
                        <td>{{ $contact->dob }}</td>
                        <td data-entry-id="{{ $contact->id }}" class="datatable-cell status-cell">
                    <span style="width: 108px;">
                        <span class="label font-weight-bold label-lg label-light-{{ $contact->is_approved ? 'primary' : 'danger' }} label-inline">
                            {{ $contact->is_approved ? 'Approved' : 'Not Approved' }}
                        </span>
                    </span>
                        </td>
                        <td>
                            <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                <i class="la la-edit"></i>
                            </a>
                            <a onclick="deleteRows('{{ $contact->id }}', this)" class="btn btn-sm btn-clean btn-icon btn-delete" title="Delete">
                                <i class="nav-icon la la-trash"></i>
                            </a>
                            <a class="btn btn-sm btn-clean btn-icon" title="Approve" id="approveButton" onclick="changeStatus({{ $contact->id }})">
                                <i class="la la-check-circle"></i>
                            </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function () {
            $('.edit-btn').click(function () {
                var advisorId = $(this).data('advisor-id');
                $('#advisorModal').modal('show');

                // Perform additional logic if needed based on the advisor ID
                // and populate the modal fields accordingly
            });

            // Handle the save button click event
            $('#saveAdvisorBtn').click(function () {
                var acceptCheckbox = $('#acceptCheckbox').prop('checked');

                // Perform further processing based on the checkbox value (accept/reject program)
                // You can make an AJAX request here to update the advisor's program status accordingly

                // Close the modal
                $('#advisorModal').modal('hide');
            });
        });
    </script>
    <script src="{{asset('admin/assets/js/pages/crud/datatables/data-sources/html.js')}}"></script>
    <script src="{{asset('admin/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>

    <script>
        function deleteRows(id, reference) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1BC5BD',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/contacts/' + id,
                        method: 'DELETE',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            // Show the success message
                            Swal.fire(
                                'Deleted!',
                                'Contact has been deleted.',
                                'success'
                            ).then(function() {
                               location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            console(error);
                            // Show the error message
                            Swal.fire(
                                'Error!',
                                'There was an error deleting role.',
                                'error'
                            );
                        }
                    });
                }
            });

        }
    </script>

    <script>
        function changeStatus(contactId) {
            $.ajax({
                url: '/contacts/' + contactId + '/approve',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    // Update the status text and class based on the response
                    if (response.is_approved) {
                        $('#statusCell_' + contactId).html(
                            '<span class="label font-weight-bold label-lg label-light-primary label-inline">Approved</span>'
                        );
                    } else {
                        $('#statusCell_' + contactId).html(
                            '<span class="label font-weight-bold label-lg label-light-danger label-inline">Not Approved</span>'
                        );
                    }

                    // Reload the page after updating the status
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    // Show the error message (optional)
                    // Swal.fire('Error!', 'There was an error updating the status.', 'error');
                }
            });
        }
    </script>



@endsection
