<x-app-layout>

    @section('title', 'Budgetify | Dashboard')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Dashboard
            </h1>
        </div>

        <div class="container mt-4">
            <div class="row">
                <!-- Budget vs Actual -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Budget vs. Actual</h5>
                            <canvas id="budgetVsActualChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Over-Budget Alerts -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Over-Budget Alerts</h5>
                            <div id="overBudgetAlerts" class="alert-container">
                                <!-- Alerts will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Net Worth Over Time -->
                <div class="col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Net Worth Over Time</h5>
                            <canvas id="netWorthOverTimeChart"></canvas>
                        </div>
                    </div>
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

            .btn-primary {
                background-color: #6666ff;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
            }

            .btn-primary:hover {
                background-color: #5555dd;
            }

            .alert-container {
                margin-top: 10px;
                max-height: 225px;
                overflow-y: auto;
            }

            .alert-item {
                margin-bottom: 10px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function fetchBudgetVsActual() {
                $.ajax({
                    url: '{{ route('getBudgetVsActual') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            const ctx = document.getElementById('budgetVsActualChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: data.data.labels,
                                    datasets: [{
                                        label: 'Budgeted',
                                        data: data.data.budgeted,
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    }, {
                                        label: 'Actual',
                                        data: data.data.actual,
                                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top'
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching budget vs actual data:', error);
                    }
                });
            }

            function fetchOverBudgetAlerts() {
                $.ajax({
                    url: '{{ route('getOverBudgetAlerts') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            $('#overBudgetAlerts').html(
                                data.data.map(alert => `
                  <div class="alert alert-warning alert-dismissible fade show alert-item" role="alert">
                    ${alert}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                `).join('')
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching over-budget alerts:', error);
                    }
                });
            }

            function fetchNetWorthOverTime() {
                $.ajax({
                    url: '{{ route('getNetWorthOverTime') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            const ctx = document.getElementById('netWorthOverTimeChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.data.months,
                                    datasets: [{
                                        label: 'Net Worth',
                                        data: data.data.netWorth,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        fill: false,
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
                                                text: 'Net Worth'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching net worth over time data:', error);
                    }
                });
            }

            $(document).ready(function() {
                fetchBudgetVsActual();
                fetchOverBudgetAlerts();
                fetchNetWorthOverTime();
            });
        </script>
    @endpush
</x-app-layout>
