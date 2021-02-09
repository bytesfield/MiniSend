<?php

namespace App\Repositories;

use DB;
use App\Models\Email;
use App\Models\Attachment;
use App\Traits\JsonResponse;
use App\Traits\FileAction;
use Illuminate\Http\Request;
use App\Interfaces\SendMailInterface;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\SendMailRequest;
use Illuminate\Support\Facades\Validator;

class SendMailRepository implements SendMailInterface
{
    // Use ResponseAPI Trait in this repository
    use JsonResponse;
    use FileAction;

    public function getAllMails()
    {
        try {

            $emails = Email::orderBy('created_at', 'asc')->with('attachments')->get()->map->format();

            return $this->success("All Mails", $emails);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function getEmailById($id)
    {
        try {
            $mailCheck = Email::find($id);

            if (!$mailCheck) return $this->notFound("No Email with ID $id");
            $email = $mailCheck->format();

            return $this->success("Email Details", $email);
        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function sendMail(SendMailRequest $request)
    {
        DB::beginTransaction();
        try {
            $files = $request->files;
            $from = $request->from;
            $to = $request->to;
            $subject = $request->subject;
            $content = $request->content;
            $path = '/files';

            $data = [

                'attachments' => $files,
                'from' => $from,
                'subject' => $subject,
                'content' => $content
            ];

            $email = new Email;
            $email->from = $from;
            $email->to = $to;
            $email->subject = $subject;
            $email->content = $content;

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
                return $this->success("Email Stored Successfully");
            }

            return $this->error("Something went wrong,Try again");
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }


    public function deleteMail($id)
    {
        DB::beginTransaction();
        try {
            $email = Email::find($id);

            if (!$email) {
                return $this->error("No Email with ID $id");
            }
            if ($email->delete()) {
                DB::commit();
                return $this->success("Email deleted", $email);
            }
            return $this->error("Something went wrong, Try Again");
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
