<?php

?>

<!-- breadcrumb area start -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1>Reset Password</h1>
                </div>
            </div>
        </div>
    </div>
</section><!-- breadcrumb area end -->
<!-- blog area start -->
<section class="blog-area">
    <div class="container">
        <hr />

        <div class="row">
            <div class="col-md-12" style="margin-top: 50px;">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="offset-md-4 col-md-4 alert alert-danger">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="offset-md-4 col-md-4" style="margin-top: 10px;">

                <form role="form" method="POST" action="<?= URL; ?>user/reset_password/<?= $email . '/' . $token; ?>">
                    <?= Security::csrf_token(); ?>

                    <input type="hidden" class="form-control" name="email" placeholder="Enter Email" autocomplete="off" required value="<?= $email; ?>">

                    <input style="margin-bottom: 10px;" type="password" name="password" placeholder="New password" autocomplete="off" required>
                    <input style="margin-bottom: 10px;" type="password" name="confirm_password" placeholder="Confirm password" autocomplete="off" required>
                    
                    <button type="submit" style="margin-bottom: 50px; width: 100%;">Reset Password</button>
                </form>

            </div>
        </div>
    </div>
</section><!-- blog area end -->