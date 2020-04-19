<?php include('include/header.php'); ?>

  <div class="container">
    <!-- General Information -->
    <div class="card" style="margin-top:30px">
      <div class="card-body">
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <h2><i class="fas fa-users"></i></h2>
                </div>
                <h4><b id="total_users"></b> Total Users</h4>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-danger text-white o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <h2><i class="fas fa-user-minus"></i></h2>
                </div>
                <h4><b id="total_inactive_users"></b> Inactive Users</h4>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-success text-white o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <h2><i class="fas fa-user-plus"></i></h2>
                </div>
                <h4><b id="total_active_users"></b> Active Users</h4>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card bg-info text-white o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <h2><i class="fas fa-fw fa-users-cog"></i></h2>
                </div>
                <h4><b id="total_admins"></b> Total Admin</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <!-- Area Charts -->
    <div class="row" style="margin-bottom: 247px;">
      <div class="col-lg-8">
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Monthly Created Users</div>
          <div class="card-body">
            <canvas id="createdUsersChart" width="100%" height="50"></canvas>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-pie"></i>
            Gender Distribution</div>
          <div class="card-body">
            <canvas id="genderDistribution" width="100%" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('include/footer.php'); ?>

  <script>
    $(document).ready(function(){

      addInformation();
      createdUsersChart();
      genderDistributionChart ();

      function addInformation(){
        $.ajax({
          url:"admin_action.php",
          method:"POST",
          data:{action:'add_info'},
          dataType: "json",
          success:function(data){
            $('#total_users').text(data['total_users']);
            $('#total_inactive_users').text(data['total_inactive_users']);
            $('#total_active_users').text(data['total_active_users']);
            $('#total_admins').text(data['total_admins']);
          }
        });
      }

      function createdUsersChart(){

        $.ajax({
          url:"admin_action.php",
          method:"POST",
          data:{action:'user_info'},
          dataType: "json",
          success:function(data){

            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#292b2c';

            // Line Chart Created Users
            var createdUsersChart = document.getElementById("createdUsersChart");
            var myLineChart = new Chart(createdUsersChart, {
              type: 'line',
              data: {
                labels: data.date,
                datasets: [{
                  label: "Users Created",
                  lineTension: 0.3,
                  backgroundColor: "rgba(2,117,216,0.2)",
                  borderColor: "rgba(2,117,216,1)",
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(2,117,216,1)",
                  pointBorderColor: "rgba(255,255,255,0.8)",
                  pointHoverRadius: 5,
                  pointHoverBackgroundColor: "rgba(2,117,216,1)",
                  pointHitRadius: 50,
                  pointBorderWidth: 2,
                  data: data.users,
                }],
              },
              options: {
                scales: {
                  xAxes: [{
                    time: {
                      unit: 'date'
                    },
                    gridLines: {
                      display: false
                    },
                    ticks: {
                      maxTicksLimit: 7
                    }
                  }],
                  yAxes: [{
                    ticks: {
                      min: 0,
                      max: 100,
                      maxTicksLimit: 5
                    },
                    gridLines: {
                      color: "rgba(0, 0, 0, .125)",
                    }
                  }],
                },
                legend: {
                  display: false
                }
              }
            });
          }
        });
      }

      function genderDistributionChart (){
        $.ajax({
          url:"admin_action.php",
          method:"POST",
          data:{action:'gender_info'},
          dataType: "json",
          success:function(data){
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#292b2c';

            // Pie Chart of Gender Distribution
            var genderDistribution = document.getElementById("genderDistribution");
            var genderDistribution = new Chart(genderDistribution, {
              type: 'doughnut',
              data: {
                labels: ["Male", "Female"],
                datasets: [{
                  data: [data.total_male, data.total_female],
                  backgroundColor: ['#007bff', '#dc3545'],
                }],
              },
            });
          }
        });
      }
    });
  </script>