<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Edit Season </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin/seasons/all">Season</a></li>
          <li class="breadcrumb-item active"><a href="javascript:void(0);"><?= $id; ?></a></li>
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

    <?php if (isset($_SESSION["message"])) { ?>
        <div class="alert alert-success"><?= $_SESSION["message"]; ?></div>
    <?php unset($_SESSION["message"]); } ?>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <!-- <h4 class="card-title">Edit Category</h4> -->
            <!-- <p class="card-description"> Basic form layout </p> -->
            <form class="forms-sample" method="POST" action="<?= URL; ?>admin/seasons/edit/<?= $id; ?>">
                <?= Security::csrf_token(); ?>
                <input type="hidden" name="id" value="<?= $id; ?>" required>

              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="<?= $season->name; ?>" name="name" required>
              </div>

              <div class="form-group">
                <label for="sponsors">Sponsors</label>
                <input type="text" class="form-control" id="sponsors" name="sponsors" value="<?= $season->sponsors; ?>" required />
              </div>

              <button type="submit" class="btn btn-primary mr-2">Submit</button>
              <button class="btn btn-light" type="reset">Cancel</button>
            </form>
          </div>
        </div>
      </div>
      
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#addEpisodeCollapse" aria-expanded="false" aria-controls="addEpisodeCollapse">
              Add Episode
            </button>
          </div>

          <div class="card-body">
            <div class="collapse" id="addEpisodeCollapse">
              <form method="POST" action="<?= URL; ?>admin/seasons/add_episode" enctype="multipart/form-data">

                <?= Security::csrf_token(); ?>
                <input type="hidden" name="id" value="<?= $id; ?>" required>

                <div class="form-group">
                  <label for="episode_name">Name</label>
                  <input type="text" class="form-control" id="episode_name" name="name" required>
                </div>

                <div class="form-group">
                  <label for="duration">Duration</label>
                  <input type="text" class="form-control" id="duration" name="duration" required>
                </div>

                <div class="form-group">
                  <label for="poster">Poster</label>
                  <input type="file" accept="image/*" class="form-control" id="poster" name="poster" required>
                </div>

                <div class="form-group">
                  <label for="video">Video</label>
                  <input type="file" accept="video/*" class="form-control" id="video" name="video" required>
                </div>

                <div class="form-group">
                  <label for="celebrities">Celebrities</label>
                  <input type="text" class="form-control" id="celebrities" name="celebrities" placeholder="Emilia Clarke, Peter Dinklage" required>
                </div>

                <div class="form-group">
                  <label for="imdb_ratings">IMDB ratings</label>
                  <input type="number" min="0" step="0.1" class="form-control" id="imdb_ratings" name="imdb_ratings" required>
                </div>

                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                <button class="btn btn-light" type="reset">Cancel</button>

              </form>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <div class="row" style="margin-top: 50px;">
      <div class="col-md-12">

        <h4>All Episodes</h4>

        <div class="card" style="margin-top: 20px;">
          <div class="card-body">
            <table class="table table-bordered">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
              </tr>

              <?php foreach ($season->episodes as $episode): ?>
                <tr>
                  <td><?= $episode->id; ?></td>
                  <td><?= $episode->name; ?></td>
                  <td><a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="return confirmDelete(this);" data-href="<?= URL; ?>admin/seasons/delete_episode/<?= $episode->id; ?>">
                    Delete
                  </a></td>
                </tr>
              <?php endforeach; ?>

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
        text: "Are you sure you want to delete this episode ?",
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