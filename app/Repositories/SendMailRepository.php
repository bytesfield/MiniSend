<?php

namespace App\Repositories;

use DB;
use App\Models\Email;
use App\Mail\SendMail;
use App\Jobs\SendEmail;
use App\Models\Attachment;
use App\Traits\FileAction;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\SendMailInterface;
use App\Http\Requests\SendMailRequest;
use App\Http\Requests\SearchMailRequest;
use Illuminate\Support\Facades\Validator;

class SendMailRepository implements SendMailInterface
{

    use JsonResponse;
    use FileAction;

    public function getAllMails()
    {
        try {

            $emails = Email::orderBy('created_at', 'desc')->with('attachments')->get()->map->format()->toArray();

            return $this->success("All Mails", $emails);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function getEmailById(int $id)
    {
        try {
            $mailCheck = Email::find($id);

            if (!$mailCheck) {
                return $this->notFound("No Email with ID $id");
            }

            $email = $mailCheck->format();

            return $this->success("Email Details", $email);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function getEmailByRecipient(string $recipient)
    {
        try {
            $mailCheck = Email::where('to', $recipient)->get();

            if (count($mailCheck) < 1) {
                return $this->notFound("No Email found with $recipient as recipient");
            }
            $email = $mailCheck->map->format()->toArray();

            return $this->success("Recipient $recipient Email Details", $email);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function getEmailBySearch(SearchMailRequest $request)
    {
        try {
            $search = $request->search;

            $query = Email::query();

            $pipes = [
                \App\QueryFilters\Search\FilterRecipient::class,
                \App\QueryFilters\Search\FilterSender::class,
                \App\QueryFilters\Search\FilterSubject::class,
            ];

            $result = app(Pipeline::class)
                ->send($query)
                ->through($pipes)->thenReturn();

            $emails = $result->get()->map->format()->toArray();
            if (!$emails) {
                return $this->notFound("No result found for $search");
            }

            return $this->success("Result for $search", $emails);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function sendMail(SendMailRequest $request)
    {
        DB::beginTransaction();
        try {
            $files = array($request->file('files'));
            $from = $request->from;
            $to = $request->to;
            $subject = $request->subject;
            $content = $request->content;
            $path = '/files';

            $data = [

                'attachments' => $files,
                'from' => $from,
                'subject' => $subject,
                'content' => $content,
                'to' => $to
            ];

            Mail::to($to)->send(new SendMail($data));
            //dispatch(new SendEmail($data));

            $email = new Email;
            $email->from = $from;
            $email->to = $to;
            $email->subject = $subject;
            $email->content = $content;
            $email->status = 'Sent';

            if ($email->save()) {

                if ($request->hasFile('files')) {

                    for ($i = 0; $i < count($files); $i++) {

                        $file = $files[$i];
                        $uploadedFile = $this->uploadFile($file, $path);

                        $attachment = new Attachment;
                        $attachment->email_id = $email->id;
                        $attachment->file = $uploadedFile;
                        $attachment->save();
                    }
                }

                DB::commit();
                return $this->success("Email Send Successfully");
            }

            return $this->error("Something went wrong,Try again");
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }


    public function deleteMail(int $id)
    {
        DB::beginTransaction();
        try {
            $email = Email::find($id);

            if (!$email) {
                return $this->error("No Email with ID $id");
            }
            if ($email->delete()) {
                DB::commit();
                return $this->success("Email deleted successfully");
            }
            return $this->error("Something went wrong, Try Again");
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
