<?php

namespace ThirtyBitTech\Autoreply\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \Statamic\Facades\Asset;
use Illuminate\Support\Facades\View;


class AutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public $reference = "autoreply";
    public $body = [];
    public $submissionNumber;
    public $subject;
    public $message;
    public $senderName;
    public $senderEmail;
    public $formData;
    public $formConfig;
    public $attachment;
    public $recieverEmail;
    public $recieverName;

    /**
     * Create a new message instance.
     *
     * @param array $body
     * @param string|null $submissionNumber
     * @param array $formConfig
     */
    public function __construct(array $formData,string $recieverEmail,?string $recieverName,  ?string $submissionNumber, array $formConfig)
    {
        $this->formData = $formData;
        $this->formConfig = $formConfig;
        $this->submissionNumber = $submissionNumber;
        $this->subject = $formConfig['auto_reply_subject'] ?? 'Thank you for your submission';
        $this->message = $formConfig['auto_reply_message'] ?? 'We have received your submission.';
        $this->senderName = $formConfig['sender_name'] ?? config('mail.from.name');
        $this->senderEmail = $formConfig['sender_email'] ?? config('mail.from.address');
        $this->attachment = $formConfig['attachment'] ?? null;
        $this->recieverEmail = $recieverEmail;
        $this->recieverName = $recieverName ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $view = View::exists('vendor.autoreply.emails.autoreply') 
                        ? 'vendor.autoreply.emails.autoreply' 
                        : 'autoreply::emails.autoreply';

        $email = $this->from($this->senderEmail, $this->senderName)
                      ->to( $this->recieverEmail , $this->recieverName)
                      ->markdown($view)
                      ->subject($this->subject);

      

        // @todo fix the hardcoding
        if ($this->attachment) {
            $asset = Asset::find('/assets/' . $this->attachment);
        
            if ($asset && $asset->exists()) {
                $disk = $asset->disk();
                $path = $disk->filesystem()->path($asset->path());
                $email->attach($path);
            }
        }

        return $email;
    }
}