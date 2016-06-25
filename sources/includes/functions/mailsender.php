<?php

require_once 'utility.php';

/**
 * Class for email sending
 */
class MailSender {

    private $from;
    private $to;
    private $subject;
    private $body;
    private $dataValid;

    /**
     * Constructor
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    function __construct($from, $to, $subject, $body) {
        if (Utility::isCorrectEmail($from) && Utility::isCorrectEmail($to)) {
            $this->from = $from;
            $this->to = $to;
            $this->subject = $subject;
            $this->body = $body;
            $this->dataValid = true;
        } else {
            $this->dataValid = false;
        }
    }

    /**
     * Send email
     * @return bool
     */
    public function Send() {

        if (!$this->dataValid)
            return false;

        $headers = "From: " . $this->from . "\r\n";
        if (mail($this->to, $this->subject, $this->body, $headers)) {
            return true;
        } else {
            return false;
        }
    }

}

?>
