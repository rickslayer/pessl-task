<?php

namespace Metos\Services;

class EmailSender {

    public static function sendEmail($to) 
    {
        if(filter_var($to, FILTER_VALIDATE_EMAIL)) {

        }
    }
}