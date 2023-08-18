<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Profile </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
      </nav>
    </div>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-success"><?= $message; ?></div>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) { ?>
        <div class="alert alert-danger"><?= $_SESSION["error"]; ?></div>
    <?php unset($_SESSION["error"]); } ?>

    <?php if (isset($_SESSION["success"])) { ?>
        <div class="alert alert-success"><?= $_SESSION["success"]; ?></div>
    <?php unset($_SESSION["success"]); } ?>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <!-- <h4 class="card-title">Default form</h4> -->
            <!-- <p class="card-description"> Basic form layout </p> -->
            <form class="forms-sample" method="POST" enctype="multipart/form-data" action="<?= URL; ?>admin/profile">

                <?= Security::csrf_token(); ?>
              
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= isset($input['name']) ? $input['name'] : ''; ?>" required>
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($input['email']) ? $input['email'] : ''; ?>" required>
              </div>

              <div class="form-group">
                <label for="picture">Profile Picture</label>
                <input type="file" accept="image/*" onchange="previewPicture(this);" class="form-control" id="picture" name="picture">
              </div>

              <img id="preview-img" class="celeb-img" style="margin-bottom: 10px;">

              <div class="form-group">
                  <a href="<?= URL . 'admin/remove_photo'; ?>" class="btn btn-danger btn-sm">Remove Photo</a>
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

  <script>
    function previewPicture(self) {
        if (self.files.length > 0) {
            var fileReader = new FileReader();
 
            fileReader.onload = function (event) {
                document.getElementById("preview-img").setAttribute("src", event.target.result);
            };
 
            fileReader.readAsDataURL(self.files[0]);
        }
    }
  </script>