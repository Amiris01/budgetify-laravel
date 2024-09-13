<x-app-layout>
    @section('title', 'Budgetify | Manage Budgets')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Manage Budgets
            </h1>
        </div>

        <div class="container mt-4 mb-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Current Total Income</h5>
                            <p class="card-text display-4 mb-0" id="totalIncome">RM
                                {{ number_format($data['totalIncome'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Expenses</h5>
                            <p class="card-text display-4 mb-0" id="totalExpenses">RM
                                {{ number_format($data['totalExpenses'], 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Savings Rate</h5>
                            <p class="card-text display-4 mb-0" id="savingsRate">
                                {{ number_format($data['savingsRate'], 2) }}%</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Remaining Budget</h5>
                            <p class="card-text display-4 mb-0" id="remainingBudget">RM
                                {{ number_format($data['totalBalance'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Budget Breakdown</h5>
                            <div class="chart-container">
                                <canvas id="budgetBreakdown" class="small-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Spending Trend</h5>
                            <canvas id="monthlySpendingTrend" class="small-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-3">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-info addBudget action-icon" data-action="add" style="float: right">
                            Add Budget
                        </button>
                    </div>
                </div>
            </div>

            <div class="container mt-4">
                @if ($budgets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped sortable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Total Amount (RM)</th>
                                    <th>Remarks</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($budgets as $index => $budget)
                                    <tr class="item">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $budget->title }}</td>
                                        <td>{{ $budget->categories->name }}</td>
                                        <td>{{ number_format($budget->total_amount, 2) }}</td>
                                        <td>{{ $budget->remarks }}</td>
                                        <td>{{ \Carbon\Carbon::parse($budget->start_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($budget->end_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="#" class="action-icon" data-id="{{ $budget->id }}"
                                                data-action="view" title="View Record">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="action-icon" data-id="{{ $budget->id }}"
                                                data-action="update" title="Update Record">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <a href="#" class="action-icon" data-id="{{ $budget->id }}"
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
                        {{ $budgets->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <div class="alert alert-danger">
                        <em>No records were found.</em>
                    </div>
                @endif
            </div>

            <div class="modal" tabindex="-1" id="add-form">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addForm" enctype="multipart/form-data" method="POST"
                                action="{{ route('budgets.store') }}">
                                @csrf
                                <div>
                                    <label for="title"><b>Title</b></label>
                                    <input type="text" name="title1" id="title1" class="form-control" />
                                </div>
                                <div>
                                    <label for="category"><b>Category</b></label>
                                    <select class="form-select" id="category1" name="category1">
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="total_amount"><b>Total Amount</b></label>
                                    <input type="number" name="total_amount1" id="total_amount1" class="form-control"
                                        step="0.01" />
                                </div>
                                <div>
                                    <label for="remarks"><b>Remarks</b></label>
                                    <textarea name="remarks1" id="remarks1" cols="5" class="form-control"></textarea>
                                </div>
                                <div>
                                    <label for="start_date"><b>Start Date</b></label>
                                    <input type="date" name="start_date1" id="start_date1" class="form-control" />
                                </div>
                                <div>
                                    <label for="end_date"><b>End Date</b></label>
                                    <input type="date" name="end_date1" id="end_date1" class="form-control" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="add-budget">
                                Save changes
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" tabindex="-1" id="update-form">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="updateForm" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id">
                                <div>
                                    <label for="title"><b>Title</b></label>
                                    <input type="text" name="title" id="title" class="form-control" />
                                </div>
                                <div>
                                    <label for="category"><b>Category</b></label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: none;">
                                    <label for="total_amount"><b>Total Amount</b></label>
                                    <input type="number" name="total_amount" id="total_amount" class="form-control" />
                                </div>
                                <div>
                                    <label for="remarks"><b>Remarks</b></label>
                                    <textarea name="remarks" id="remarks" cols="5" class="form-control"></textarea>
                                </div>
                                <div>
                                    <label for="start_date"><b>Start Date</b></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" />
                                </div>
                                <div>
                                    <label for="end_date"><b>End Date</b></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" />
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary" id="saveChanges">
                                Save changes
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">
                                <i class="fas fa-money-bill-wave me-2"></i>View Budget
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-info-circle me-2"
                                                    style="color: blue;"></i>Basic Information</h6>
                                            <ul class="list-group list-group-flush" style="margin-bottom: 40px;">
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="gap: 120px;">
                                                    <span><i class="fas fa-heading me-2"
                                                            style="color: blue;"></i>Title</span>
                                                    <span id="view-title" class="fw-bold"></span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-tag me-2"
                                                            style="color: blue;"></i>Category</span>
                                                    <span id="view-category" class="badge bg-secondary"></span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-coins me-2" style="color: blue;"></i>Total
                                                        Amount</span>
                                                    <span id="view-total_amount" class="fw-bold text-success"></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-calendar-alt me-2"
                                                    style="color: blue;"></i>Date Information</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    style="gap: 120px;">
                                                    <span><i class="fas fa-play me-2" style="color: blue;"></i>Start
                                                        Date</span>
                                                    <span id="view-start_date"></span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-stop me-2" style="color: blue;"></i>End
                                                        Date</span>
                                                    <span id="view-end_date"></span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-clock me-2" style="color: blue;"></i>Created
                                                        at</span>
                                                    <span id="view-created_at"></span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-sync me-2" style="color: blue;"></i>Last
                                                        Updated</span>
                                                    <span id="view-updated_at"></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-comment-alt me-2"
                                                    style="color: blue;"></i>Remarks</h6>
                                            <p id="view-remarks" class="card-text"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5><i class="fas fa-exchange-alt fa-black me-2" style="color: black;"></i>Recent Transactions
                            </h5>
                            <div class="transaction-list" id="transaction-list">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" id="confirm-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                        <div class="modal-body">
                            <p class="mb-0">Are you sure you want to delete this budget?</p>

                            <div id="transaction-options" class="mt-3" style="display: none;">
                                <h6>Transactions associated with this budget:</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="transactionOption"
                                        id="nullifyTransactions" value="nullify">
                                    <label class="form-check-label" for="nullifyTransactions">
                                        Set transactions to no associated budget
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
                .chart-container {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                }

                .small-chart {
                    width: 300px;
                    height: 300px;
                }

                .card-body {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
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
                    if (!dateString) return "";

                    const date = new Date(dateString);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();

                    return `${day}/${month}/${year}`;
                }

                $('.action-icon').click(function() {
                    var budgetId = $(this).data('id');
                    var action = $(this).data('action');

                    if (action === 'view') {
                        var budgetId = Number($(this).data("id"));
                        $.ajax({
                            url: 'budgets/' + budgetId,
                            method: "GET",
                            data: {
                                id: budgetId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data) {
                                    $('#view-title').text(data.title !== null ? data.title : "");
                                    $('#view-category').text(data.category !== null ? data.category : "");
                                    $('#view-total_amount').text(data.total_amount !== null ? data
                                        .total_amount : "");
                                    $('#view-remarks').text(data.remarks !== null ? data.remarks : "");
                                    $('#view-start_date').text(data.start_date !== null ? formatDate(data
                                        .start_date) : "");
                                    $('#view-end_date').text(data.end_date !== null ? formatDate(data
                                        .end_date) : "");
                                    $('#view-created_at').text(formatDate(data.created_at));
                                    $('#view-updated_at').text(formatDate(data.updated_at));
                                } else {
                                    console.error('No data received.');
                                }
                            },
                            error: function(error) {
                                console.error("There was an error fetching the budget data:", error);
                            }
                        });

                        $.ajax({
                            url: '{{ route('getTransactionByBudget') }}',
                            method: "GET",
                            data: {
                                id: budgetId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.length > 0) {
                                    var transactionList = $('#transaction-list');
                                    transactionList.empty(); // Clear any existing transactions

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
                                console.error("There was an error fetching the wallet transactions:", error);
                            }
                        });

                        $('#view-modal').modal('show');

                    } else if (action === 'update') {
                        var budgetId = Number($(this).data("id"));
                        $("#saveChanges").attr("data-id", budgetId);
                        $("#id").val(budgetId);
                        $('#updateForm').attr('action', '/budgets/' + budgetId);
                        $.ajax({
                            url: 'budgets/' + budgetId,
                            method: "GET",
                            data: {
                                id: budgetId
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data) {
                                    console.log(data);
                                    $('#update-form').modal('show');
                                    $('#title').val(data.title !== null ? data.title : "");
                                    $('#category').val(data.category !== null ? data.category : "");
                                    $('#total_amount').val(data.total_amount !== null ? data.total_amount : "");
                                    $('#remarks').val(data.remarks !== null ? data.remarks : "");
                                    $('#start_date').val(data.start_date !== null ? data.start_date : "");
                                    $('#end_date').val(data.end_date !== null ? data.end_date : "");
                                } else {
                                    console.error('No data received.');
                                }
                            },
                            error: function(error) {
                                console.error("There was an error fetching the budget data:", error);
                            }
                        });
                    } else if (action === 'delete') {
                        $('#confirmDelete').data('id', budgetId);
                        var deleteUrl = '{{ route('budgets.destroy', ':id') }}';
                        deleteUrl = deleteUrl.replace(':id', budgetId);
                        $('#deleteForm').attr('action', deleteUrl);
                        $('#confirm-modal').modal('show');
                    } else if (action === 'add') {
                        $('#add-form').modal('show');
                    }
                });

                $('#saveChanges').on('click', function() {
                    $('#updateForm').submit();
                });

                $('#confirmDelete').click(function() {
                    $('#deleteForm').submit();
                });

                $.ajax({
                    url: '{{ route('getBudgetBreakdown') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            const categories = data.data.map(item => item.category_name);
                            const amounts = data.data.map(item => parseFloat(item.total));

                            const ctx = document.getElementById('budgetBreakdown').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: categories,
                                    datasets: [{
                                        label: 'Budgets by Category',
                                        data: amounts,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.6)',
                                            'rgba(54, 162, 235, 0.6)',
                                            'rgba(255, 206, 86, 0.6)',
                                            'rgba(75, 192, 192, 0.6)',
                                            'rgba(153, 102, 255, 0.6)',
                                            'rgba(255, 159, 64, 0.6)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching budget breakdown data:', error);
                    }
                });

                $.ajax({
                    url: '{{ route('getMonthlySpendingTrend') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            const months = data.data.map(item => item.month);
                            const totals = data.data.map(item => parseFloat(item.total));

                            const ctx = document.getElementById('monthlySpendingTrend').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: months,
                                    datasets: [{
                                        label: 'Total Expenses by Month',
                                        data: totals,
                                        fill: false,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        tension: 0.1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Month'
                                            }
                                        },
                                        y: {
                                            title: {
                                                display: true,
                                                text: 'Total Expenses (RM)'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching monthly spending trend data:', error);
                    }
                });

                function loadEventData(budgetId) {
                    $.ajax({
                        url: '{{ route('checkBudget') }}',
                        type: 'GET',
                        data: {
                            id: budgetId,
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
                    const budgetId = $('#confirmDelete').data('id');
                    if (budgetId) {
                        loadEventData(budgetId);
                    }
                });
            </script>
        @endpush
</x-app-layout>
