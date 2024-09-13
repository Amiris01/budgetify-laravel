<x-app-layout>
    @section('title', 'Budgetify | Manage Wallets')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Manage Wallets
            </h1>
        </div>

        <div class="container mt-4 mb-5">
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Expense Distribution</h5>
                            <div class="chart-container">
                                <canvas id="expenseBreakdown" class="small-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Income Trend</h5>
                            <canvas id="monthlySpendingTrend" class="small-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="row">
                <div class="col">

                    <button class="btn btn-info addWallet action-icon" data-action="add" style="float: right">
                        Add Wallet
                    </button>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            @if ($wallets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped sortable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Institution</th>
                                <th>Current Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallets as $index => $wallet)
                                <tr class="item">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $wallet->name }}</td>
                                    <td>{{ $wallet->walletType->name }}</td>
                                    <td>{{ $wallet->financialInstitute->name }}</td>
                                    <td>{{ number_format($wallet->amount, 2) }}</td>
                                    <td>
                                        <a href="#" class="action-icon" data-id="{{ $wallet->id }}"
                                            data-action="view" title="View Record">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $wallet->id }}"
                                            data-action="update" title="Update Record">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $wallet->id }}"
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
                    {{ $wallets->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-danger">
                    <em>No records were found.</em>
                </div>
            @endif
        </div>

        <div class="modal fade" id="add-form" tabindex="-1" aria-labelledby="addWalletModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWalletModalLabel">Add New Wallet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" enctype="multipart/form-data" class="needs-validation" novalidate
                            method="POST" action="{{ route('wallets.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name1" class="form-label"><b>Name</b></label>
                                <input type="text" class="form-control" id="name1" name="name1" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="wallet_type1" class="form-label"><b>Type</b></label>
                                    <select class="form-select" id="wallet_type1" name="wallet_type1" required>
                                        <option value="" selected disabled>Select Type</option>
                                        @foreach ($wallet_type as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="amount1" class="form-label"><b>Opening Balance</b></label>
                                    <input type="number" name="amount1" id="amount1" step="0.01" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fin_institute1" class="form-label"><b>Financial Institution</b></label>
                                <select class="form-select" id="fin_institute1" name="fin_institute1" required>
                                    <option value="" selected disabled>Select Institution</option>
                                    @foreach ($fin_institute as $fin_ins)
                                        <option value="{{ $fin_ins->id }}">{{ $fin_ins->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description1" class="form-label"><b>Description</b></label>
                                <textarea class="form-control" id="description1" name="description1" rows="2"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="add-wallet">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="updateWalletModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateWalletModalLabel">Update Wallet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm" enctype="multipart/form-data" class="needs-validation" novalidate
                            method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id">
                            <div class="mb-3">
                                <label for="name" class="form-label"><b>Name</b></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="wallet_type" class="form-label"><b>Type</b></label>
                                    <select class="form-select" id="wallet_type" name="wallet_type" required>
                                        @foreach ($wallet_type as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="is_active" class="form-label"><b>Is Active</b></label>
                                    <select class="form-select" id="is_active" name="is_active" required>
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fin_institute" class="form-label"><b>Financial Institution</b></label>
                                <select class="form-select" id="fin_institute" name="fin_institute" required>
                                    <option value="" selected disabled>Select Institution</option>
                                    @foreach ($fin_institute as $fin_ins)
                                        <option value="{{ $fin_ins->id }}">{{ $fin_ins->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label"><b>Description</b></label>
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="viewModalLabel">
                            <i class="fas fa-wallet me-2"></i>Wallet Details
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="wallet-info">
                                    <i class="fas fa-user text-primary"></i>
                                    <span class="info-label">Name:</span>
                                    <span class="info-value" id="view-name"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-money-bill-wave text-primary"></i>
                                    <span class="info-label">Total Amount:</span>
                                    <span class="info-value" id="view-total_amount"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-piggy-bank text-primary"></i>
                                    <span class="info-label">Wallet Type:</span>
                                    <span class="info-value" id="view-type"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="wallet-info">
                                    <i class="fas fa-university text-primary"></i>
                                    <span class="info-label">Financial Institution:</span>
                                    <span class="info-value" id="view-fin"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    <span class="info-label">Description:</span>
                                    <span class="info-value" id="view-desc"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-toggle-on text-primary"></i>
                                    <span class="info-label">Status:</span>
                                    <span class="info-value" id="view-is_active"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                    <span class="info-label">Created at:</span>
                                    <span class="info-value" id="view-created_at"></span>
                                </div>
                                <div class="wallet-info">
                                    <i class="fas fa-calendar-check text-primary"></i>
                                    <span class="info-label">Last Updated:</span>
                                    <span class="info-value" id="view-updated_at"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5><i class="fas fa-exchange-alt me-2"></i>Recent Transactions</h5>
                        <div class="transaction-list" id="transaction-list">

                        </div>
                    </div>
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
                        <p class="mb-0">Are you sure you want to delete this wallet?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .wallet-info {
                margin-bottom: 15px;
            }

            .wallet-info i {
                margin-right: 10px;
                width: 20px;
            }

            .info-label {
                font-weight: bold;
                margin-right: 5px;
            }

            .info-value {
                color: #6c757d;
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
        </style>
    @endpush

    @push('scripts')
        <script>
            $('.action-icon').click(function() {
                var walletId = $(this).data('id');
                var action = $(this).data('action');

                if (action === 'view') {
                    var walletId = Number($(this).data("id"));
                    $.ajax({
                        url: 'wallets/' + walletId,
                        method: "GET",
                        data: {
                            id: walletId
                        },
                        dataType: 'json',
                        success: function(data) {

                            function formatDateTime(dateString) {
                                if (!dateString) return "N/A";
                                const date = new Date(dateString);
                                const day = String(date.getDate()).padStart(2, '0');
                                const month = String(date.getMonth() + 1).padStart(2,
                                    '0');
                                const year = date.getFullYear();
                                const hours = String(date.getHours()).padStart(2, '0');
                                const minutes = String(date.getMinutes()).padStart(2, '0');
                                const seconds = String(date.getSeconds()).padStart(2, '0');
                                return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
                            }

                            if (data) {
                                $('#view-name').text(data.name !== null ? data.name : "");
                                $('#view-total_amount').text(data.amount !== null ? data.amount : 0.00);
                                $('#view-currency').text(data.currency !== null ? data.currency : "");
                                $('#view-type').text(data.wallet_type !== null ? data.wallet_type.name :
                                    "");
                                $('#view-fin').text(data.fin_institute !== null ? data.financial_institute
                                    .name : "");
                                $('#view-desc').text(data.description !== null ? data.description : "");
                                $('#view-is_active').text(data.is_active === 1 ? "Active" : "Inactive");
                                $('#view-created_at').text(formatDateTime(data.created_at) !== null ?
                                    formatDateTime(data.created_at) : "");
                                $('#view-updated_at').text(formatDateTime(data.updated_at) !== null ?
                                    formatDateTime(data.updated_at) : "");
                            } else {
                                console.error('No data received.');
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the wallet data:", error);
                        }
                    });

                    $.ajax({
                        url: '{{ route('getTransactionByWallet') }}',
                        method: "GET",
                        data: {
                            id: walletId
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
                    var walletId = Number($(this).data("id"));
                    $("#saveChanges").attr("data-id", walletId);
                    $("#id").val(walletId);
                    $('#updateForm').attr('action', '/wallets/' + walletId);
                    $.ajax({
                        url: 'wallets/' + walletId,
                        method: "GET",
                        data: {
                            id: walletId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $('#updateForm #name').val(data.name);
                                $('#updateForm #wallet_type').val(data.wallet_type.id);
                                $('#updateForm #amount').val(data.amount);
                                $('#updateForm #fin_institute').val(data.financial_institute.id);
                                $('#updateForm #description').val(data.description);
                                $('#updateForm #is_active').val(data.is_active);
                                $('#update-form').modal('show');
                            } else {
                                console.error('No data received.');
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the wallet data:", error);
                        }
                    });
                } else if (action === 'delete') {
                    $('#confirmDelete').data('id', walletId);
                    var deleteUrl = '{{ route('wallets.destroy', ':id') }}';
                    deleteUrl = deleteUrl.replace(':id', walletId);
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
                url: '{{ route('getExpenseBreakdown') }}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        const categories = data.data.map(item => item.category_name);
                        const amounts = data.data.map(item => parseFloat(item.total));

                        const ctx = document.getElementById('expenseBreakdown').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: categories,
                                datasets: [{
                                    label: 'Expenses by Category',
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
                    console.error('Error fetching expense breakdown data:', error);
                }
            });

            $.ajax({
                url: '{{ route('getMonthlyIncomeTrend') }}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        console.log(data);
                        const months = data.data.map(item => item.month);
                        const totals = data.data.map(item => parseFloat(item.total));

                        const ctx = document.getElementById('monthlySpendingTrend').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: 'Total Income by Month',
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
                                            text: 'Total Income (RM)'
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
        </script>
    @endpush
</x-app-layout>
