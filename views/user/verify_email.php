<?php

?>

<!-- breadcrumb area start -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1>Verify Email</h1>
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
                <?php if (isset($_SESSION["login_verification"])): ?>
                    <div class="offset-md-4 col-md-4 alert alert-success">
                        <?= $_SESSION["login_verification"]; ?>
                    </div>
                <?php unset($_SESSION["login_verification"]); endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="offset-md-4 col-md-4" style="margin-top: 10px;">

                <?php
                    if (!empty($error))
                    {
                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                    }
                ?>

                <form role="form" method="POST" action="<?= URL; ?>user/verify_email/<?= $email; ?>">
                    <?= Security::csrf_token(); ?>

                    <input type="hidden" name="email" id="email" placeholder="Enter Email" autocomplete="off" value="<?= $email; ?>" required>
                    <input style="margin-bottom: 10px;" type="text" name="verification_code" placeholder="Enter Verification Code" autocomplete="off" required>
                    
                    <button type="submit" style="margin-bottom: 50px; width: 100%;">Verify</button>
                </form>

            </div>
        </div>
    </div>
</section><!-- blog area end -->