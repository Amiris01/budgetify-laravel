<x-app-layout>
    @section('title', 'Budgetify | Manage Events')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Manage Events
            </h1>
        </div>

        <div class="container mt-4 mb-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Events</h5>
                            <p class="card-text display-4 mb-0" id="totalEvents">{{ $data['totalEvents'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Upcoming Events</h5>
                            <p class="card-text display-4 mb-0" id="upcomingEvents">{{ $data['upcomingEvents'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Events This Month</h5>
                            <p class="card-text display-4 mb-0" id="eventsThisMonth">{{ $data['eventsThisMonth'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Expenses</h5>
                            <p class="card-text display-4 mb-0" id="totalExpenses">RM
                                {{ number_format($data['totalExpenses'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="row">
                <div class="col">

                    <button class="btn btn-info addBudget action-icon" data-action="add" style="float: right">
                        Add Event
                    </button>
                    <button id="showCalendarBtn" class="btn btn-primary" style="float: right; margin-right:10px;">Show
                        Calendar</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="calendarModalLabel">Calendar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-item-center justify-content-center">
                        <div data-bs-toggle="calendar" data-bs-target="./events.json" id="event-calendar"
                            style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            @if ($events->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped sortable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Expenses (RM)</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $index => $event)
                                <tr class="item">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->location }}</td>
                                    <td>{{ number_format($event->expenses, 2) }}</td>
                                    <td>{{ $event->status }}</td>
                                    <td>{{ $event->remarks }}</td>
                                    <td>
                                        <a href="#" class="action-icon" data-id="{{ $event->id }}"
                                            data-action="view" title="View Record">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $event->id }}"
                                            data-action="update" title="Update Record">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $event->id }}"
                                            data-action="delete" title="Delete Record">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $events->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-danger">
                    <em>No records were found.</em>
                </div>
            @endif
        </div>

        <div class="modal fade" tabindex="-1" id="add-form">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" enctype="multipart/form-data" method="POST"
                            action="{{ route('events.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name1" class="form-label">Name</label>
                                    <input type="text" name="name1" id="name1" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="location1" class="form-label">Location</label>
                                    <input type="text" name="location1" id="location1" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="status1" class="form-label">Status</label>
                                    <select name="status1" id="status1" class="form-select">
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Postponed">Postponed</option>
                                        <option value="Tentative">Tentative</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="attachment1" class="form-label">Attachment</label>
                                    <input type="file" name="attachment1" id="attachment1" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="start_date1" class="form-label">Start Date and Time</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date1" id="start_date1" class="form-control">
                                        <input type="time" name="start_time1" id="start_time1" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date1" class="form-label">End Date and Time</label>
                                    <div class="input-group">
                                        <input type="date" name="end_date1" id="end_date1" class="form-control">
                                        <input type="time" name="end_time1" id="end_time1" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="remarks1" class="form-label">Remarks</label>
                                <textarea name="remarks1" id="remarks1" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="mt-3">
                                <div class="text-center">
                                    <div id="imagePreview"></div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="add-event">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="viewModalLabel">
                            <i class="fas fa-calendar-alt me-2"></i>Event Details
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center align-items-center mb-4">
                            <div id="view-attachment"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="event-info">
                                    <i class="fas fa-signature"></i>
                                    <span class="info-label">Name:</span>
                                    <span class="info-value" id="view-name"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="info-label">Location:</span>
                                    <span class="info-value" id="view-location"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="info-label">Status:</span>
                                    <span class="info-value" id="view-status"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="info-label">Expenses (RM):</span>
                                    <span class="info-value" id="view-expenses"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-comment"></i>
                                    <span class="info-label">Remarks:</span>
                                    <span class="info-value" id="view-remarks"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="event-info">
                                    <i class="fas fa-hourglass-start"></i>
                                    <span class="info-label">Start Timestamp:</span>
                                    <span class="info-value" id="view-start_timestamp"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-hourglass-end"></i>
                                    <span class="info-label">End Timestamp:</span>
                                    <span class="info-value" id="view-end_timestamp"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-clock"></i>
                                    <span class="info-label">Created at:</span>
                                    <span class="info-value" id="view-created_at"></span>
                                </div>
                                <div class="event-info">
                                    <i class="fas fa-edit"></i>
                                    <span class="info-label">Last Updated:</span>
                                    <span class="info-value" id="view-updated_at"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5><i class="fas fa-exchange-alt me-2"></i>Transaction History</h5>
                        <div class="transaction-list" id="transaction-list">
                            <!-- Transaction items will be dynamically added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="update-form">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" name="location" id="location" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Postponed">Postponed</option>
                                        <option value="Tentative">Tentative</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="attachment" class="form-label">Attachment</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date and Time</label>
                                    <div class="input-group">
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                        <input type="time" name="start_time" id="start_time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date and Time</label>
                                    <div class="input-group">
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                        <input type="time" name="end_time" id="end_time" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="mt-3">
                                <div class="text-center">
                                    <div id="imagePreview1"></div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="confirm-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                        <p class="mb-0">Are you sure you want to delete this event?</p>

                        <div id="transaction-options" class="mt-3" style="display: none;">
                            <h6>Transactions associated with this event:</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transactionOption"
                                    id="nullifyTransactions" value="nullify">
                                <label class="form-check-label" for="nullifyTransactions">
                                    Set transactions to no associated event
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transactionOption"
                                    id="deleteTransactions" value="delete">
                                <label class="form-check-label" for="deleteTransactions">
                                    Delete associated transactions
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .modal-dialog {
                display: flex;
                align-items: center;
                min-height: calc(100% - 1rem);
            }

            @media (min-width: 576px) {
                .modal-dialog {
                    min-height: calc(100% - 3.5rem);
                }
            }

            .modal-content {
                border: none;
                border-radius: 0.5rem;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }

            .modal-header {
                background-color: #f8f9fa;
                border-bottom: none;
                padding: 1.5rem 2rem;
            }

            .modal-body {
                padding: 2rem;
            }

            .modal-footer {
                border-top: none;
                padding: 1rem 2rem 1.5rem;
            }

            .event-info {
                margin-bottom: 1rem;
            }

            .event-info .info-label {
                font-weight: bold;
                color: #6c757d;
            }

            .event-info .info-value {
                color: #343a40;
            }

            .event-info i {
                width: 25px;
                color: #007bff;
            }

            #view-attachment {
                max-width: 100%;
                height: auto;
                margin-bottom: 1rem;
            }

            .transaction-list {
                max-height: 300px;
                overflow-y: auto;
            }

            .transaction-item {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            .expense {
                color: #dc3545;
            }

            .income {
                color: #28a745;
            }

            .transaction-date {
                color: #6c757d;
                font-size: 0.9em;
            }

            .transaction-time {
                color: #6c757d;
                font-size: 0.8em;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function formatDate(dateString) {
                const date = new Date(dateString);

                const year = date.getFullYear();
                const month = ('0' + (date.getMonth() + 1)).slice(-2);
                const day = ('0' + date.getDate()).slice(-2);
                const hours = ('0' + date.getHours()).slice(-2);
                const minutes = ('0' + date.getMinutes()).slice(-2);
                const seconds = ('0' + date.getSeconds()).slice(-2);

                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            $('.action-icon').click(function() {
                var budgetId = $(this).data('id');
                var action = $(this).data('action');

                if (action === 'view') {
                    var eventId = Number($(this).data("id"));
                    $.ajax({
                        url: 'events/' + eventId,
                        method: "GET",
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $('#view-name').text(data.name || "N/A");
                                $('#view-location').text(data.location || "N/A");
                                $('#view-status').text(data.status || "N/A");
                                $('#view-remarks').text(data.remarks || "N/A");
                                $('#view-start_timestamp').text(data.start_timestamp);
                                $('#view-end_timestamp').text(data.end_timestamp);
                                $('#view-created_at').text(formatDate(data.created_at));
                                $('#view-updated_at').text(formatDate(data.updated_at));
                                $('#view-expenses').text(data.expenses !== null ? data.expenses.toFixed(2) :
                                    "N/A");

                                if (data.attachment) {
                                    var attachmentUrl = '/storage/' + data
                                        .attachment;
                                    var fileExtension = data.attachment.split('.').pop().toLowerCase();

                                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                        var imageElement = '<img src="' + attachmentUrl +
                                            '" class="img-fluid mb-4" style="max-width: 100%; height: auto; max-height: 200px;">';
                                        $('#view-attachment').html(imageElement);
                                    } else {
                                        var linkElement = '<a href="' + attachmentUrl +
                                            '" target="_blank" class="btn btn-primary"><i class="fas fa-download me-2"></i>Download Attachment</a>';
                                        $('#view-attachment').html(linkElement);
                                    }
                                } else {
                                    $('#view-attachment').html(
                                        '<p class="text-muted"><i class="fas fa-image me-2"></i>No attachment available.</p>'
                                    );
                                }
                            } else {
                                console.error('No data received.');
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the event data:", error);
                        }
                    });

                    $.ajax({
                        url: '{{ route('getTransactionsByEvent') }}',
                        method: "GET",
                        data: {
                            id: eventId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                var transactionList = $('#transaction-list');
                                transactionList.empty();

                                data.forEach(function(transaction) {
                                    var transactionItem = '';

                                    if (transaction.trans_type === 'Income') {
                                        // Template for Income
                                        transactionItem = `
                          <div class="transaction-item">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                      <i class="fas fa-arrow-up income me-2"></i>
                                      <span class="transaction-date">${new Date(transaction.created_at).toLocaleDateString()}</span>
                                      <span class="transaction-time ms-1">${new Date(transaction.created_at).toLocaleTimeString()}</span>
                                      <strong class="ms-2">${transaction.description}</strong>
                                  </div>
                                  <strong class="income">+$${transaction.amount.toFixed(2)}</strong>
                              </div>
                          </div>
                      `;
                                    } else if (transaction.trans_type === 'Expense') {
                                        // Template for Expense
                                        transactionItem = `
                          <div class="transaction-item">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                      <i class="fas fa-arrow-down expense me-2"></i>
                                      <span class="transaction-date">${new Date(transaction.created_at).toLocaleDateString()}</span>
                                      <span class="transaction-time ms-1">${new Date(transaction.created_at).toLocaleTimeString()}</span>
                                      <strong class="ms-2">${transaction.description}</strong>
                                  </div>
                                  <strong class="expense">-$${transaction.amount.toFixed(2)}</strong>
                              </div>
                          </div>
                      `;
                                    }

                                    transactionList.append(transactionItem);
                                });
                            } else {
                                console.error('No data received.');
                                $('#transaction-list').html(`
                <div class="alert alert-warning d-flex align-items-center mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        No recent transactions found.
                    </div>
                </div>
            `);
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the transaction data:", error);
                        }
                    });

                    $('#view-modal').modal('show');

                } else if (action === 'update') {
                    var eventId = Number($(this).data("id"));
                    $("#saveChanges").attr("data-id", eventId);
                    $("#id").val(eventId);
                    $('#updateForm').attr('action', '/events/' + eventId);
                    $.ajax({
                        url: 'events/' + eventId,
                        method: "GET",
                        data: {
                            id: eventId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $('#updateForm #name').val(data.name);
                                $('#updateForm #location').val(data.location);
                                $('#updateForm #expenses').val(data.expenses);
                                $('#updateForm #remarks').val(data.remarks);
                                $('#updateForm #start_date').val(data.start_timestamp.split(' ')[0]);
                                $('#updateForm #start_time').val(data.start_timestamp.split(' ')[1]);
                                $('#updateForm #end_date').val(data.end_timestamp.split(' ')[0]);
                                $('#updateForm #end_time').val(data.end_timestamp.split(' ')[1]);
                                $('#updateForm #status').val(data.status);
                                $('#updateForm #expenses').val(data.expenses);

                                if (data.attachment) {
                                    var attachmentUrl = '/storage/' + data
                                        .attachment;
                                    var fileExtension = data.attachment.split('.').pop().toLowerCase();

                                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                        var imageElement = '<img src="' + attachmentUrl +
                                            '" class="img-fluid" style="max-width: 100%; height: auto; max-height: 200px;">';
                                        $('#imagePreview1').html(imageElement);
                                    } else {
                                        var linkElement = '<a href="' + attachmentUrl +
                                            '" target="_blank" class="btn btn-primary"><i class="fas fa-download me-2"></i>Download Attachment</a>';
                                        $('#imagePreview1').html(linkElement);
                                    }
                                } else {
                                    $('#imagePreview1').html(
                                        '<p class="text-muted"><i class="fas fa-image me-2"></i>No attachment available.</p>'
                                    );
                                }

                                $('#update-form').modal('show');
                            } else {
                                console.error('No data received.');
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the event data:", error);
                        }
                    });
                } else if (action === 'delete') {
                    $('#confirmDelete').data('id', budgetId);
                    var deleteUrl = '{{ route('events.destroy', ':id') }}';
                    deleteUrl = deleteUrl.replace(':id', budgetId);
                    $('#deleteForm').attr('action', deleteUrl);
                    $('#confirm-modal').modal('show');
                } else if (action === 'add') {
                    $('#add-form').modal('show');
                }
            });

            $('#attachment').on('change', function(event) {
                var input = event.target;

                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    var fileExtension = file.name.split('.').pop().toLowerCase();

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var imageElement = '<img src="' + e.target.result +
                                '" class="img-fluid" style="max-width: 100%; height: auto; max-height: 200px;">';
                            $('#imagePreview1').html(imageElement);
                        };

                        reader.readAsDataURL(file);
                    } else {
                        $('#imagePreview1').html(
                            '<p class="text-muted"><i class="fas fa-image me-2"></i>Selected file is not an image.</p>'
                        );
                    }
                }
            });

            $('#attachment1').on('change', function(event) {
                var input = event.target;

                if (input.files && input.files[0]) {
                    var file = input.files[0];
                    var fileExtension = file.name.split('.').pop().toLowerCase();

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var imageElement = '<img src="' + e.target.result +
                                '" class="img-fluid" style="max-width: 100%; height: auto; max-height: 200px;">';
                            $('#imagePreview').html(imageElement);
                        };

                        reader.readAsDataURL(file);
                    } else {
                        $('#imagePreview').html(
                            '<p class="text-muted"><i class="fas fa-image me-2"></i>Selected file is not an image.</p>'
                        );
                    }
                }
            });

            $('#saveChanges').on('click', function() {
                $('#updateForm').submit();
            });

            $('#confirmDelete').click(function() {
                $('#deleteForm').submit();
            });

            $('#showCalendarBtn').click(function() {
                $('#event-calendar').toggle();
            });

            $('#showCalendarBtn').click(function() {
                fetchCalendarEvents();
                $('#calendarModal').modal('show');
                $('#event-calendar').show();
            });

            function fetchCalendarEvents() {
                $.ajax({
                    url: '{{ route('getCalendar') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            console.log('Data fetched successfully:', data.message);
                            // Handle your calendar update here
                        } else {
                            console.error('Error fetching calendar data:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching calendar data:', error);
                    }
                });
            }


            $('#event-calendar').bsCalendar({
                locale: 'en',
                url: null,
                width: '300px',
                icons: {
                    prev: 'fa-solid fa-arrow-left fa-fw',
                    next: 'fa-solid fa-arrow-right fa-fw',
                    eventEdit: 'fa-solid fa-edit fa-fw',
                    eventRemove: 'fa-solid fa-trash fa-fw'
                },
                showTodayHeader: true,
                showEventEditButton: false,
                showEventRemoveButton: false,
                showPopover: false,
                popoverConfig: {
                    animation: false,
                    html: true,
                    delay: 400,
                    placement: 'top',
                    trigger: 'hover'
                },
                formatPopoverContent: function(events) {
                    return '';
                },
                formatEvent: function(event) {
                    return drawEvent(event);
                }
            });

            var previous = null;
            var current = null;
            setInterval(function() {
                $.getJSON('{{ asset('events.json') }}', function(json) {
                    current = JSON.stringify(json);
                    if (previous && current && previous !== current) {
                        console.log('refresh');
                        location.reload();
                    }
                    previous = current;
                });
            }, 2000);

            function loadEventData(eventId) {
                $.ajax({
                    url: '{{ route('checkEvent') }}',
                    type: 'GET',
                    data: {
                        id: eventId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.hasTransactions) {
                            $('#transaction-options').show();
                        } else {
                            $('#transaction-options').hide();
                        }
                    }
                });
            }

            $('#confirm-modal').on('show.bs.modal', function() {
                const eventId = $('#confirmDelete').data('id');
                if (eventId) {
                    loadEventData(eventId);
                }
            });
        </script>
    @endpush
</x-app-layout>
