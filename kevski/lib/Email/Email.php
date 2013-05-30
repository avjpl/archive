<?php
/*****************************************************
* File:     class.email.php
* Purpose:  Email Class
* Author:   Adrian Lawrence
* Date:     May 24, 2010
*****************************************************/

class Email {
    private $recipient;
    private $sender;
    private $reply_to;
    private $subject;
    private $body;
    private $cc;
    private $bcc;
    private $headers;

    /**
     * Constructor: Read in $file.
     * @param string $to        Recipient
     * @param array $from       "Sender's Name" => "Sender's Email"
     * @param string $subject   Email subject
     * @param string $body      Email body
     */
    public function __construct($to, $from = array(), $subject, $body) {
        // Set receiver
        $this->receiver($to);

        // Set sender
        if(!empty($from)) {
            $this->sender = array(key($from) => $from[key($from)]);
        } else {
            $this->sender = array($_SERVER['SERVER_ADMIN'] => $_SERVER['SERVER_ADMIN'] . '@' . $_SERVER['SERVER_NAME']);
        }

        // Set subject
        $this->subject($subject);

        // Set body
        $this->body($body);
    }

    /**
     * receiver(): Set receiver of the email
     * @param array $send_to List of Names & Email addresses
     */
    public function receiver($send_to) {
        if(is_array($send_to)) {
            foreach($send_to as $name => $email) {
                if($this->email_is_valid($email)) {
                    if(!is_numeric($name)) {
                        $this->recipient = ucfirst($name) . ' <' . $email . '>';
                    }
                    $this->recipient .= ', ';
                } else {
                    exit($email . " is not a valid email address.");
                }
            }
            $this->recipient = preg_replace('/, $/', '', $this->recipient);
        } else {
            if($this->email_is_valid($sent_to)) {
                echo $send_to;
                $this->recipient = $sent_to;
            }
        }
    }
    
    /**
     * sender(): Set send of the email
     * @param array $sender Name & Email address
     */
    public function sender($sender) {
        if(email_is_valid($sender[0])) {
            if(!is_numeric(key($sender))) {
                $this->sender = ucfirst(key($sender)) . ' <' . $email . '>';
            } else {
                $this->sender = $sender[0];
            }
        } else {
            exit( $sender[0] . " is not a valid email address." );
        }
    }

    /**
     * reply_to(): Set send of the email
     * @param array $reply_to Name & Email address
     */
    public function reply_to($reply_to) {
        if(email_is_valid($reply_to[0])) {
            if(!is_numeric(key($reply_to))) {
                $this->reply_to = ucfirst( key($reply_to) ) . ' <' . $email . '>';
            } else {
                $this->reply_to = $sender[key($reply_to)];
            }
        } else {
            exit($reply_to[0] . " is not a valid email address.");
        }
    }

    /**
     * cc(): Set Cc of the email
     * @param array $cc List of Names & Email addresses
     */
    public function cc($cc) {
        foreach($cc as $name => $email) {
            if(email_is_valid($email)) {
                if (!is_numeric($name)) {
                    $this->cc = ucfirst($name) . ' <' . $email . '>';
                } else {
                    $this->cc = $email;
                }
                $this->cc .= ', ';
            } else {
                exit($email . " is not a valid email address.");
            }
        }

        $this->cc = preg_replace('/, $/', '', $this->to);
    }

    /**
     * bcc(): Set Bcc of the email
     * @param array $bcc List of Names & Email addresses
     */
    public function bcc($bcc) {
        foreach($bcc as $name => $email) {
            if(!is_numeric($name)) {
                $this->bcc = ucfirst($name) . ' <' . $email . '>';
            } else {
                $this->bcc = $email;
            }
            $this->bcc .= ', ';
        }
        $this->bcc = preg_replace('/, $/', '', $this->to);
    }

    /**
     * set_headers(): Set email headers
     */
    public function set_headers() {
       // $this->set_from();
        
        $this->headers  = "MIME-Version: 1.0rn"
                        . "From: ". $this->sender . "rn"
                        . "To: " . $this->recipient . "rn";
        
        if(!empty($this->reply_to)) $this->headers .= "Reply-To: " . $this->reply_to . "rn";
        if(!empty($this->cc)) $this->headers .= "Cc: " . $this->cc . "rn";
        if(!empty($this->bcc)) $this->headers .= "Bcc: " . $this->bcc . "rn";

        $this->headers .= "X-Priority: 1rn"
                        . "X-Mailer: PHP/" . phpversion() . "rn"
                        . "Content-type: text/html; charset=iso-8859-1rn";

    }

    /**
     * subject(): Sets the subject of the email.
     * @param string $subject The subject message.
     */
    public function subject($subject) {
        // Strip any newlines
        $this->subject = str_replace('n', '', $subject);
    }

    /**
     * body(): Sets the body message of the email.
     * @param string $body The body message.
     */
    public function body($body) {
        $this->body = $body;
    }

    /**
     * send(): Send the email
     * @returns boolean True if successful, false if not.
     */
    public function send() {
        if(mail($this->recipient, $this->subject, $this->body, $this->headers)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * email_is_valid():    Check whether email address is valid
     * @param string $email Email address to check
     * @returns boolean     True if address is valid, false if not.
     */
    private function email_is_valid($email) {
        if (preg_match("/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/i", $email)) {
            return true;
        } else {
            return false;
        }
    }
}
?>