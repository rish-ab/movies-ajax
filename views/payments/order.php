<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-area-content">
                    <h1>Order</h1>

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
        <div class="transformers-box" style="background-color: white; color: black;">
            <div class="row flexbox-center">
                <div class="col-md-12 text-center">
                    Order # <?php echo $order_detail["order"]->id; ?>
                </div>
            </div>

            <div class="row" style="margin-top: 30px;">
                <div class="offset-md-3 col-md-6">
                    <h3 class="text-center" style="color: black;" id="grant-total-text">Total: $<?php echo $grand_total; ?></h3>
                </div>
            </div>

            <?php if (!$has_coupon_code_applied): ?>
                <div class="row" style="margin-top: 30px;">
                    <div class="offset-md-3 col-md-6">

                        <form method="POST" onsubmit="return applyCouponCode(this);">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" name="coupon_code" class="form-control" required style="background: white; border: 1px solid #ced4da;" placeholder="Enter coupon code" />
                                    </div>
                                </div>

                                <input type="hidden" name="order_id" value="<?php echo $order_detail['order']->id; ?>" required />
                                <input type="hidden" name="movie_id" value="<?php echo $order_detail['movie']->id; ?>" required />

                                <div class="col-md-4">
                                    <input type="submit" name="submit" class="btn btn-info btn-lg" value="Apply" style="background-color: #17a2b8;
    color: white;
    position: relative;
    top: 2px;" />
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            <?php endif; ?>

            <div class="row" style="margin-top: 30px;">
                <div class="offset-md-3 col-md-6">
                    <h1 style="color: black;" class="text-center">Pay via Stripe</h1>

                    <div id="stripe-card-element" style="margin-top: 20px; margin-bottom: 20px;"></div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" onclick="payViaStripe(this);" class="btn btn-primary">
                                Pay now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px;">
                <div class="offset-md-3 col-md-6">
                    <h1 style="color: black; margin-bottom: 10px;" class="text-center">Pay via PayPal</h1>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <!-- paypal button will be rendered here using Javascript -->
                            <div id="btn-paypal-checkout" style="margin-top: 20px;"></div>
                            <div id="paypal-button-container" style="width: 100% !important;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php foreach ($order_detail["tickets"] as $ticket): ?>
                <div class="row">
                    <div class="col-md-12 seat">
                        <div class="cardWrap">
                            <div class="card cardLeft">
                                <h1 style="position: relative; top: 15px;"><?php echo $ticket["cinema"]->name; ?></h1>
                                <div class="title" style="margin-top: 50px;">
                                    <h2><?php echo $order_detail["movie"]->name; ?></h2>
                                    <span>movie</span>
                                </div>
                                <div class="name">
                                    <h2><?php echo $order_detail["movie"]->languages; ?></h2>
                                    <span>name</span>
                                </div>
                                <div class="seat">
                                    <h2><?php echo $ticket["ticket"]->ticket_location; ?></h2>
                                    <span>seat</span>
                                </div>
                                <div class="time" style="margin-left: 0px;">
                                    <h2><?php echo $ticket["ticket"]->movie_time; ?></h2>
                                    <span>time</span>
                                </div>
                            </div>

                            <div class="card cardRight">
                                
                                <div class="number" style="margin-top: 20px;">
                                    <h3 style="margin-top: 40px;"><?php echo $ticket["ticket"]->ticket_location; ?></h3>
                                    <span>seat</span>
                                </div>

                                <div class="number">
                                    <h3 class="price-value" style="margin-top: 20px;"><?php echo $order_detail["movie"]->price_per_ticket; ?></h3>
                                    <span>price</span>
                                </div>

                                <div class="barcode"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</section>

<!-- Load the required checkout.js script -->
<script src="https://www.paypalobjects.com/api/checkout.js" data-version-4></script>

<!-- Load the required Braintree components. -->
<script src="https://js.braintreegateway.com/web/3.39.0/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.39.0/js/paypal-checkout.min.js"></script>

<input type="hidden" id="stripe-public-key" value="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" />
<input type="hidden" id="stripe-payment-intent" value="<?php echo $payment_intent->client_secret; ?>" />
<input type="hidden" id="all-tickets" value="<?php echo htmlentities(json_encode($order_detail['tickets'])); ?>" />
<input type="hidden" id="hidden-movie" value="<?php echo htmlentities(json_encode($order_detail['movie'])); ?>" />
<input type="hidden" id="hidden-order" value="<?php echo htmlentities(json_encode($order_detail['order'])); ?>" />

<input type="hidden" id="user-name" value="<?php echo $order_detail['user']->name; ?>" />
<input type="hidden" id="user-email" value="<?php echo $order_detail['user']->email; ?>" />
<input type="hidden" id="user-mobile_number" value="<?php echo $order_detail['user']->mobile_number; ?>" />

