<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Add coupon code</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL; ?>admin">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add coupon code</li>
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
            <form class="forms-sample" method="POST" enctype="multipart/form-data" action="<?= URL; ?>admin/coupon_codes/add">

                <?= Security::csrf_token(); ?>
              
              <div class="form-group">
                <label for="movie_id">Movie</label>
                <select name="movie_id" class="form-control" required id="movie_id">
                  <?php foreach ($movies as $movie): ?>
                    <option value="<?php echo $movie->id; ?>"
                        <?php echo isset($input['movie_id']) && $input['movie_id'] == $movie->id ? 'selected' : ''; ?>>
                      <?php echo $movie->name; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="coupon_code">Coupon code</label>
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="<?= isset($input['coupon_code']) ? $input['coupon_code'] : ''; ?>" required />
              </div>

              <div class="form-group">
                <label for="discount">Discount</label>
                <input type="number" min="1" class="form-control" id="discount" name="discount" value="<?= isset($input['discount']) ? $input['discount'] : ''; ?>" placeholder="%" required />
              </div>

              <div class="form-group">
                <label for="valid_till">Valid till</label>
                <input autocomplete="off" type="text" class="form-control" id="valid_till" name="valid_till" value="<?= isset($input['valid_till']) ? $input['valid_till'] : ''; ?>" required />
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
    window.addEventListener("load", function () {
      $("#valid_till").datetimepicker({
        "timepicker": true,
        "scrollMonth": false,
        "format": "Y-m-d H:i:s",
        "closeOnDateSelect": true
      });
    });
  </script>