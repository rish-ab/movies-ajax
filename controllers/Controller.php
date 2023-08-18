<?php
	class Controller {
		protected $header;
        protected $footer;
        protected $title;
        protected $user;
		
		public function __construct() {
			$this->header = VIEW . "layout/header.php";
            $this->footer = VIEW . "layout/footer.php";

            $this->title = "Home";
            $this->user = $this->get_logged_in_user();
		}
		
		protected function get_header() {
            return $this->header;
        }

        protected function get_footer() {
            return $this->footer;
        }

		public function send_mail($to, $subject, $body) {
            // for live
            /*$headers = 'From: AdnanTech <your_gmail>' . "\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
            return mail($to, $subject, $body, $headers);*/

            require 'public/PHPMailer/PHPMailerAutoload.php';

            $mail = new PHPMailer;

            // $mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'your_gmail';                 // SMTP username
            $mail->Password = 'your_password';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            $mail->setFrom('your_gmail', 'aheedbutt');
            $mail->addAddress($to);     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body    = $body;

            if(!$mail->send()) {
                // die('Mailer Error: ' . $mail->ErrorInfo);
            }
        }
		
		protected function load_model($model_name) {
			$path = "models/" . $model_name . ".php";
			if (file_exists($path)) {
				require_once($path);
				return new $model_name();
			} else {
				return null;
			}
		}

        public function is_admin_logged_in() {
            return isset($_SESSION["admin"]);
        }

        public function get_logged_in_user()
        {
            if (isset($_SESSION["user"]))
                return $_SESSION["user"];
            else
                return null;
        }

        public function do_logout()
        {
            unset($_SESSION["user"]);
            unset($_SESSION["admin"]);

            session_destroy();
            header("Location: " . URL . "user/login");
        }

        public function isMobile()
        {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        }

        public function time_elapsed_string($datetime, $full = false)
        {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        public function goto_admin_login()
        {
            header("Location: " . URL . "admin/login");
        }

        public function get_logged_in_admin()
        {
            if ($this->is_admin_logged_in())
                return $this->load_model("AdminModel")->get_admin($_SESSION["admin"]);
            else
                return null;
        }
	}
?>