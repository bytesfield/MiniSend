<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\SendMailInterface;
use App\Http\Requests\SendMailRequest;
use App\Http\Requests\SearchMailRequest;

class SendMailController extends Controller
{
    use JsonResponse;


    public function __construct(SendMailInterface $sendMailInterface)
    {
        $this->sendMailInterface = $sendMailInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Traits\JsonResponse
     */
    public function index()
    {
        return $this->sendMailInterface->getAllMails();
    }

    /**
     * Store a newly sent mail.
     *
     * @param  \App\Http\Requests\SendMailRequest  $request
     * @return \App\Traits\JsonResponse
     */

    public function store(SendMailRequest $request)
    {

        return $this->sendMailInterface->sendMail($request);
    }

    /**
     * Display the specified mail by $id.
     *
     * @param  int  $id
     * @return \App\Traits\JsonResponse
     */
    public function show($id)
    {
        return $this->sendMailInterface->getEmailById($id);
    }

    /**
     * Display the all mails by $recipient.
     *
     * @param  string  $recipient
     * @return \App\Traits\JsonResponse
     */
    public function showRecipientMails($recipient)
    {
        return $this->sendMailInterface->getEmailByRecipient($recipient);
    }

    /**
     * Display the all mails by search recipient, sender, subject.
     *
     * @param  \App\Http\Requests\SearchMailRequest;
     * @return \App\Traits\JsonResponse
     */
    public function getEmailSearch(SearchMailRequest $request)
    {
        return $this->sendMailInterface->getEmailBySearch($request);
    }

    /**
     * Remove the specified mail.
     *
     * @param  int  $id
     * @return \App\Traits\JsonResponse
     */
    public function destroy($id)
    {
        return $this->sendMailInterface->deleteMail($id);
    }
}
