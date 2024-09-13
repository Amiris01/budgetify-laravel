<x-app-layout>
    @section('title', 'Budgetify | Manage Transaction')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Manage Transactions
            </h1>
        </div>

        <div class="container-fluid mt-4 px-5">
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Transactions</h5>
                            <h2 class="card-text display-4 mb-0" id="total-transactions">{{ $data['totalTransaction'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Income</h5>
                            <h2 class="card-text display-4 mb-0" id="total-income">RM
                                {{ number_format($data['totalIncome'], 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Expenses</h5>
                            <h2 class="card-text display-4 mb-0" id="total-expenses">RM
                                {{ number_format($data['totalExpenses'], 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Wallet Balance</h5>
                            <h2 class="card-text display-4 mb-0" id="balance">RM
                                {{ number_format($data['totalBalance'], 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-flex align-items-stretch">
                <div class="col-md-8 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Transaction History</h5>
                            <div id="transaction-list" class="transaction-list">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Transaction Types</h5>
                            <canvas id="transactionTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="row">
                <div class="col">
                    <button class="btn btn-info addTransaction action-icon" data-action="add" style="float: right">
                        Add Transaction
                    </button>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            @if ($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped sortable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Amount (RM)</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $index => $transaction)
                                <tr class="item">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaction->trans_type }}</td>
                                    <td>{{ $transaction->categories->name }}</td>
                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->trans_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="#" class="action-icon" data-id="{{ $transaction->id }}"
                                            data-action="view" title="View Record">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $transaction->id }}"
                                            data-action="update" title="Update Record">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $transaction->id }}"
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
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-danger">
                    <em>No records were found.</em>
                </div>
            @endif
        </div>

        <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header pb-4 pt-3 px-4">
                        <h5 class="modal-title" id="transactionModalLabel">Record a Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pb-5 pt-4 px-4">
                        <div class="d-flex justify-content-around">
                            <button type="button" class="btn btn-outline-success btn-lg rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#incomeAllocationModal">
                                <i class="fas fa-plus-circle me-2"></i> Record Income
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-lg rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#allocationModal">
                                <i class="fas fa-minus-circle me-2"></i> Record Expense
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="incomeAllocationModal" tabindex="-1" aria-labelledby="incomeAllocationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header pb-4 pt-3 px-3">
                        <h5 class="modal-title" id="incomeAllocationModalLabel">Allocate a Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pb-5 pt-4 px-3">
                        <div class="d-flex justify-content-around">
                            <button type="button" class="btn btn-outline-warning btn-lg rounded-pill"
                                data-income_category="event">
                                <i class="fas fa-calendar-alt me-2"></i> Events
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill"
                                data-income_category="income">
                                <i class="fas fa-wallet me-2"></i> Normal Income
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="allocationModal" tabindex="-1" aria-labelledby="allocationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header pb-4 pt-3 px-3">
                        <h5 class="modal-title" id="allocationModalLabel">Allocate a Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pb-5 pt-4 px-3">
                        <div class="d-flex justify-content-around">
                            <button type="button" class="btn btn-outline-warning btn-lg rounded-pill"
                                data-category="event">
                                <i class="fas fa-calendar-alt me-2"></i> Events
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill"
                                data-category="expense">
                                <i class="fas fa-wallet me-2"></i> Normal Expenses
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="incomeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Income</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addIncomeForm" enctype="multipart/form-data" method="POST"
                            action="{{ route('transactions.store') }}">
                            @csrf
                            <input type="hidden" name="trans_type" id="trans_type" value="Income">
                            <input type="hidden" name="table_ref1" id="table_ref1">
                            <div id="eventSection">
                                <label for="event_id1" class="form-label"><b>Event Name</b></label>
                                <select class="form-select" id="event_id1" name="event_id1">
                                    <option value="" selected disabled>Select Event</option>
                                    @foreach ($userEvents as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="wallet_id1" class="form-label"><b>Wallet</b></label>
                                <select class="form-select" id="wallet_id1" name="wallet_id1">
                                    <option value="" selected disabled>Select Wallet</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">
                                            {{ $wallet->name . ' (' . $wallet->financialInstitute->name . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="amount1" class="form-label"><b>Amount</b></label>
                                <input type="number" name="amount1" id="amount1" class="form-control"
                                    step="0.01" />
                            </div>
                            <div>
                                <label for="category1" class="form-label"><b>Category</b></label>
                                <select class="form-select" id="category1" name="category1">
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($incomeCategory as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="description1" class="form-label"><b>Description</b></label>
                                <textarea name="description1" id="description1" cols="5" class="form-control"></textarea>
                            </div>
                            <div>
                                <label for="trans_date1" class="form-label"><b>Transaction Date</b></label>
                                <input type="date" name="trans_date1" id="trans_date1" class="form-control" />
                            </div>
                            <div>
                                <label for="attachment1" class="form-label"><b>Attachment</b></label>
                                <input type="file" name="attachment1" id="attachment1" class="form-control">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="add-income">
                            Save changes
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="expenseModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addExpenseForm" enctype="multipart/form-data" method="POST"
                            action="{{ route('transactions.store') }}">
                            @csrf
                            <input type="hidden" name="table_ref" id="table_ref">
                            <input type="hidden" name="trans_type" id="trans_type" value="Expense">
                            <div id="eventSection1">
                                <label for="event_id" class="form-label"><b>Event Name</b></label>
                                <select class="form-select" id="event_id" name="event_id">
                                    <option value="" selected disabled>Select Event</option>
                                    @foreach ($userEvents as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="category" class="form-label"><b>Category</b></label>
                                <select class="form-select" id="category" name="category">
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($expenseCategory as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="amount" class="form-label"><b>Amount</b></label>
                                <input type="number" name="amount" id="amount" class="form-control"
                                    step="0.01" />
                            </div>
                            <div>
                                <label for="wallet_id" class="form-label"><b>Wallet</b></label>
                                <select class="form-select" id="wallet_id" name="wallet_id">
                                    <option value="" selected disabled>Select Wallet</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">
                                            {{ $wallet->name . ' (' . $wallet->financialInstitute->name . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="description" class="form-label"><b>Description</b></label>
                                <textarea name="description" id="description" cols="5" class="form-control"></textarea>
                            </div>
                            <div>
                                <label for="trans_date" class="form-label"><b>Transaction Date</b></label>
                                <input type="date" name="trans_date" id="trans_date" class="form-control" />
                            </div>
                            <div>
                                <label for="attachment" class="form-label"><b>Attachment</b></label>
                                <input type="file" name="attachment" id="attachment" class="form-control">
                            </div>
                    </div>
                    <div class="container mt-2">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="allocate_budget"
                                        name="allocate_budget" />
                                    <label class="form-check-label" for="allocate_budget"><b>Allocate to
                                            Budget?</b></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="budgetSection" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="budget_id" class="form-label"><b>Budget</b></label>
                                    <select class="form-select" id="budget_id" name="budget_id">
                                        <option value="" selected disabled>Select Budget</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="add-expense">
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
                        <h4 class="modal-title" id="viewModalLabel">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Transaction Details
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="info-section">
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <h5 class="mb-3">
                                        <i class="fas fa-wallet me-2"></i>Wallet
                                    </h5>
                                    <p id="modal-wallet-name" class="fs-5"></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5 class="mb-3">
                                        <i class="fas fa-bullseye me-2"></i>Budget
                                    </h5>
                                    <p id="modal-budget-title" class="fs-5"></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5 class="mb-3">
                                        <i class="fas fa-calendar me-2"></i>Date
                                    </h5>
                                    <p id="modal-transaction-date" class="fs-5"></p>
                                </div>
                            </div>
                            <div class="text-center">
                                <span id="modal-transaction-amount" class="transaction-amount">
                                    <i id="modal-transaction-icon" class="fas me-2"></i>
                                </span>
                                <p id="modal-transaction-type" class="text-muted mb-0"></p>
                            </div>
                        </div>
                        <div class="details-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Details</h5>
                                    <ul id="modal-transaction-details" class="list-group list-group-flush">
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-paperclip me-2"></i>Attachment</h5>
                                    <div id="view-attachment" class="text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted text-end mt-3">
                            <small id="modal-creation-date"></small>
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
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p class="mb-0">Are you sure you want to delete this transaction?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="updateIncomeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Income</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateIncomeForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="trans_type" id="trans_type" value="Income">
                            <div id="eventSection_income">
                                <label for="event_id_income" class="form-label"><b>Event Name</b></label>
                                <select class="form-select" id="event_id_income" name="event_id_income" required>
                                    <option value="" selected disabled>Select Event</option>
                                    @foreach ($userEvents as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="wallet_id_income" class="form-label"><b>Wallet</b></label>
                                <select class="form-select" id="wallet_id_income" name="wallet_id_income" required>
                                    <option value="" selected disabled>Select Wallet</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">
                                            {{ $wallet->name . ' (' . $wallet->financialInstitute->name . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="amount_income" class="form-label"><b>Amount</b></label>
                                <input type="number" name="amount_income" id="amount_income" class="form-control" />
                            </div>
                            <div>
                                <label for="category_income" class="form-label"><b>Category</b></label>
                                <select class="form-select" id="category_income" name="category_income" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($incomeCategory as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="description_income" class="form-label"><b>Description</b></label>
                                <textarea name="description_income" id="description_income" cols="5" class="form-control"></textarea>
                            </div>
                            <div>
                                <label for="trans_date_income" class="form-label"><b>Transaction Date</b></label>
                                <input type="date" name="trans_date_income" id="trans_date_income"
                                    class="form-control" />
                            </div>
                            <div>
                                <label for="attachment_income" class="form-label"><b>Attachment</b></label>
                                <input type="file" name="attachment_income" id="attachment_income"
                                    class="form-control">
                            </div>
                            <div id="attachment_preview_income"
                                class="mt-2 d-flex justify-content-center align-items-center"
                                style="display: none; height: 200px;">
                                <img id="attachment_image_income" src="" alt="Attachment Preview"
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="update-income">
                            Save changes
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="updateExpenseModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateExpenseForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id_expense" id="id_expense">
                            <input type="hidden" name="trans_type" id="trans_type" value="Expense">
                            <div id="eventSection_expense">
                                <label for="event_expense" class="form-label"><b>Event Name</b></label>
                                <select class="form-select" id="event_expense" name="event_expense" required>
                                    <option value="" selected disabled>Select Event</option>
                                    @foreach ($userEvents as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="category_expense" class="form-label"><b>Category</b></label>
                                <select class="form-select" id="category_expense" name="category_expense" required>
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($expenseCategory as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="amount_expense" class="form-label"><b>Amount</b></label>
                                <input type="number" name="amount_expense" id="amount_expense" class="form-control" />
                            </div>
                            <div>
                                <label for="wallet_expense" class="form-label"><b>Wallet</b></label>
                                <select class="form-select" id="wallet_expense" name="wallet_expense" required>
                                    <option value="" selected disabled>Select Wallet</option>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}">
                                            {{ $wallet->name . ' (' . $wallet->financialInstitute->name . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="desc_expense" class="form-label"><b>Description</b></label>
                                <textarea name="desc_expense" id="desc_expense" cols="5" class="form-control"></textarea>
                            </div>
                            <div>
                                <label for="trans_date_expense" class="form-label"><b>Transaction Date</b></label>
                                <input type="date" name="trans_date_expense" id="trans_date_expense"
                                    class="form-control" />
                            </div>
                            <div>
                                <label for="attachment_expense" class="form-label"><b>Attachment</b></label>
                                <input type="file" name="attachment_expense" id="attachment_expense"
                                    class="form-control">
                            </div>
                            <div id="attachment_preview_expense"
                                class="mt-2 d-flex justify-content-center align-items-center"
                                style="display: none; height: 200px;">
                                <img id="attachment_image_expense" src="" alt="Attachment Preview"
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                    </div>
                    <div class="container mt-2">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="allocate_budget_update"
                                        name="allocate_budget_update" />
                                    <label class="form-check-label" for="allocate_budget_update"><b>Allocate to
                                            Budget?</b></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="budgetSection1" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_budget" class="form-label"><b>Budget</b></label>
                                    <select class="form-select" id="update_budget" name="update_budget">
                                        <option value="" selected disabled>Select Budget</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="update-expense">
                            Save changes
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .card {
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }

            .card-body {
                padding: 30px;
            }

            .form-control {
                border-radius: 5px;
            }

            .transaction-item {
                padding: 10px 0;
                border-bottom: 1px solid #ddd;
            }

            .transaction-item:last-child {
                border-bottom: none;
            }

            .income {
                color: green;
            }

            .expense {
                color: red;
            }

            .transaction-date {
                font-weight: bold;
                font-size: 0.9rem;
            }

            .transaction-time {
                font-size: 0.8rem;
                color: #888;
            }

            .transaction-list {
                max-height: 300px;
                overflow-y: auto;
            }

            .transaction-amount {
                font-size: 2rem;
                font-weight: bold;
            }

            .info-section {
                background-color: #f8f9fa;
                border-radius: 0.5rem;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .details-section {
                background-color: #fff;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                padding: 1.5rem;
            }

            #view-modal .modal-body .fas {
                color: #007bff;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $('.action-icon').click(function() {
                var budgetId = $(this).data('id');
                var action = $(this).data('action');

                if (action === 'view') {

                    function populateTransactionModal(data) {
                        $('#modal-wallet-name').text(data.wallet.name);
                        $('#modal-budget-title').text(data.budget && data.budget.title ? data.budget.title :
                            'No Allocation');

                        var amountSpan = $('#modal-transaction-amount');
                        amountSpan.text('$' + parseFloat(data.amount).toFixed(2));

                        if (data.trans_type === 'Expense') {
                            amountSpan.removeClass('income').addClass('expense');
                            $('#modal-transaction-icon').removeClass('fa-arrow-up').addClass('fa-arrow-down');
                            $('#modal-transaction-type').text('Expense');
                        } else {
                            amountSpan.removeClass('expense').addClass('income');
                            $('#modal-transaction-icon').removeClass('fa-arrow-down').addClass('fa-arrow-up');
                            $('#modal-transaction-type').text('Income');
                        }

                        var formattedDate = new Date(data.trans_date).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        }).split('/').join('/');

                        $('#modal-transaction-date').text(formattedDate);

                        if (data.table_ref != null) {
                            var capitalizedTableRef = data.table_ref.charAt(0).toUpperCase() + data.table_ref.slice(1);
                        }

                        var detailsList = $('#modal-transaction-details');
                        detailsList.empty();
                        detailsList.append('<li class="list-group-item"><strong>Description:</strong> ' + data
                            .description + '</li>');
                        detailsList.append('<li class="list-group-item"><strong>Category:</strong> ' + data.categories
                            .name +
                            '</li>');
                        if (capitalizedTableRef != null) {
                            detailsList.append('<li class="list-group-item"><strong>Reference:</strong> ' +
                                capitalizedTableRef + '</li>');
                        }
                        if (capitalizedTableRef == 'Events') {
                            detailsList.append('<li class="list-group-item"><strong>Event Name:</strong> ' + data
                                .event.name + '</li>');
                        }

                        var attachmentDiv = $('#view-attachment');
                        if (data.attachment && data.attachment !== 'attachments/no images.jpg') {
                            var attachmentUrl = '{{ Storage::url('') }}' + data.attachment;
                            attachmentDiv.html('<img src="' + attachmentUrl +
                                '" alt="Transaction Attachment" class="img-fluid rounded" style="max-width: 100%; height: 200px; object-fit: cover;" />'
                            );
                        } else {
                            attachmentDiv.html('<p>No attachment available</p>');
                        }

                        var createdAt = new Date(data.created_at);
                        var day = String(createdAt.getDate()).padStart(2, '0');
                        var month = String(createdAt.getMonth() + 1).padStart(2, '0');
                        var year = createdAt.getFullYear();
                        var hours = String(createdAt.getHours()).padStart(2, '0');
                        var minutes = String(createdAt.getMinutes()).padStart(2, '0');
                        var seconds = String(createdAt.getSeconds()).padStart(2, '0');
                        var formattedDate = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
                        $('#modal-creation-date').text('Created: ' + formattedDate);
                    }

                    var transactionId = Number($(this).data("id"));
                    $.ajax({
                        url: 'transactions/' + transactionId,
                        method: "GET",
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                populateTransactionModal(data);
                                $('#view-modal').modal('show');
                            } else {
                                console.error('No data received.');
                            }
                        },
                        error: function(error) {
                            console.error("There was an error fetching the transaction data:", error);
                        }
                    });
                } else if (action === 'update') {
                    var transactionId = Number($(this).data("id"));
                    $("#update-income").attr("data-id", transactionId);
                    $("#id").val(transactionId);
                    $("#id_expense").val(transactionId);
                    $('#updateIncomeForm').attr('action', '/transactions/' + transactionId);
                    $('#updateExpenseForm').attr('action', '/transactions/' + transactionId);
                    $.ajax({
                        url: 'transactions/' + transactionId,
                        method: "GET",
                        data: {
                            id: transactionId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                console.log(data);
                                if (data.trans_type === 'Income') {
                                    $('#wallet_id_income').val(data.wallet_id !== null ? data.wallet_id :
                                        "");
                                    $('#amount_income').val(data.amount !== null ? data.amount : "");
                                    $('#category_income').val(data.category !== null ? data.category : "");
                                    $('#description_income').val(data.description !== null ? data
                                        .description :
                                        "");
                                    $('#trans_date_income').val(data.trans_date !== null ? data.trans_date :
                                        "");
                                    $('#eventSection_income').addClass('d-none');
                                    if (data.table_ref === 'events' && data.id_ref !== null) {
                                        $('#eventSection_income').removeClass('d-none');
                                        $('#event_id_income').val(data.id_ref);
                                    }
                                    if (data.attachment) {
                                        $('#attachment_image_income').attr('src',
                                            '{{ Storage::url('') }}' + data.attachment);
                                        $('#attachment_preview_income').show();
                                    } else {
                                        $('#attachment_preview_income').hide();
                                    }
                                    $('#updateIncomeModal').modal('show');
                                } else if (data.trans_type === 'Expense') {
                                    $('#wallet_expense').val(data.wallet_id !== null ? data.wallet_id : "");
                                    $('#amount_expense').val(data.amount !== null ? data.amount : "");
                                    $('#category_expense').val(data.category !== null ? data.category : "");
                                    $('#desc_expense').val(data.description !== null ? data.description :
                                        "");
                                    $('#trans_date_expense').val(data.trans_date !== null ? data
                                        .trans_date : "");
                                    $('#eventSection_expense').addClass('d-none');
                                    if (data.table_ref === 'events' && data.id_ref !== null) {
                                        $('#eventSection_expense').removeClass('d-none');
                                        $('#event_expense').val(data.id_ref);
                                    }
                                    if (data.attachment) {
                                        $('#attachment_image_expense').attr('src',
                                            '{{ Storage::url('') }}' + data.attachment);
                                        $('#attachment_preview_expense').show();
                                    } else {
                                        $('#attachment_preview_expense').hide();
                                    }
                                    if (data.budget_id) {
                                        $('#allocate_budget_update').prop('checked', true);
                                        populateBudgetDropdown('#update_budget', data.category);
                                        setTimeout(function() {
                                            $('#update_budget').val(data.budget_id);
                                        }, 500);
                                        $('#budgetSection1').show();
                                    } else {
                                        $('#allocate_budget_update').prop('checked', false);
                                        $('#budgetSection1').hide();
                                    }

                                    $('#updateExpenseModal').modal('show');
                                }
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
                    var deleteUrl = '{{ route('transactions.destroy', ':id') }}';
                    deleteUrl = deleteUrl.replace(':id', budgetId);
                    $('#deleteForm').attr('action', deleteUrl);
                    $('#confirm-modal').modal('show');
                } else if (action === 'add') {
                    $('#transactionModal').modal('show');
                }
            });

            $('[data-income_category]').on('click', function() {
                var category = $(this).data('income_category');
                var modal = new bootstrap.Modal($('#incomeModal')[0]);

                $('#apparelSection').addClass('d-none');
                $('#eventSection').addClass('d-none');
                $('#expenseSection').addClass('d-none');

                if (category === 'apparel') {
                    $('#apparelSection').removeClass('d-none');
                    $('#table_ref1').val('apparels');
                } else if (category === 'event') {
                    $('#eventSection').removeClass('d-none');
                    $('#table_ref1').val('events');
                } else if (category === 'expense') {
                    $('#expenseSection').removeClass('d-none');
                    $('#table_ref1').val('normal');
                }

                $('#incomeAllocationModal').modal('hide');

                modal.show();
            });

            $('[data-category]').on('click', function() {
                var category = $(this).data('category');
                var modal = new bootstrap.Modal($('#expenseModal')[0]);

                $('#apparelSection').addClass('d-none');
                $('#eventSection1').addClass('d-none');
                $('#expenseSection').addClass('d-none');

                if (category === 'apparel') {
                    $('#apparelSection').removeClass('d-none');
                    $('#table_ref').val('apparels');
                } else if (category === 'event') {
                    $('#eventSection1').removeClass('d-none');
                    $('#table_ref').val('events');
                } else if (category === 'expense') {
                    $('#expenseSection').removeClass('d-none');
                    $('#table_ref').val('normal');
                }

                $('#allocationModal').modal('hide');

                modal.show();
            });

            function populateBudgetDropdown(selector, category) {
                $.ajax({
                    url: '/getBudgetByCategory',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        category: category
                    },
                    success: function(data) {
                        let $dropdown = $(selector);
                        $dropdown.empty();
                        $dropdown.append($('<option>', {
                            value: '',
                            text: 'Select Budget',
                            selected: true,
                            disabled: true
                        }));
                        $.each(data, function(index, item) {
                            $dropdown.append($('<option>', {
                                value: item.id,
                                text: item.title
                            }));
                        });
                    },
                    error: function() {
                        alert('Failed to load data.');
                    }
                });
            }

            $('#allocate_budget').closest('.form-check').hide();
            $('#budgetSection').hide();

            $('#category').change(function() {
                const selectedCategory = $(this).val();
                if (selectedCategory) {
                    $('#allocate_budget').closest('.form-check').show();
                } else {
                    $('#allocate_budget').closest('.form-check').hide();
                    $('#budgetSection').hide();
                }
            });

            $('#category').change(function() {
                const selectedCategory = $(this).val();
                if (selectedCategory) {
                    $('#allocate_budget').closest('.form-check').show();
                    if ($('#allocate_budget').is(':checked')) {
                        populateBudgetDropdown('#budget_id', selectedCategory);
                    }
                } else {
                    $('#allocate_budget').closest('.form-check').hide();
                    $('#budgetSection').hide();
                }
            });

            $('#allocate_budget').change(function() {
                if ($(this).is(':checked')) {
                    $('#budgetSection').show();
                    const selectedCategory = $('#category').val();
                    populateBudgetDropdown('#budget_id', selectedCategory);
                } else {
                    $('#budgetSection').hide();
                }
            });

            $('#allocate_budget_update').change(function() {
                if ($(this).is(':checked')) {
                    $('#budgetSection1').show();
                    const selectedCategory = $('#category_expense').val();
                    populateBudgetDropdown('#update_budget', selectedCategory);
                } else {
                    $('#budgetSection1').hide();
                }
            });

            $('#category_expense').change(function() {
                const selectedCategory = $('#category_expense').val();
                populateBudgetDropdown('#update_budget', selectedCategory);
            });



            $.ajax({
                url: '{{ route('getRecentTransaction') }}',
                method: "GET",
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
                                        <strong class="income">+$${parseFloat(transaction.amount).toFixed(2)}</strong>
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
                                        <strong class="expense">-$${parseFloat(transaction.amount).toFixed(2)}</strong>
                                    </div>
                                </div>
                            `;
                            }

                            transactionList.append(transactionItem);
                        });
                    } else {
                        // Display a message if there are no transactions
                        $('#transaction-list').html(
                            '<p class="text-center text-muted">No recent transactions found.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching transactions:', error);
                    $('#transaction-list').html(
                        '<p class="text-center text-danger">Error fetching transactions. Please try again later.</p>'
                    );
                }
            });


            $.ajax({
                url: '{{ route('getTransactionTypeDist') }}',
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        var incomeCount = data.data.incomeCount;
                        var expenseCount = data.data.expenseCount;

                        var ctx = document.getElementById('transactionTypeChart').getContext('2d');
                        var transactionTypeChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Income', 'Expense'],
                                datasets: [{
                                    label: 'Transaction Types',
                                    data: [incomeCount, expenseCount],
                                    backgroundColor: [
                                        'rgba(75, 192, 192, 0.6)', // Color for Income
                                        'rgba(255, 99, 132, 0.6)' // Color for Expense
                                    ],
                                    borderColor: [
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(255, 99, 132, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                var label = tooltipItem.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += tooltipItem.raw;
                                                label +=
                                                    ` (${((tooltipItem.raw / (incomeCount + expenseCount)) * 100).toFixed(2)}%)`;
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        // If no data is found
                        $('#transactionTypeChart').replaceWith(
                            '<p class="text-center text-muted">No transactions found to display.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching transactions:', error);
                    $('#transactionTypeChart').replaceWith(
                        '<p class="text-center text-danger">Error fetching transaction types. Please try again later.</p>'
                    );
                }
            });

            $('#confirmDelete').click(function() {
                $('#deleteForm').submit();
            });

            $('#update-income').on('click', function() {
                $('#updateIncomeForm').submit();
            });

            $('#update-expense').on('click', function() {
                $('#updateExpenseForm').submit();
            });
        </script>
    @endpush
</x-app-layout>
