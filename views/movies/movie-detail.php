<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1>Movie Detailed Page</h1>

                    <?php if (isset($_SESSION["success"])): ?>
                        <div class="offset-md-4 col-md-4 alert alert-success">
                            <?= $_SESSION["success"]; ?>
                        </div>
                    <?php unset($_SESSION["success"]); endif; ?>

                    <?php if (isset($_SESSION["error"])): ?>
                        <div class="offset-md-4 col-md-4 alert alert-danger">
                            <?= $_SESSION["error"]; ?>
                        </div>
                    <?php unset($_SESSION["error"]); endif; ?>
                </div>
            </div>
        </div>
    </div>
</section><!-- breadcrumb area end -->

<!-- transformers area start -->
<section class="transformers-area">
    <div class="container">
        <div class="transformers-box">
            <div class="row flexbox-center">
                <div class="col-lg-5 text-lg-left text-center">
                    <div class="movie-thumbnails transformers-content">
                        <?php $count = 0; foreach ($movie->thumbnails as $thumbnail): ?>
                            <img style="width: 100%;" class="<?= $count == 0 ? 'active' : ''; ?>" src="<?= URL . $thumbnail->file_path; ?>" alt="about" />
                        <?php $count++; endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="transformers-content">
                        <h2><?= $movie->movie->name; ?></h2>
                        
                        <p>
                            <?php
                                $count = 0;
                                $last_category = "";
                                foreach ($movie->categories as $category):
                                    echo $category->name . ($count == count($movie->categories) - 1 ? "" : " | ");
                                    $count++;
                                    $last_category = $category->name;
                                endforeach;
                            ?>
                        </p>

                        <div id="movie-rating" data-rating="<?= $ratings; ?>"></div>

                        <ul>
                            <li>
                                <div class="transformers-left">
                                    Movie:
                                </div>
                                <div class="transformers-right">
                                    <?= $last_category; ?>
                                </div>
                            </li>

                            <li>
                                <div class="transformers-left">
                                    Cast:
                                </div>
                                <div class="transformers-right">
                                    <?php
                                        $count = 0;
                                        foreach ($movie->casts as $cast)
                                        {
                                            ?>

                                            <a href="<?= URL . 'celebrity/detail/' . $cast->cast_id; ?>">
                                                <?= $cast->celebrity_name; ?>
                                            </a>

                                            <?php
                                            $count++;

                                            if ($count < count($movie->casts))
                                            {
                                                echo ", ";
                                            }
                                        }
                                    ?>
                                </div>
                            </li>

                            <li>
                                <div class="transformers-left">
                                    Writer:
                                </div>
                                <div class="transformers-right">
                                    <?= $movie->movie->writer; ?>
                                </div>
                            </li>
                            <li>
                                <div class="transformers-left">
                                    Director:
                                </div>
                                <div class="transformers-right">
                                    <?= $movie->movie->director; ?>
                                </div>
                            </li>

                            <li>
                                <div class="transformers-left">
                                    Time: 
                                </div>
                                <div class="transformers-right">
                                    <?= $movie->movie->duration; ?>
                                </div>
                            </li>
                            <li>
                                <div class="transformers-left">
                                    Release:
                                </div>
                                <div class="transformers-right">
                                    <?= date("Y-m-d", strtotime($movie->movie->release_date)); ?>
                                </div>
                            </li>
                            <li>
                                <div class="transformers-left">
                                    Language:
                                </div>
                                <div class="transformers-right">
                                    <?= $movie->movie->languages; ?>
                                </div>
                            </li>
                            <li>
                                <div class="transformers-left">
                                    Cinema:
                                </div>
                                <div class="transformers-right" style="width: 100%;">

                                    <div class="row">
                                        <?php $count = 0; foreach ($movie->cinemas as $cinema): ?>
                                            <div class="col-md-3 transformers-bottom">
                                                <?= $cinema->name; ?> <br>
                                                <p>
                                                    <?= date("M d, Y", strtotime($cinema->movie_time)); ?>
                                                    <span><?= date("h:i A", strtotime($cinema->movie_time)); ?></span>
                                                </p>
                                            </div>
                                        <?php $count++; endforeach; ?>
                                    </div>
                                    
                                </div>
                            </li>
                            <li>
                                <div class="transformers-left">
                                    Share:
                                </div>
                                <div class="transformers-right">
                                    <a href="javascript:void(0);" onclick="shareOnFacebook();"><i class="icofont icofont-social-facebook"></i></a>
                                    
                                    <a target="_blank" href="http://twitter.com/share?text=<?= $movie->movie->name; ?>&url=<?= URL . 'movie/detail/' . $movie->movie->id; ?>&hashtags=<?= str_replace(" ", "_", $movie->movie->name); ?>"><i class="icofont icofont-social-twitter"></i></a>

                                    <a target="_blank" href="https://youtube.com/c/AdnanAfzal565"><i class="icofont icofont-youtube-play"></i></a>
                                </div>
                            </li>

                            <li>
                                <h3 style="cursor: pointer;" onclick="$('.show-trailers').show();">Watch Trailer</h3>
                            </li>

                            <?php if ($my_ratings != null): ?>
                                <li>
                                    You have already voted
                                    <div style="margin-left: 5px;" id="user-ratings" data-rating="<?= $my_ratings->ratings; ?>"></div>
                                </li>
                            <?php endif; ?>

                            <?php if (isset($_SESSION["user"])): ?>
                                <li>
                                    <div class="starrr"></div>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="offset-md-2 movie-thumbnail-indicators">
                    <?php $count = 0; foreach ($movie->thumbnails as $thumbnail): ?>
                        <div onclick="gotoThumbnail(this);" data-id="<?= $movie->movie->id; ?>" data-index="<?= $count; ?>" class="owl-dot <?= $count == 0 ? 'active' : ''; ?>"></div>
                    <?php $count++; endforeach; ?>
                </div>
            </div>

            <a href="javascript:void(0);" class="theme-btn show-tickets"><i class="icofont icofont-ticket"></i> BUY TICKET</a>
        </div>
    </div>

    <div class="show-trailers" style="top: 20%;">
        <div class="container">
            <div class="buy-ticket-area" style="padding-top: 50px; background: black;">
                <a href="javascript:void(0);" style="color: white;" onclick="closeTrailers();"><i class="icofont icofont-close"></i></a>
                <div class="row">
                    <div class="col-md-12">
                        <div class="movie-trailer-box">
                            
                            <div class="movie-trailer-videos">
                                <?php $count = 0; foreach ($movie->trailers as $trailer): ?>
                                    <video class="<?= $count == 0 ? 'active' : ''; ?>" style="width: 100%; height: 400px;" controls src="<?= URL . $trailer->file_path; ?>"></video>
                                <?php $count++; endforeach; ?>
                            </div>

                            <div class="offset-md-5 movie-trailer-indicators">
                                <?php $count = 0; foreach ($movie->trailers as $trailer): ?>
                                    <div onclick="gotoTrailer(this);" data-index="<?= $count; ?>" class="owl-dot <?= $count == 0 ? 'active' : ''; ?>"></div>
                                <?php $count++; endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .movie-trailer-box video.active {
            display: block !important;
        }

        .movie-thumbnails img,
        .movie-trailer-box video {
            display: none !important;
        }
        .movie-thumbnails img.active {
            display: block !important;
            height: 500px;
            object-fit: cover;
        }
        .movie-thumbnail-indicators {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .movie-thumbnail-indicators div,
        .movie-trailer-indicators div {
            cursor: pointer;
        }
    </style>

    <script>
        function closeTrailers() {
            $('.show-trailers').hide();
            $('.show-trailers video').each(function() {
                $(this).get(0).pause();
            });
        }

        function gotoTrailer(self) {
            var index = self.getAttribute("data-index");

            var movieTrailerVideos = document.querySelector(".show-trailers .movie-trailer-videos").children;
            if (movieTrailerVideos.length > index) {
                for (var a = 0; a < movieTrailerVideos.length; a++) {
                    movieTrailerVideos[a].className = "";
                }
                movieTrailerVideos[index].className = "active";
            }

            var movieTrailerIndicators = document.querySelector(".show-trailers .movie-trailer-indicators").children;
            for (var a = 0; a < movieTrailerIndicators.length; a++) {
                movieTrailerIndicators[a].className = "owl-dot";
            }
            self.className = "owl-dot active";
        }

        function gotoThumbnail(self) {
            var index = self.getAttribute("data-index");
            var id = self.getAttribute("data-id");

            var movieThumbnails = document.querySelector(".movie-thumbnails").children;
            if (movieThumbnails.length > index) {
                for (var a = 0; a < movieThumbnails.length; a++) {
                    movieThumbnails[a].className = "";
                }
                movieThumbnails[index].className = "active";
            }

            var movieThumbnailIndicators = document.querySelector(".movie-thumbnail-indicators").children;
            for (var a = 0; a < movieThumbnailIndicators.length; a++) {
                movieThumbnailIndicators[a].className = "owl-dot";
            }
            self.className = "owl-dot active";
        }

        var ratings = 0;
        window.addEventListener("load", function () {
            $(".starrr").starrr().on("starrr:change", function (event, value) {
                ratings = value;

                $.ajax({
                    url: BASE_URL + "movie/add_ratings",
                    method: "POST",
                    data: {
                        "movie_id": $("#movie_id").val(),
                        "ratings": ratings
                    },
                    success: function (response) {
                        // whatever server echo, that will be displayed here in alert
                        // console.log(response);
                        var response = JSON.parse(response);
                        // console.log(response);

                        if (response.status == "error") {
                            swal("Error", response.message, "error");
                        } else {
                            $("#user-ratings").starrr("setRating", ratings);
                        }
                    }
                });
            });
 
            $("#movie-rating").starrr({
                readOnly: true,
                rating: $("#movie-rating").attr("data-rating")
            });
            $("#movie-rating a").attr("href", "javascript:void(0)");

            $("#user-ratings").starrr({
                readOnly: true,
                rating: $("#user-ratings").attr("data-rating")
            });
            $("#user-ratings a").attr("href", "javascript:void(0)");
        });
    </script>

    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId            : '',
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v8.0'
            });
        };

        function shareOnFacebook() {
            FB.ui({
                method: 'share',
                href: window.location.href,
            }, function(response){});
        }
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