<!-- include Stripe library -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    /* global variables */
    var stripe = null;
    var cardElement = null;

    /* global variables that will be received from PayPal when payment succeeds */
    var intent = "",
        orderID = "",
        payerID = "",
        paymentID = "",
        paymentToken = "";

    function applyCouponCode(form) {
        var ajax = new XMLHttpRequest();
        ajax.open("POST", document.getElementById("base-url").value + "payment/verify_coupon_code", true);

        ajax.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    console.log(this.responseText);

                    if (this.responseText > 0) {
                        // document.getElementById("grant-total-text").innerHTML = "Total: $" + this.responseText;

                        swal("Coupon code accepted", "Please wait while we re-calculate your price." , "success");
                        window.location.reload();
                    } else {
                        swal("Coupon code failed", "Sorry, this coupon code is not correct." , "error");
                    }
                }

                if (this.status == 500) {
                    console.log(this.responseText);
                }
            }
        };

        var formData = new FormData(form);
        ajax.send(formData);

        return false;
    }

    function payment_successfull(type, payment_id) {
        var ajax = new XMLHttpRequest();
        ajax.open("POST", document.getElementById("base-url").value + "payment/payment_successfull", true);

        ajax.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    console.log(this.responseText);

                    if (this.responseText > 0) {
                        // document.getElementById("grant-total-text").innerHTML = "Total: $" + this.responseText;

                        // swal("Coupon code accepted", "Please wait while we re-calculate your price." , "success");
                        // window.location.reload();
                    } else {
                        // swal("Coupon code failed", "Sorry, this coupon code is not correct." , "error");
                    }
                }

                if (this.status == 500) {
                    console.log(this.responseText);
                }
            }
        };

        var formData = new FormData();
        formData.append("type", type);
        formData.append("payment_id", payment_id);
        formData.append("order_id", JSON.parse(document.getElementById("hidden-order").value).id);
        ajax.send(formData);

        return false;
    }

    /* this function will be called when the "Pay now" button is pressed */
    function payViaStripe(self) {

        /* display the loader */
        self.innerHTML = "Loading...";
        self.setAttribute("disabled", "disabled");

        /* get stripe payment intent */
        const stripePaymentIntent = document.getElementById("stripe-payment-intent").value;

        /* execute the payment */
        stripe
            .confirmCardPayment(stripePaymentIntent, {
                payment_method: {
                        card: cardElement,
                        billing_details: {
                            "email": document.getElementById("user-email").value,
                            "name": document.getElementById("user-name").value,
                            "phone": document.getElementById("user-mobile_number").value
                        },
                    },
                })
                .then(function(result) {

                    self.removeAttribute("disabled");
                    self.innerHTML = "Pay now";

                    // Handle result.error or result.paymentIntent
                    if (result.error) {
                        console.log(result.error);
                        // alert(result.error.message);
                        // Display "error.message" to the user...

                        swal("Stripe", result.error.message , "error");
                    } else {
                        console.log("The card has been verified successfully...", result.paymentIntent);
                        // alert("Payment has been made.");

                        swal("Stripe", "Payment has been made." , "success");

                        payment_successfull("stripe", result.paymentIntent.id);

                        /* you can use the result.paymentIntent object for saving
                            in database if you want */

                        /* redirect the user to home page */
                        // window.location.href = "index.php";
                    }
                });
    }

    /* initialize stripe when page loads */
    window.addEventListener("load", function () {
        stripe = Stripe('');
        var elements = stripe.elements();
        cardElement = elements.create('card');
        cardElement.mount('#stripe-card-element');

        /* cookieItems array will be used for PayPal because it uses a specific format of data */
        var cookieItems = [];
        var cartItems = JSON.parse(document.getElementById("all-tickets").value);

        var movie = JSON.parse(document.getElementById("hidden-movie").value);
        var order = JSON.parse(document.getElementById("hidden-order").value);

        var total = 0;

        /* fill the cookieItems array with cart items */
        for (var a = 0; a < cartItems.length; a++) {
            if (order.coupon_code_id == 0) {
                cookieItems.push({
                    name: cartItems[a].ticket.ticket_location,
                    description: "Movie: " + movie.name + ", cinema: " + cartItems[a].ticket.movie_time,
                    quantity: 1,
                    price: parseFloat(movie.price_per_ticket),
                    sku: movie.id,
                    currency: "USD"
                });
            } else {
                cookieItems.push({
                    name: cartItems[a].ticket.ticket_location,
                    description: "Movie: " + movie.name + ", cinema: " + cartItems[a].ticket.movie_time,
                    quantity: 1,
                    price: parseFloat(movie.price_per_ticket),
                    sku: movie.id,
                    currency: "USD"
                });
            }
            
            total += parseFloat(movie.price_per_ticket);
        }

        // Render the PayPal button
        paypal.Button.render({

            // Set your environment
            env: 'sandbox', // sandbox | production

            // Specify the style of the button
            style: {
                label: 'checkout',
                size: 'medium', // small | medium | large | responsive
                shape: 'pill', // pill | rect
                color: 'gold', // gold | blue | silver | black,
                layout: 'vertical'
            },

            // PayPal Client IDs - replace with your own
            // Create a PayPal app: https://developer.paypal.com/developer/applications/create

            client: {
                sandbox: '',
                production: ''
            },

            funding: {
                allowed: [
                    paypal.FUNDING.CARD,
                    paypal.FUNDING.ELV
                ]
            },

            payment: function(data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [{
                            amount: {
                                /* get grand total from hidden input field */
                                total: total,
                                currency: 'USD'
                            },
                            item_list: {
                                /* custom cookieItems array created specifically
                                    for PayPal */
                                items: cookieItems
                            }
                        }]
                    }
                });
            },

            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function() {
                    /*console.log({
                        method: "onAuthorize",
                        data: data,
                        actions: actions
                    });*/

                    /* you can use all the values received from PayPal
                        as you want */
                    intent = data.intent;
                    orderID = data.orderID;
                    payerID = data.payerID;
                    paymentID = data.paymentID;
                    paymentToken = data.paymentToken;

                    console.log({
                        "intent": intent,
                        "orderID": orderID,
                        "payerID": payerID,
                        "paymentID": paymentID,
                        "paymentToken": paymentToken,
                        "data": data
                    });

                    swal("PayPal", "Payment has been made." , "success");

                    payment_successfull("paypal", paymentID);

                    /* redirect the user to home page */
                    // window.location.href = "index.php";
                });
            },
            
            onCancel: function (data, actions) {
                // Show a cancel page or return to cart
                console.log({
                    method: "onCancel",
                    data: data,
                    actions: actions
                });
            }

        }, '#btn-paypal-checkout');
    });
</script>