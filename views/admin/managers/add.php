<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Add Movie manager</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Movie manager</li>
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
            <form class="forms-sample" method="POST" enctype="multipart/form-data" action="<?= URL; ?>admin/movie_managers/add">

                <?= Security::csrf_token(); ?>
              
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= isset($input['name']) ? $input['name'] : ''; ?>" required />
              </div>

              <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($input['email']) ? $input['email'] : ''; ?>" required />
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?= isset($input['password']) ? $input['password'] : ''; ?>" required />
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