breadcrumb area start -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1><?= $season->name; ?></h1>
                </div>
            </div>
        </div>
    </div>
</section><!-- breadcrumb area end -->

<!-- blog area start -->
<section class="blog-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title pb-20">
                    <h1><i class="icofont icofont-coffee-cup"></i> Episodes</h1>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                <?php foreach ($season->episodes as $episode): ?>
                    <div class="single-news">
                        <div class="news-bg-1" style="background-image: url('<?= URL . '/' . $episode->poster; ?>')"></div>
                        <div class="news-date">
                            <h2>
                                <span style="text-transform: uppercase;">
                                    <?= date_format(date_create($episode->created_at), "M"); ?>
                                </span>
                                <?= date_format(date_create($episode->created_at), "d"); ?>
                            </h2>

                            <h1>
                                <?= date_format(date_create($episode->created_at), "Y"); ?>
                            </h1>
                        </div>

                        <div class="news-content">
                            <h2 style="padding-left: 10px;"><?= $episode->name; ?></h2>
                            <p style="background: rgba(0, 0, 0, 0.5); padding-left: 10px;">
                                Duration: <?= $episode->duration; ?> <br />
                                Stars: <?= $episode->celebrities; ?> <br />
                                IMDB: <?= $episode->imdb_ratings; ?>/10.0
                            </p>
                        </div>
                        <a href="javascript:void(0);" onclick="viewVideo(this);" data-src="<?= URL . $episode->video; ?>" style="right: 60px;">Play</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section><!-- blog area end-->

<style>
    .modal-dialog {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      max-width: 100% !important;
      /*margin-left: 10px;*/
    }

    .modal-content {
      height: auto;
      min-height: 100%;
      border-radius: 0;
    }
</style>

<script>
    function viewVideo(self) {
        $("#videoModal").modal("show");
        $("#videoModal video").attr("src", self.getAttribute("data-src"));
    }
</script>

<!-- Modal -->
<div class="modal" id="videoModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Video</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <video controls></video>
      </div>
    </div>
  </div>
</div>