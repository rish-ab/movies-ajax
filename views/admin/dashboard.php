<?php

?>

<div class="content-wrapper">

    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="d-sm-flex align-items-baseline report-summary-header">
                  <h5 class="font-weight-semibold">Report Summary</h5> <span class="ml-auto">Updated Report</span> <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                </div>
              </div>
            </div>
            <div class="row report-inner-cards-wrapper">
              <div class=" col-md -6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <a href="<?= URL . 'admin/upcoming_movies' ?>" style="text-decoration: none; color: black;">
                        <span class="report-title">Upcoming movies</span>
                        <h4><?= count($comming_soon); ?></h4>
                    </a>
                </div>
                <div class="inner-card-icon bg-success">
                  <i class="icon-rocket"></i>
                </div>
              </div>
              <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <a href="<?= URL . 'admin/movies_played_so_far' ?>" style="text-decoration: none; color: black;">
                        <span class="report-title">Movies played so far</span>
                        <h4><?= $played_so_far; ?></h4>
                    </a>
                </div>
                <div class="inner-card-icon bg-danger">
                  <i class="icon-briefcase"></i>
                </div>
              </div>
              <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                    <a href="<?= URL . 'admin/currently_playing' ?>" style="text-decoration: none; color: black;">
                        <span class="report-title">Currently playing</span>
                        <h4><?= count($currently_playing); ?></h4>
                    </a>
                </div>
                <div class="inner-card-icon bg-warning">
                  <i class="icon-globe-alt"></i>
                </div>
              </div>
              <div class="col-md-6 col-xl report-inner-card">
                <div class="inner-card-text">
                  <span class="report-title">Tickets sold so far</span>
                  <h4><?= $tickets_sold; ?></h4>
                </div>
                <div class="inner-card-icon bg-primary">
                  <i class="icon-diamond"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline report-summary-header">
                                <h5 class="font-weight-semibold">Tickets sold so far</h5>
                                <span class="ml-auto">Updated Report</span>
                                <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row report-inner-cards-wrapper">
                        <div class=" col-md -6 col-xl report-inner-card">
                            <canvas id="chartTicketsSold" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline report-summary-header">
                                <h5 class="font-weight-semibold">Movies with ratings</h5>
                                <span class="ml-auto">Updated Report</span>
                                <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row report-inner-cards-wrapper">
                        <div class=" col-md -6 col-xl report-inner-card">
                            <canvas id="chartMoviesRatings" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        window.addEventListener("load", function () {
            dashboardCharts.ticketsSold();
            dashboardCharts.movieRatings();
        });

        var dashboardCharts = {

            ticketsSold: function () {
                $.ajax({
                    url: BASE_URL + "ajax/get_charts_data_tickets_sold",
                    method: "POST",
                    success: function (response) {
                        console.log(response);
                        var data = JSON.parse(response);
                        console.log(data);

                        var labels = [];
                        var dataArray = [];
                        var colors = [];

                        for (var a = 0; a < data.length; a++) {
                            labels.push(data[a].name);
                            dataArray.push(data[a].tickets);

                            // dynamicColors() is in admin/footer.php
                            colors.push(dynamicColors());
                        }

                        var ctx = document.getElementById('chartTicketsSold').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Tickets',
                                    data: dataArray,
                                    backgroundColor: colors,
                                    borderColor: dynamicColors(),
                                    hoverBorderColor: dynamicColors(),
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            stepSize: 1
                                        }
                                    }]
                                }
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr + " " + status + " " + error);
                    }
                });
            },

            movieRatings: function () {
                $.ajax({
                    url: BASE_URL + "ajax/get_charts_data_movies_ratings",
                    method: "POST",
                    success: function (response) {
                        console.log(response);
                        var data = JSON.parse(response);
                        console.log(data);

                        var labels = [];
                        var dataArray = [];
                        var colors = [];

                        for (var a = 0; a < data.length; a++) {
                            labels.push(data[a].name);
                            dataArray.push(data[a].ratings);

                            // dynamicColors() is in admin/footer.php
                            colors.push(dynamicColors());
                        }

                        var ctx = document.getElementById('chartMoviesRatings').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'horizontalBar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Ratings',
                                    data: dataArray,
                                    backgroundColor: colors,
                                    borderColor: dynamicColors(),
                                    hoverBorderColor: dynamicColors(),
                                    borderWidth: 1,
                                    fill: false
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            stepSize: 1
                                        }
                                    }]
                                }
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr + " " + status + " " + error);
                    }
                });
            }
        };

    </script>

</div>