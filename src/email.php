<?php

namespace FC;

/**
 * USAGE: 
 *      Instantiate a new object
 *          $email = new Email();
 *      Set mandatory from address with optional name
 *          $email->setFrom("email_address", "recipient_name");
 *      Add mandatory to address(es)
 *          $email->addTo("to_email_address", "to_name");
 *      [Add optional reply-to address(es) with optional name(s)]
 *          $email->addReplyTo("email_address", "recipient_name");
 *      [Add optional cc address(es)]
 *          $email->addCc("email_address");
 *      [Add optional bcc address(es)]
 *          $email->addBcc("email_address");
 *      Set mandatory subject
 *          $email->setSubject("email_subject");
 *      Set mandatory message
 *          $email->setMessage("email_message");
 *      Send email
 *          $email->send();
 */

/**
 * TODO: 
 *  - Allow attachments
 *  - Handle exceptions instead of error numbers
 */

/**
 * PHP library to deliver emails to several recipients
 */
class Email {
/* constants */
    const EMAIL_IDX = 0;
    const NAME_IDX = 1;
    const TEXT = "text/plain;";
    const HTML = "text/html;";
    const UTF8_CHARSET = "UTF-8";
    const ANSI_CHARSET = "Windows-1252";
    const ISO_8859_1_CHARSET = "ISO-8859-1";

/* member variables */
    private $from; // 2D array (1 row only): [0] email, [1] name (optional)
    private $replyTo; // 2D array: [0] email, [1] name (optional)
    private $to; // 2D array: [0] email, [1] name (optional)
    private $cc; // 1D array: [0] email
    private $bcc; // 1D array: [0] email
    private $headers; // 1D array for all email headers
    private $subject;
    private $message;

/* member functions */
    public function getFrom() { return $this->from; }
    public function getReplyTo() { return $this->replyTo; }
    public function getTo() { return $this->to; }
    public function getCc() { return $this->cc; }
    public function getBcc() { return $this->bcc; }
    public function getSubject() { return $this->subject; }
    public function getMessage() { return $this->message; }

    public function setFrom(string $parEmail, string $parName = '') { 
        $this->from[0][self::EMAIL_IDX] = $parEmail;
        $this->from[0][self::NAME_IDX] = $parName;
    }
    public function addReplyTo(string $parEmail, string $parName = '') {
        $idx = sizeof($this->replyTo);
        $this->replyTo[$idx][self::EMAIL_IDX] = $parEmail;
        $this->replyTo[$idx][self::NAME_IDX] = $parName;
    }
    public function addTo(string $parEmail, string $parName = '') {
        $idx = sizeof($this->to);
        $this->to[$idx][self::EMAIL_IDX] = $parEmail;
        $this->to[$idx][self::NAME_IDX] = $parName;
    }
    public function addCc(string $parEmail) {
        $idx = sizeof($this->cc);
        $this->cc[$idx][self::EMAIL_IDX] = $parEmail;
    }
    public function addBcc(string $parEmail) {
        $idx = sizeof($this->bcc);
        $this->bcc[$idx][self::EMAIL_IDX] = $parEmail;
    }
    public function setSubject(string $parSubject) { $this->subject = $parSubject; }
    public function setMessage(string $parMessage) { $this->message = $parMessage; }

    public function resetFrom() { $this->from = array(); }
    public function resetReplyTo() { $this->replyTo = array(); }
    public function resetTo() { $this->to = array(); }
    public function resetCc() { $this->cc = array(); }
    public function resetBcc() { $this->bcc = array(); }
    public function resetAll() {
        $this->from = array();
        $this->replyTo = array();
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->headers = array();
        $this->subject = '';
        $this->message = '';
    }
    
/* constructor */
    function __construct() {
        $this->resetAll();
    }

/* methods */
    /**
     * Init email from, to, reply-to, cc, bcc, headers and verify if everything mandatory has been initialized
     *
     * @param string $parContentType content type
     * @param string $parCharset charset
     * @return int 0 on success, negative error numbers otherwise
     */
    private function initEmail(string $parContentType = self::TEXT, string $parCharset = self::UTF8_CHARSET) {
        // from address missing
        if ($this->from == '') { return -1; }
        // to address(es) missing
        if (sizeof($this->to) == 0) { return -2; }
        // subject text missing
        if ($this->subject == '') { return -3; }
        // message text missing
        if ($this->message == '') { return -4; }

        $this->headers = array();

        // mime version
        $this->headers[] = 'MIME-Version: 1.0';
        // content type
        $this->headers[] = "Content-Type: ". $parContentType ."; charset=" . $parCharset;

        // from
        $this->headers[] = 'From: ' . $this->emailWithNameArrayToString($this->from);
        // reply to
        if (sizeof($this->replyTo) > 0) { $this->headers[] = 'Reply-to: ' . $this->emailWithNameArrayToString($this->replyTo); }
        // cc
        if (sizeof($this->cc) > 0) { $this->headers[] = 'Cc: ' . $this->emailArrayToString($this->cc); }
        // bcc
        if (sizeof($this->bcc) > 0) { $this->headers[] = 'Bcc: ' . $this->emailArrayToString($this->bcc); }

        return 0;
    }

    /**
     * Send an email
     *
     * @param string $parContentType content type
     * @param string $parCharset charset 
     * @return bool true on success, false on failure
     */
    public function send(string $parContentType = self::TEXT, string $parCharset = self::UTF8_CHARSET) {
        // verify if headers have been initialized
        if (sizeof($this->headers) == 0) { 
            $r = $this->initEmail($parContentType, $parCharset);
            if ($r != 0) { return $r; }
        }

        // init to
        $toList = $this->emailWithNameArrayToString($this->to);

        // send email
        $r = mail($toList, $this->subject, $this->message, implode("\r\n", $this->headers));

        return $r;
    }

    /**
     * Transform an array of emails and names into a string formatted for email headers.
     * Return the formatted string
     *
     * @param array $parEmailName array containing emails and names
     * @return string
     */
    private function emailWithNameArrayToString(array $parEmailName) {
        $list = '';
        for ($i = 0, $imax = sizeof($parEmailName); $i < $imax; $i++) {
            if ($parEmailName[$i][self::EMAIL_IDX] != '') {
                $list .= sprintf('"%s" <%s>', $parEmailName[$i][self::NAME_IDX], $parEmailName[$i][self::EMAIL_IDX]);
                $list .= ',';
            }
            else {
                $list .= sprintf('%s', $parEmailName[$i][self::EMAIL_IDX]);
                $list .= ',';
            }
        }
        $list = substr($list, 0, strlen($list) - 1);

        return $list;
    }

    /**
     * Transform an array of emails into a string formatted for email headers.
     * Return the formatted string
     *
     * @param array $parEmailName array containing emails and names
     * @return string
     */
    private function emailArrayToString(array $parEmail) {
        $list = '';
        for ($i = 0, $imax = sizeof($parEmail); $i < $imax; $i++) {
            $list .= sprintf('%s', $parEmail[$i][self::EMAIL_IDX]);
            $list .= ',';
        }
        $list = substr($list, 0, strlen($list) - 1);

        return $list;
    }
}

