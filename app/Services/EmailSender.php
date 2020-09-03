<?php

namespace Metos\Services;

use Exception;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailSender {

    public static function sendEmail($to, $html)
    {
        $response = array();
        if(filter_var($to, FILTER_VALIDATE_EMAIL)) {
           
            $email = new Mail(); 
            $email->setFrom("alerts@metos.at", "Metos Field Climate");
            $email->setSubject("Alert from FieldClimate Wheater Station");
            $email->addTo($to);
            $email->addContent(
                "text/html", "<span>$html<span>"
            );

            $sendgrid = new SendGrid(getenv('SEND_GRID_API_KEY'));
            try {
                 $response = $sendgrid->send($email);
                
            } catch (Exception $e) {
                return array (
                    "message" => $e->getMessage(),
                    "success" => false
                );
            }
        }else {
            return array (
                "message" => "Invalid email",
                "success" => false
            );
        }
        return array(
            "status_code" => $response->statusCode(),
            "message" => "Email sent successfull",
            "success" => true
        );
    }
}