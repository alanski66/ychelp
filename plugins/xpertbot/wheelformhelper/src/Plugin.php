<?php
namespace wheelformhelper;

use wheelform\Mailer;
use yii\base\Event;
use wheelform\controllers\MessageController;

class Plugin extends \craft\base\Plugin
{

    public function init()
    {
        parent::init();

        //First Event to modify values before saving to database
        Event::on(MessageController::class, MessageController::EVENT_BEFORE_SAVE, function($event){
            // Check for correct form
            if($event->form_id == "1") {
                // $field is an ActiveRecord Object
                foreach($event->message as $field) {
                    if($field->field->name == "message") {
                        $field->value = "Custom Value from BeforeSave event";
                    }
                }
            }
        });

        //Values from Above Event to be modified for email
        Event::on(Mailer::class, Mailer::EVENT_BEFORE_SEND, function($event)
        {
            //Add extra fields to message
//            $event->message['extra_field'] = [
//                'label' => 'New Label',
//                'value' => 'Lorem Ipsum',
//                'type' => "text"
//            ];

            //Add static Reply_to email
            $event->reply_to = 'reply@example.com';

            //Add reply_to based on form field
            if($event->form_id == 1) {
                if(! empty($event->message['email']['value'])) {
                    $event->reply_to = $event->message['email']['value'];
                }
            }

            //Conditional To Emails
            //If user selected Chocolate as favorite 'flavour' field. (this can be radio, select, text, etc)
            //It's a good idea to add more checks such as form ID and valid email.
            if(array_key_exists('Location', $event->message)) {
                if($event->message['Location']['value'] == 'Brighton' ) {
                    $event->to[] = 'brighton@justlife.org.uk';
                }
            }
            if(array_key_exists('Location', $event->message)) {
                if($event->message['Location']['value'] == 'Manchester' ) {
                    $event->to[] = 'info@justlife.org.uk';
                }
            }
            if(array_key_exists('Location', $event->message)) {
                if($event->message['Location']['value'] == 'Other enquiry' ) {
                    $event->to[] = 'info@justlife.org.uk';
                }
            }

            //If user selected specific 'toppings' in a checkbox field.
            if (array_key_exists('toppings', $event->message)) {
                if (is_array($event->message['toppings'])) {
                    if(in_array('Fudge', $event->message['toppings']['value'])) {
                        $event->to[] = 'fudge@example.com';
                    }
                }
            }
        });

        // Final Values to use for any third party libraries
        Event::on(Mailer::class, Mailer::EVENT_AFTER_SEND, function($event)
        {
            // Mailchimp, Stripe, ConstactContact Integrations with final values
        });
    }
}
