<?php

namespace App\Interfaces;

use App\Http\Requests\SendMailRequest;

interface SendMailInterface
{
    /**
     * Get all Emails
     * 
     * @method  GET api/allMails
     * @access  public
     */
    public function getAllMails();

    /**
     * Get Email By ID
     * 
     * @param   integer     $id
     * 
     * @method  GET api/mail/{id}
     * @access  public
     */
    public function getEmailById($id);

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
     * @method  DELETE  api/mail/{id}
     * @access  public
     */
    public function deleteMail($id);
}
