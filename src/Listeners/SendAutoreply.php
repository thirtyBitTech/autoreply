<?php

namespace ThirtyBitTech\Autoreply\Listeners;

use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\Mail;
use ThirtyBitTech\Autoreply\Mail\AutoReply;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Arr;


class SendAutoreply
{
    /**
     * Handle the event.
     *
     * @param FormSubmitted $event
     * @return void
     */
    public function handle(FormSubmitted $event)
    {
        $formData = Arr::except($event->submission->data(), ['random_id']);
        $form = $event->submission->form();
        $config = config('autoreply.forms');

        if ($config) {    
            foreach ($config as $formConfig) {
                if ($formConfig['form'] === $form->handle() && $formConfig['auto_reply_enabled']) {
                    $submissionNumber = uniqid();
                    $autoReplyData = $formData->toArray();

                
                    
                    $email = $event->submission->{$formConfig['email_field']};
                    $name = isset($formConfig['name_field']) ? $event->submission->{$formConfig['name_field']} : null;

                    try {

                         // Validate email format first
                        $validator = Validator::make(['email' => $email], [
                            'email' => 'required|email'
                        ]);

                        if ($validator->fails()) {
                            Log::warning('Invalid email address for auto-reply: ' . $email);
                            // Optionally skip sending or return an error here
                            return;
                        }

                        $autoreply = new AutoReply(
                            $autoReplyData, 
                            $email, 
                            $name,
                            $submissionNumber, 
                            $formConfig
                        );
                    
                        Mail::send($autoreply); 
                    
                    } catch (Exception $e) {
                        Log::error('Auto-reply email failed to send: ' . $e->getMessage());
                    }

                    if ($formConfig['include_submission_number'] ?? false) {
                        $autoReplyData['submission_number'] = $submissionNumber;
                        $event->submission->random_id = $submissionNumber;
                        $event->submission->save();
                    }
                }
            }
        }
    }
}