</section><!-- transformers area end -->

<!-- details area start -->
<section class="details-area" id="section-comments">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="details-content">
                    <div class="details-overview">
                        <h2>Overview</h2>
                        <p><?= $movie->movie->description; ?></p>
                    </div>

                    <?php if (isset($_SESSION["post_comment_success"])): ?>
                        <div class="col-md-12 alert alert-success">
                            <?= $_SESSION["post_comment_success"]; ?>
                        </div>

                        <script>
                            setTimeout(function () {
                                document.getElementById('section-comments').scrollIntoView();
                            }, 1000);
                        </script>

                    <?php unset($_SESSION["post_comment_success"]); endif; ?>

                    <?php if (isset($_SESSION["post_comment_error"])): ?>
                        <div class="col-md-12 alert alert-danger">
                            <?= $_SESSION["post_comment_error"]; ?>
                        </div>

                        <script>
                            setTimeout(function () {
                                document.getElementById('section-comments').scrollIntoView();
                            }, 1000);
                        </script>

                    <?php unset($_SESSION["post_comment_error"]); endif; ?>

                    <div class="details-reply">
                        <h2>Leave a Reply</h2>
                        <form action="<?= URL . 'movie/add_comment'; ?>" method="POST" id="form-post-comment">
                            <input type="hidden" id="movie_id" name="movie_id" value="<?= $movie->movie->id; ?>">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="select-container">
                                        <input type="text" name="name" required="" placeholder="Name *"/>
                                        <i class="icofont icofont-ui-user"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="select-container">
                                        <input type="email" name="email" required="" placeholder="Email *"/>
                                        <i class="icofont icofont-envelope"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="select-container">
                                        <input type="number" class="disable-arrows" name="phone" placeholder="Phone"/>
                                        <i class="icofont icofont-phone"></i>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="textarea-container">
                                        <textarea name="message" required="" placeholder="Type Here Message *"></textarea>
                                        <button><i class="icofont icofont-send-mail"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="details-comment">
                        <input type="submit" class="theme-btn theme-btn2 btn-add-comment" value="Post Comment" form="form-post-comment">
                        <p>Your email address will not be published. Required fields are marked *</p>
                    </div>

                    <?php foreach ($movie->comments as $comment): ?>
                        <div class="row">
                            <div class="col-md-2">
                                <img src="<?= IMG . 'user-placeholder.png'; ?>">
                            </div>

                            <div class="col-md-10">
                                <h3>
                                    <?= $comment->name; ?>
                                </h3>

                                <p>
                                    <?= $comment->message; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="details-thumb">

                        <div class="details-thumb-prev">
                            <?php if ($previous_movie != null): ?>
                                <div onclick="window.location.href = this.getAttribute('data-href');" data-href="<?= URL . 'movie/detail/' . $previous_movie->id; ?>" style="display: contents; cursor: pointer;">
                                    <div class="thumb-icon">
                                        <i class="icofont icofont-simple-left"></i>
                                    </div>
                                    <div class="thumb-text">
                                        <h4>Previous Movie</h4>
                                        <p><?= $previous_movie->name; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="details-thumb-next">
                            <?php if ($next_movie != null): ?>
                                <div onclick="window.location.href = this.getAttribute('data-href');" data-href="<?= URL . 'movie/detail/' . $next_movie->id; ?>" style="display: contents; cursor: pointer;">
                                    <div class="thumb-text">

                                        <h4>Next Movie</h4>
                                        <p>
                                            <?= $next_movie->name; ?>
                                        </p>
                                    </div>
                                    <div class="thumb-icon">
                                        <i class="icofont icofont-simple-right"></i>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-center text-lg-left">
                <div class="portfolio-sidebar">
                    <img src="<?= IMG; ?>sidebar/sidebar1.png" alt="sidebar" />
                    <img src="<?= IMG; ?>sidebar/sidebar2.png" alt="sidebar" />
                    <img src="<?= IMG; ?>sidebar/sidebar4.png" alt="sidebar" />
                </div>
            </div>
        </div>
    </div>
