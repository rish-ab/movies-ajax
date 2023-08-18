<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Settings </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Settings</li>
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
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <!-- <h4 class="card-title">Default form</h4> -->
            <!-- <p class="card-description"> Basic form layout </p> -->
            <form class="forms-sample" method="POST" enctype="multipart/form-data" action="<?= URL; ?>admin/settings">

                <?= Security::csrf_token(); ?>
              
              <div class="form-group">
                <label for="current_password">Current password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" value="<?= isset($input['current_password']) ? $input['current_password'] : ''; ?>" required>
              </div>

              <div class="form-group">
                <label for="new_password">New password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" value="<?= isset($input['new_password']) ? $input['new_password'] : ''; ?>" required>
              </div>

              <div class="form-group">
                <label for="confirm_password">Confirm password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?= isset($input['confirm_password']) ? $input['confirm_password'] : ''; ?>" required>
              </div>

              <button type="submit" class="btn btn-primary mr-2">Submit</button>
              <button class="btn btn-light">Cancel</button>
            </form>
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- content-wrapper ends -->