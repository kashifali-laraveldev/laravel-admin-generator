@extends('laravel-admin::layout.dashboard')
@section('content')
    <section>
        <div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="card sm-card-shadow p-10">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                <i class="fa-solid fa-universal-access fa-lg fa-2xl text-success"></i>
                            </div>
                            <div>
                                <div class="text-large font-bold d-flex justify-content-end">{{ $usersCount }}</div>
                                <div class="text-medium text-grey mt-1">Total User's</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card sm-card-shadow p-10">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                <i class="fa-solid fa-user-plus fa-2xl text-info"></i>
                            </div>
                            <div>
                                <div class="text-large font-bold d-flex justify-content-end">{{ $newUsersCount }}</div>
                                <div class="text-medium text-grey mt-1">New User's</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card sm-card-shadow p-10">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                <i class="fa-solid fa-user-slash fa-2xl text-danger"></i>
                            </div>
                            <div>
                                <div class="text-large font-bold d-flex justify-content-end">{{ $unConfirmedUsersCount }}</div>
                                <div class="text-medium text-grey mt-1">UnConfirmed User's</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card sm-card-shadow p-10">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                <i class="fa-solid fa-user fa-2xl text-warning"></i>
                            </div>
                            <div>
                                <div class="text-large font-bold d-flex justify-content-end">{{ $confirmedUsersCount }}</div>
                                <div class="text-medium text-grey mt-1">Confirmed User's</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card sm-card-shadow">
                    <div class="d-flex justify-content-between p-3">
                        <div>Registration History</div>
                    </div>
                    <hr class="hr-custome">
                    <div class="p-3">
                        <canvas id="lineChart" width="400" height="335px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card sm-card-shadow">
                    <div class="d-flex justify-content-between p-3">
                        <div>Latest Registration</div>
                        <div class="text-green">
                            <a style="color:green;text-decoration:none;" href="{{ url(PREFIX_ADMIN_FOR_ROUTES . 'users') }}">View All</a>
                        </div>
                    </div>
                    <hr class="hr-custome">
                    <div class="user-list-container px-3">
                        @foreach($lastestUserList as $user)
                            <div class="d-flex mt-3">
                                <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle avatar-size"
                                    alt="Avatar" />
                                <div class="ml-10">
                                    <div class="text-medium text-dark">{{ $user->name }}</div>
                                    <div class="text-small text-grey">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        // Initialize Months in chart x index side
        var months = [];

        // Initialize users count in y axis side
        var users = [];

        // Push month names in array
        @foreach ($UserGraph['months'] as $month)
            months.push("{{$month}}");
        @endforeach

        // Push user count in array
        @foreach ($UserGraph['users'] as $userCount)
            users.push("{{$userCount}}");
        @endforeach
        // Sample data for the line chart
        var data = {
            labels: months,
            datasets: [{
                label: "Registration",
                data: users,
                borderColor: 'green',
                borderWidth: 2,
                fill: false
            }]
        };

        // Configuration options for the chart
        var options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false // Hide x-axis vertical grid lines
                    },
                    scaleLabel: {
                        display: false // Hide x-axis top label
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                    },
                    ticks: {
                        beginAtZero: true, // Start the y-axis at 0
                        max: 100, // Set the maximum value on the y-axis to 100
                        stepSize: 10 // Define the interval between tick marks
                    }
                }]
            },
            legend: {
                display: false // Hide the legend
            }
        };

        // Get the canvas element and create the chart
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    </script>
@endsection
