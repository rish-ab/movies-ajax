<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Coupon codes </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Coupon codes</li>
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
            <h4 class="card-title">All Coupon codes</h4>
            <!-- <p class="card-description"> Add class <code>.table-bordered</code> -->
            </p>
            <table class="table table-bordered">
              <thead>
                <tr>
                    <th>#</th>
                    <th> Movie </th>
                    <th> Code </th>
                    <th>Discount</th>
                    <th> Action </th>
                </tr>
              </thead>
              <tbody>

                <?php $count = 1; foreach ($data as $d) { ?>
                    <tr>
                        <td><?= $count; ?></td>
                        <td> <?= $d->movie->name; ?> </td>
                        <td> <?= $d->coupon_code; ?> </td>
                        <td> <?= $d->discount; ?>% </td>

                        <td>
                            <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return confirmDelete(this);" data-href="<?= URL; ?>admin/coupon_codes/delete/<?= $d->id; ?>">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php $count++; } ?>

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
        text: "Are you sure you want to delete this coupon code ?",
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
  </script>