</section><!-- details area end -->

<form method="POST" action="<?= URL . 'ticket/create'; ?>">
    
    <input type="hidden" name="id" value="<?= $movie->movie->id; ?>">

    <div class="buy-ticket">
        <div class="container">
            <div class="buy-ticket-area">
                <a href="#"><i class="icofont icofont-close"></i></a>
                <div class="row">

                    <div class="col-lg-8">
                        <div class="buy-ticket-box">
                            <h4>Buy Tickets</h4>
                            <h5>Seat</h5>
                            <h6>Screen</h6>
                            <div class="ticket-box-table" style="margin-bottom: 30px;" id="container-seats-view">

                                <?php for ($a = 0; $a < $columns; $a++): ?>
                                    <table class="ticket-table-seat">
                                        <?php for ($b = 0; $b < $rows; $b++): ?>
                                            <tr>
                                                <?php
                                                    for ($c = 0; $c < $seats_per_column; $c++):
                                                        $seat_number = floor($seats_per_column * $a) + ($c + 1);
                                                        
                                                        // Check if this seat is already booked
                                                        $class_name = "";
                                                        foreach ($movie->tickets as $ticket)
                                                        {
                                                            if ($ticket->ticket_location == $alphabets[$b] . $seat_number)
                                                            {
                                                                // $class_name = "active";
                                                                break;
                                                            }
                                                        }
                                                ?>
                                                    <td style="cursor: pointer;" onclick="selectSeat(this);" data-seat="<?= $alphabets[$b] . $seat_number; ?>" class="<?= $class_name; ?>">
                                                        <?= $seat_number; ?>
                                                        <input type="checkbox" name="selected_seats[]" style="display: none;" <?= empty($class_name) ? "" : "disabled"; ?>>
                                                    </td>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endfor; ?>
                                    </table>

                                    <?php if ($a <= $columns - 1): ?>
                                        <table>
                                            <?php for ($d = 0; $d < $rows; $d++): ?>
                                                <tr>
                                                    <td><?= $alphabets[$d]; ?></td>
                                                </tr>
                                            <?php endfor; ?>
                                        </table>
                                    <?php endif; ?>

                                <?php endfor; ?>

                            </div>

                            <?php if (isset($_SESSION["user"])): ?>
                                <?php if ($is_currently_playing): ?>
                                    <input style="background: #eb315a; color: white;" type="submit" class="theme-btn" value="Book Tickets" />
                                <?php else: ?>
                                    <input style="background: #eb315a; color: white;" type="button" class="theme-btn" value="Movie not being playing." disabled>
                                <?php endif; ?>
                            <?php else: ?>
                                <input style="background: #eb315a; color: white;" type="button" class="theme-btn" value="Please login to book a ticket." disabled>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="buy-ticket-box mtr-30">
                            <h4>Your Information</h4>
                            <ul>
                                <li>
                                    <p>Location</p>
                                    <select class="form-control" name="selected_cinema" required onchange="onCinemaSelected(this);">
                                        <option value="">Select Cinema</option>
                                        <?php
                                            foreach ($cinemas as $cinema):
                                            ?>
                                                <option value="<?= $cinema->id; ?>">
                                                    <?= $cinema->name; ?>
                                                </option>
                                            <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <p>TIME</p>
                                    <select class="form-control" id="selected_time" name="selected_time" required onchange="renderAlreadyBookedSeats();">
                                        <option value="">Select Time</option>
                                    </select>
                                </li>
                                <li>
                                    <p>Movie name</p>
                                    <span><?= $movie->movie->name; ?></span>
                                </li>
                                <li>
                                    <p>Ticket number</p>
                                    <span id="selected-tickets"></span>
                                </li>
                                <li>
                                    <p>Price</p>
                                    <span id="price-per-ticket">
                                        <?php
                                            // if ($movie->movie->discount_price == 0)
                                            {
                                                echo $movie->movie->price_per_ticket;
                                            }
                                            // else
                                            {
                                                // echo "<del>" . $movie->movie->price_per_ticket . "</del> ";
                                                // echo $movie->movie->discount_price;
                                            }
                                        ?>
                                    </span>
                                    <input type="hidden" id="actual-price-per-ticket" value="<?php echo $movie->movie->price_per_ticket; ?>">

                                    <!-- <input type="hidden" id="discounted-price-per-ticket" value="<?php //echo $movie->movie->discount_price; ?>" /> -->
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- header section end-->
</form>

