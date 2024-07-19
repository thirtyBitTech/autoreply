<?php

namespace ThirtyBitTech\Autoreply\Listeners;

use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\Mail;
use ThirtyBitTech\Autoreply\Mail\AutoReply;

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
        $formData = $event->submission->data();
        $form = $event->submission->form();
        $config = config('autoreply.forms');

        foreach ($config as $formConfig) {
            if ($formConfig['form'] === $form->handle() && $formConfig['auto_reply_enabled']) {
                $submissionNumber = uniqid();
                $autoReplyData = $formData->toArray();

              
                
                $email = $event->submission->{$formConfig['email_field']};
                $name = isset($formConfig['name_field']) ? $event->submission->{$formConfig['name_field']} : null;

                $autoreply = new AutoReply(
                    $autoReplyData, 
                    $email, 
                    $name,
                    $submissionNumber, 
                    $formConfig
                    
                );
                
                Mail::send($autoreply); 

                if ($formConfig['include_submission_number'] ?? false) {
                    $autoReplyData['submission_number'] = $submissionNumber;
                    $event->submission->random_id = $submissionNumber;
                    $event->submission->save();
                }
            }
        }
    }
}