<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Users </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Users</li>
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
            <h4 class="card-title">All Users</h4>
            <!-- <p class="card-description"> Add class <code>.table-bordered</code> -->
            </p>
            <table class="table table-bordered">
              <thead>
                <tr>
                    <th>#</th>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Mobile Number </th>
                    <th>Status</th>
                    <th> Action </th>
                </tr>
              </thead>
              <tbody>

                <?php $count = 1; foreach ($users as $user) { ?>
                    <tr>
                        <td><?= $count; ?></td>
                        <td> <?= $user->name; ?> </td>
                        <td> <?= $user->email; ?> </td>
                        <td> <?= $user->mobile_number; ?> </td>
                        <td> <?= $user->verified_at == null ? "<span class='unverified'>Unverified</span>" : "<span class='verified'>Verified</span>"; ?> </td>

                        <td>
                            <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return confirmDelete(this);" data-href="<?= URL; ?>admin/users/delete/<?= $user->id; ?>">
                                Delete
                            </a>

                            <?php if ($user->verified_at == null): ?>
                                <a class="btn btn-success btn-sm" href="javascript:void(0);" onclick="return confirmVerify(this);" data-href="<?= URL; ?>admin/users/verify/<?= $user->id; ?>">
                                    Verify
                                </a>
                            <?php endif; ?>
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
          text: "Are you sure you want to delete this ? All his movie ratings will be removed as well.",
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

      function confirmVerify(self) {
        swal({
          title: "Confirm Verify ?",
          text: "This user will be able to login and book tickets if verified ?",
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