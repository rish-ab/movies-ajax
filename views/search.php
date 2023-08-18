<!-- breadcrumb area start -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1>Search Page</h1>
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
                    <h1><i class="icofont icofont-coffee-cup"></i> <?= $value; ?></h1>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-12">

                <?php foreach ($data as $d): ?>
                    <div class="single-news">
                        <div class="news-bg-1" style="background: url('<?= URL . $d->picture; ?>'); background-size: cover;"></div>
                        <div class="news-date">
                            <h2><span><?= $d->month; ?></span> <?= $d->date; ?></h2>
                            <h1><?= $d->year; ?></h1>
                        </div>
                        <div class="news-content">
                            <h2 onclick="window.location.href = this.getAttribute('data-href');" data-href="<?= $d->detail; ?>">
                                <?= $d->name; ?>
                            </h2>
                            <p><?= substr($d->description, 0, 100); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section><!-- blog area end -->