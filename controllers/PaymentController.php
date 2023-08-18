<?php

/**
 * PaymentController
 */
class PaymentController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function payment_successfull()
    {
        $type = $_POST["type"];
        $order_id = $_POST["order_id"];
        $payment_id = $_POST["payment_id"];

        if ($this->user == null)
        {
            echo false;
            exit();
        }

        $order_detail = $this->load_model("OrderModel")->get_detail($order_id);
        
        if ($order_detail["order"]->user_id != $this->user->id)
        {
            echo false;
            exit();
        }

        $this->load_model("PaymentModel")->payment_successfull();

        echo true;
        exit();
    }

    public function verify_coupon_code()
    {
        $MovieModel = $this->load_model("MovieModel");
        
        $order_id = $_POST["order_id"];
        $movie_id = $_POST["movie_id"];
        $coupon_code = $_POST["coupon_code"];

        $movie = $MovieModel->get($movie_id);

        if ($MovieModel->has_coupon_code($movie_id))
        {
            $coupon_code = $MovieModel->verify_coupon_code($movie_id, $coupon_code);
            if ($coupon_code != null)
            {
                $order_detail = $this->load_model("OrderModel")->get_detail($order_id);

                $grand_total = 0;
                foreach ($order_detail["tickets"] as $ticket)
                {
                    $grand_total += $order_detail["movie"]->price_per_ticket;
                }

                $discount = $grand_total * ($coupon_code->discount / 100);
                $discount_price = $grand_total - $discount;

                $this->load_model("OrderModel")->update_coupon_code($order_id, $coupon_code->id);

                echo $discount_price;
                exit();
            }
        }

        echo 0;
        exit();
    }

    public function order($id)
    {
        if ($this->user == null)
        {
            header("Location: " . URL);
            exit();
        }

        $order_detail = $this->load_model("OrderModel")->get_detail($id);
        $movie = $this->load_model("MovieModel")->get_detail($order_detail["movie"]->id);

        if ($order_detail["order"]->user_id != $this->user->id)
        {
            header("Location: " . URL);
            exit();
        }

        /* stripe secret and publishable keys are in db.php */
        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        $grand_total = 0;
        foreach ($order_detail["tickets"] as $ticket)
        {
            if ($order_detail["coupon"] == null)
            {
                $grand_total += $movie->movie->price_per_ticket;
            }
            else
            {
                $discount = $movie->movie->price_per_ticket * ($order_detail["coupon"]->discount / 100);
                $discount_price = $movie->movie->price_per_ticket - $discount;

                $order_detail["movie"]->price_per_ticket = $discount_price;

                $grand_total += $discount_price;
            }
        }

        $has_coupon_code_applied = ($order_detail["coupon"] != null);
        
        /* creating setup intent */
        $payment_intent = $stripe->paymentIntents->create([
            'payment_method_types' => ['card'],

            /* convert double to integer for stripe payment intent,
                multiply by 100 is required for stripe */
            'amount' => round($grand_total) * 100,
            
            'currency' => 'usd',
        ]);

        require_once VIEW . "layout/header.php";
        require_once VIEW . 'payments/order.php';
        require_once VIEW . "layout/footer.php";
    }
}