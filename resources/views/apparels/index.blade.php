<x-app-layout>
    @section('title', 'Budgetify | Manage Apparels')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Manage Apparels
            </h1>
        </div>


        <div class="container mt-4 mb-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Apparels</h5>
                            <p class="card-text display-4 mb-0" id="totalApparels">{{ $data['totalApparels'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Expensive Apparels</h5>
                            <p class="card-text display-4 mb-0" id="expensiveApparels">{{ $data['expensiveApparels'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Apparels Purchased this Month</h5>
                            <p class="card-text display-4 mb-0" id="apparelsThisMonth">{{ $data['apparelsThisMonth'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">Total Spending</h5>
                            <p class="card-text display-4 mb-0" id="totalSpending">RM {{ $data['totalSpending'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="row">
                <div class="col">

                    <button class="btn btn-info addApparel" style="float: right">
                        Add Apparel
                    </button>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            @if ($apparels->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped sortable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Price (RM)</th>
                                <th>Brand</th>
                                <th>Remarks</th>
                                <th>Purchase Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($apparels as $index => $apparel)
                                <tr class="item">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $apparel->apparelType->name }}</td>
                                    <td>{{ $apparel->size }}</td>
                                    <td>{{ number_format($apparel->price, 2) }}</td>
                                    <td>{{ $apparel->apparelBrand->name }}</td>
                                    <td>{{ $apparel->remarks }}</td>
                                    <td>{{ Carbon\Carbon::parse($apparel->purchase_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="#" class="action-icon" data-id="{{ $apparel->id }}"
                                            data-action="view" title="View Record">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $apparel->id }}"
                                            data-action="update" title="Update Record">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="#" class="action-icon" data-id="{{ $apparel->id }}"
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
                    {{ $apparels->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-danger">
                    <em>No records were found.</em>
                </div>
            @endif
        </div>

        <div class="modal fade" id="add-form" tabindex="-1" aria-labelledby="addFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="addFormLabel">Add Apparels</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" enctype="multipart/form-data" method="POST"
                            action="{{ route('apparels.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type1" class="form-label"><small><b>Type</b></small></label>
                                        <select name="type1" id="type1" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Type</option>
                                            @foreach ($apparelType as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size1" class="form-label"><small><b>Size</b></small></label>
                                        <input type="text" name="size1" id="size1"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color1" class="form-label"><small><b>Color</b></small></label>
                                        <input type="text" name="color1" id="color1"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity1" class="form-label"><small><b>Quantity</b></small></label>
                                        <input type="number" name="quantity1" id="quantity1"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand1" class="form-label"><small><b>Brand</b></small></label>
                                        <select name="brand1" id="brand1" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price1" class="form-label"><small><b>Price</b></small></label>
                                        <input type="number" name="price1" id="price1"
                                            class="form-control form-control-sm" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="style1" class="form-label"><small><b>Style</b></small></label>
                                        <select name="style1" id="style1" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Style</option>
                                            @foreach ($styles as $style)
                                                <option value="{{ $style->id }}">{{ $style->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purchase_date1" class="form-label"><small><b>Purchase
                                                    Date</b></small></label>
                                        <input type="date" name="purchase_date1" id="purchase_date1"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remarks1" class="form-label"><small><b>Remarks</b></small></label>
                                <textarea name="remarks1" id="remarks1" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="add-apparel">Save changes</button>
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
                            <i class="fas fa-tshirt me-2"></i>Apparel Details
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="apparel-info">
                                    <i class="fas fa-tag"></i>
                                    <span class="info-label">Type:</span>
                                    <span class="info-value" id="view-type"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-ruler"></i>
                                    <span class="info-label">Size:</span>
                                    <span class="info-value" id="view-size"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-palette"></i>
                                    <span class="info-label">Color:</span>
                                    <span class="info-value" id="view-color"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-cubes"></i>
                                    <span class="info-label">Quantity:</span>
                                    <span class="info-value" id="view-quantity"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-trademark"></i>
                                    <span class="info-label">Brand:</span>
                                    <span class="info-value" id="view-brand"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="apparel-info">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span class="info-label">Price (RM):</span>
                                    <span class="info-value" id="view-price"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-vest"></i>
                                    <span class="info-label">Style:</span>
                                    <span class="info-value" id="view-style"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-comment"></i>
                                    <span class="info-label">Remarks:</span>
                                    <span class="info-value" id="view-remarks"></span>
                                </div>
                                <div class="apparel-info">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="info-label">Purchase Date:</span>
                                    <span class="info-value" id="view-purchase_date"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="apparel-info">
                                    <i class="fas fa-clock"></i>
                                    <span class="info-label">Created at:</span>
                                    <span class="info-value" id="view-created_at"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="apparel-info">
                                    <i class="fas fa-edit"></i>
                                    <span class="info-label">Last Updated:</span>
                                    <span class="info-value" id="view-updated_at"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="update-form" tabindex="-1" aria-labelledby="addFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title" id="addFormLabel">Update Apparels</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="form-label"><small><b>Type</b></small></label>
                                        <select name="type" id="type" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Type</option>
                                            @foreach ($apparelType as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size" class="form-label"><small><b>Size</b></small></label>
                                        <input type="text" name="size" id="size"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color" class="form-label"><small><b>Color</b></small></label>
                                        <input type="text" name="color" id="color"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity" class="form-label"><small><b>Quantity</b></small></label>
                                        <input type="number" name="quantity" id="quantity"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand" class="form-label"><small><b>Brand</b></small></label>
                                        <select name="brand" id="brand" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="form-label"><small><b>Price</b></small></label>
                                        <input type="number" name="price" id="price"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="style" class="form-label"><small><b>Style</b></small></label>
                                        <select name="style" id="style" class="form-select form-select-sm">
                                            <option value="" hidden>Select Apparel Style</option>
                                            @foreach ($styles as $style)
                                                <option value="{{ $style->id }}">{{ $style->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purchase_date" class="form-label"><small><b>Purchase
                                                    Date</b></small></label>
                                        <input type="date" name="purchase_date" id="purchase_date"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remarks" class="form-label"><small><b>Remarks</b></small></label>
                                <textarea name="remarks" id="remarks" class="form-control form-control-sm" rows="2"></textarea>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" id="saveChanges">Save changes</button>
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
                        <p class="mb-0">Are you sure you want to delete this apparel?</p>
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

            .apparel-info {
                margin-bottom: 1rem;
            }

            .apparel-info .info-label {
                font-weight: bold;
                color: #6c757d;
            }

            .apparel-info .info-value {
                color: #343a40;
            }

            .apparel-info i {
                width: 25px;
                color: #007bff;
            }

            .pagination {
                display: flex;
                padding-left: 0;
                list-style: none;
                border-radius: 0.25rem;
            }

            .page-item:first-child .page-link {
                margin-left: 0;
                border-top-left-radius: 0.25rem;
                border-bottom-left-radius: 0.25rem;
            }

            .page-item:last-child .page-link {
                border-top-right-radius: 0.25rem;
                border-bottom-right-radius: 0.25rem;
            }

            .page-item.active .page-link {
                z-index: 3;
                color: #fff;
                background-color: #007bff;
                border-color: #007bff;
            }

            .page-item.disabled .page-link {
                color: #6c757d;
                pointer-events: none;
                cursor: auto;
                background-color: #fff;
                border-color: #dee2e6;
            }

            .page-link {
                position: relative;
                display: block;
                padding: 0.5rem 0.75rem;
                margin-left: -1px;
                line-height: 1.25;
                color: #007bff;
                background-color: #fff;
                border: 1px solid #dee2e6;
            }

            .page-link:hover {
                z-index: 2;
                color: #0056b3;
                text-decoration: none;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $('.addApparel').click(function() {
                $('#add-form').modal('show');
            });

            $('.action-icon').click(function() {
                var budgetId = $(this).data('id');
                var action = $(this).data('action');

                if (action === 'view') {
                    var apparelId = Number($(this).data("id"));
                    $.ajax({
                        url: 'apparels/' + apparelId,
                        method: "GET",
                        data: {
                            id: apparelId
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#view-modal').modal('show');

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

                            const fields = {
                                'view-size': 'size',
                                'view-color': 'color',
                                'view-quantity': 'quantity',
                                'view-price': 'price',
                                'view-remarks': 'remarks',
                                'view-purchase_date': 'purchase_date',
                                'view-created_at': 'created_at',
                                'view-updated_at': 'updated_at'
                            };

                            for (const [fieldId, dataKey] of Object.entries(fields)) {
                                let value = data[dataKey] !== null ? data[dataKey] : "";

                                if (fieldId === 'view-price') {
                                    value = parseFloat(value).toFixed(2);
                                } else if (fieldId === 'view-purchase_date') {
                                    value = new Date(value).toLocaleDateString('en-GB');
                                } else if (fieldId === 'view-created_at' || fieldId === 'view-updated_at') {
                                    value = formatDateTime(value);
                                }

                                $('#' + fieldId).text(value);
                            }

                            $('#view-type').text(data.type ? data.apparel_type.name : "N/A");
                            $('#view-brand').text(data.brand ? data.apparel_brand.name : "N/A");
                            $('#view-style').text(data.style ? data.apparel_style.name : "N/A");
                        },
                        error: function(error) {
                            console.error("There was an error fetching the apparel data:", error);
                        }
                    });
                } else if (action === 'update') {
                    var apparelId = Number($(this).data("id"));
                    $("#saveChanges").attr("data-id", apparelId);
                    $("#id").val(apparelId);
                    $('#updateForm').attr('action', '/apparels/' + apparelId);
                    $.ajax({
                        url: 'apparels/' + apparelId,
                        method: "GET",
                        data: {
                            id: apparelId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                $('#updateForm #type').val(data.apparel_type.id);
                                $('#updateForm #size').val(data.size);
                                $('#updateForm #color').val(data.color);
                                $('#updateForm #quantity').val(data.quantity);
                                $('#updateForm #brand').val(data.apparel_brand.id);
                                $('#updateForm #price').val(data.price);
                                $('#updateForm #style').val(data.apparel_style.id);
                                $('#updateForm #purchase_date').val(data.purchase_date);
                                $('#updateForm #remarks').val(data.remarks);
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
                    $('#confirm-modal').modal('show');
                    $('#confirmDelete').data('id', budgetId);
                    var deleteUrl = '{{ route('apparels.destroy', ':id') }}';
                    deleteUrl = deleteUrl.replace(':id', budgetId);
                    $('#deleteForm').attr('action', deleteUrl);
                }
            });

            $('#saveChanges').on('click', function() {
                $('#updateForm').submit();
            });

            $('#confirmDelete').click(function() {
                $('#deleteForm').submit();
            });
        </script>
    @endpush
</x-app-layout>
