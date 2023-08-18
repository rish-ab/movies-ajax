<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Orders </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
      </nav>
    </div>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-success"><?= $message; ?></div>
    <?php } ?>

    <div class="row">
      
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <!-- <h4 class="card-title">All Movies</h4> -->
            <!-- <p class="card-description"> Add class <code>.table-bordered</code> -->
            </p>
            <table class="table table-bordered" id="table-movies">
              <thead>
                <tr>
                    <th>#</th>
                  <th> User </th>
                  <th>Payment</th>
                  <th>Movie</th>
                  <th>Time</th>
                  <th>Tickets</th>
                </tr>
              </thead>
              <tbody>

                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td><?= $order["order"]->id; ?></td>
                        <td> <?= $order["user"]->name; ?> </td>

                        <td>
                          <?php
                            if ($order["payment"] != null)
                            {
                              echo $order["payment"]->type . " (" . $order["payment"]->payment_id . ")";
                            }
                            ?>
                        </td>

                        <td>
                          <?= $order["movie"]->name; ?>
                        </td>

                        <td>
                          <?= $order["tickets"][0]["ticket"]->movie_time; ?>
                        </td>

                        <td>
                          <?php foreach ($order["tickets"] as $ticket): ?>
                            <div><?php echo $ticket["ticket"]->ticket_location; ?></div>
                          <?php endforeach; ?>
                        </td>
                    </tr>
                <?php } ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>

  </div>
  <!-- content-wrapper ends -->

  <script>
      function confirmDelete(self) {
        swal({
          title: "Confirm Delete ?",
          text: "Are you sure you want to delete this ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href = self.getAttribute("data-href");
          }
        });
        return false;
      }

      window.addEventListener("load", function () {
        $('#table-movies').DataTable({
          "order": [[
            0, "desc"
          ]]
        });
      });
  </script>