<input type="hidden" id="hidden_cinemas" value="<?= htmlentities(json_encode($cinemas)); ?>" />
<input type="hidden" id="hidden_movie_id" value="<?= $movie->movie->id; ?>" />

<script>

    var hiddenCinemas = [];

    /* initialize stripe when page loads */
    window.addEventListener("load", function () {
        hiddenCinemas = JSON.parse(document.getElementById("hidden_cinemas").value);
    });

    function onCinemaSelected(self) {
        var html = "";
        if (self.value != "") {
            for (var a = 0; a < hiddenCinemas.length; a++) {
                if (hiddenCinemas[a].id == self.value) {
                    for (var b = 0; b < hiddenCinemas[a].times.length; b++) {
                        html += "<option data-cinema-id='" + hiddenCinemas[a].id + "' data-time-id='" + hiddenCinemas[a].times[b].time_id + "' value='" + hiddenCinemas[a].times[b].time_id + "'>" + hiddenCinemas[a].times[b].movie_time + "</option>";
                    }
                }
            }
        }
        document.getElementById("selected_time").innerHTML = html;

        renderAlreadyBookedSeats();
    }

    function renderAlreadyBookedSeats() {
        var e = document.getElementById("selected_time");
        var selectedOption = e.options[e.selectedIndex];
        var movie_cinema_id = selectedOption.getAttribute("data-time-id");
        
        var ajax = new XMLHttpRequest();
        ajax.open("POST", document.getElementById("base-url").value + "ajax/get_my_booked_seats", true);

        var checkboxes = document.querySelectorAll("#container-seats-view input[type='checkbox']");

        for (var b = 0; b < checkboxes.length; b++) {
            checkboxes[b].parentElement.className = "";
            checkboxes[b].removeAttribute("disabled");
        }

        ajax.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    // console.log(this.responseText);
                    var data = JSON.parse(this.responseText);
                    // console.log(data);

                    if (data.status == "success") {
                        // data.seats_booked
                        // console.log(data.seats_booked);

                        var checkboxes = document.querySelectorAll("#container-seats-view input[type='checkbox']");

                        for (var a = 0; a < data.seats_booked.length; a++) {
                            for (var b = 0; b < checkboxes.length; b++) {
                                var dataSeat = checkboxes[b].parentElement.getAttribute("data-seat");

                                if (dataSeat == data.seats_booked[a].ticket_location) {
                                    checkboxes[b].parentElement.className = "active";
                                    checkboxes[b].setAttribute("disabled", "disabled");
                                }
                            }
                        }
                    } else {
                        alert(data.message);
                    }
                }

                if (this.status == 500) {
                    console.log(this.responseText);
                }
            }
        };

        var formData = new FormData();
        formData.append("movie_cinema_id", movie_cinema_id);
        formData.append("movie_id", document.getElementById("hidden_movie_id").value);
        formData.append("cinema_id", selectedOption.getAttribute("data-cinema-id"));
        ajax.send(formData);
    }

    var selectedTickets = [];
    function selectSeat(self) {
        if (self.children[0].hasAttribute("disabled")) {
            return;
        }

        self.children[0].checked = self.children[0].checked ? false : true;
        self.children[0].value = self.getAttribute("data-seat");
        self.classList.toggle("active");

        if (self.children[0].checked) {
            selectedTickets.push(self.getAttribute("data-seat"));
        } else {
            for (var a = 0; a < selectedTickets.length; a++) {
                if (selectedTickets[a] == self.getAttribute("data-seat")) {
                    selectedTickets.splice(a, 1);
                }
            }
        }

        var pricePerTicket = parseInt(document.getElementById("actual-price-per-ticket").value);
        pricePerTicket = pricePerTicket * selectedTickets.length;
        document.getElementById("price-per-ticket").innerHTML = pricePerTicket;

        /*var discountedPricePerTicket = parseFloat(document.getElementById("discounted-price-per-ticket").value);
        if (discountedPricePerTicket > 0) {
            discountedPricePerTicket = discountedPricePerTicket * selectedTickets.length;
            discountedPricePerTicket = discountedPricePerTicket.toFixed(2);
            pricePerTicket = "<del>" + pricePerTicket + "</del> " + discountedPricePerTicket;
        }
        document.getElementById("price-per-ticket").innerHTML = pricePerTicket;*/

        var html = "";
        for (var a = 0; a < selectedTickets.length; a++) {
            html += selectedTickets[a];
            if (a < selectedTickets.length - 1) {
                html += ", ";
            }
        }
        document.getElementById("selected-tickets").innerHTML = html;
    }
</script>