<?php

namespace Metos\Services;

use Exception;
use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Cache;


class EmailSender {

    /**
     *  Main responsable for send email
     * @param string $to - email to send
     * @param string $html - a string for a html body
     * 
     * return array
     */

    public static function sendEmail($to, $html) : array
    {
        $response = array();
        if(filter_var($to, FILTER_VALIDATE_EMAIL)) {

            $html_body = "";
            $html_body .= "<ul>";
            foreach ($html as $items) {
                if (is_array($items) ){
                    foreach($items as $li) {
                        $html_body .= "<li>$li</li>";
                    }
                }else {
                    $html_body .= $items;
                }
                
            }
            $html_body .= "</ul>"; 
           
            $email = new Mail(); 
            $email->setFrom("alerts@metos.at", "Metos Field Climate");
            $email->setSubject("Alert from FieldClimate Wheater Station");
            $email->addTo($to);
            $email->addContent(
                "text/html", "<h1>Checkout alerts from Fieldclimate</h1>${html_body}"
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

    /**
     * Controls email to send between 15 and 15 minutes
     * @param string $to - email to send
     * @param string $html - a string for a html body
     * 
     * return array
     */
    public static function checkEmailFrequence($to, $html) : array
    {
        $result = array();
        $html_key = $to . "_html";
        $list_html = Cache::store('redis')->get($html_key);
        $list_html[] = $html;
        if (Cache::store('redis')->get($to)) {
            Cache::store('redis')->forever($html_key, $list_html);
            $result['need_to_send'] = false;
            $result['html'] = $list_html;
            
        }else {
            Cache::store('redis')->put($to, $to, 900);
            Cache::store('redis')->forever($html_key, $list_html);
            $result['need_to_send'] = true;
            $result['html'] = $list_html;
        }

        return $result;

    }
}
