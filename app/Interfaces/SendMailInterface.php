<?php

namespace App\Interfaces;

use App\Http\Requests\SearchMailRequest;
use Illuminate\Http\Request;
use App\Http\Requests\SendMailRequest;

interface SendMailInterface
{
    /**
     * Get all Emails
     * 
     * @method  GET api/get/mails
     * @access  public
     */
    public function getAllMails();

    /**
     * Get Email By ID
     * 
     * @param integer $id
     * 
     * @method  GET api/get/mail/{id}
     * @access  public
     */
    public function getEmailById(int $id);

    /**
     * Get Email By Recipient
     * 
     * @param string $recipient
     * 
     * @method  GET api/get/mail/recipient/{recipient}
     * @access  public
     */
    public function getEmailByRecipient(string $recipient);

    /**
     * Get Email By Recipient
     * 
     * @param \App\Http\Requests\SearchMailRequest $request
     * 
     * @method  GET api/get/mail/search
     * @access  public
     */
    public function getEmailBySearch(SearchMailRequest $request);


    /**
     * Store Sent Mail
     * 
     * @param  \App\Http\Requests\SendMailRequest $request
     * @param  integer $id
     * 
     * @method  POST api/sendMail
     * @access  public
     */
    public function sendMail(SendMailRequest $request);

    /**
     * Delete Mail
     * 
     * @param integer $id
     * 
     * @method  DELETE  api/delete/mail/{id}
     * @access  public
     */
    public function deleteMail(int $id);
}
