<?php

namespace Metos\Services;

use Carbon\Carbon;
use Exception;
use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Support\Facades\Cache;


class EmailSenderService {
    /**
     * @param string $to - email to send
     */
    private $to;

    /**
     * @param string $html - a string for a html body
     */
    private $html;

    /**
     *  Main responsable for send email
     * @return array
     */

    public function sendEmail() : array
    {
        $response = array();
        if(filter_var($this->getTo(), FILTER_VALIDATE_EMAIL)) {

            $html_body = "";
            $html_body .= "<ul>";
            foreach ($this->html as $items) {
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
            $email->addTo($this->getTo());
            $email->addContent(
                "text/html", "<h1>Checkout alerts from Fieldclimate</h1></br></br>${html_body}"
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
        $status_code = $response->statusCode();
        return array(
            "status_code" => $status_code ,
            "message" => "Email sent successfull",
            "success" => true
        );
    }

    /**
     * Controls email to send
     * @return array
     */
    public function checkEmailFrequence() : array
    {
        $result = array();
        $html_key = $this->to . "_html";
        $list_html = Cache::store('redis')->get($html_key);
        $list_html[] = $this->getHtml();
        if (Cache::store('redis')->get($this->to)) {
            Cache::store('redis')->forever($html_key, $list_html);
            $result['need_to_send'] = false;
            $result['html'] = $list_html;
            
        }else {
            $main_email = Cache::store('redis')->get('MAIN_MAIL') ?? getenv('MAIN_MAIL');
            $user_param = Cache::store('redis')->get("{$main_email}_data");

            $email_frequency =  $user_param['frequency_email'] ?? getenv('SEND_EMAIL_FREQUENCY',8);

            Cache::store('redis')->put($this->getTo(),$this->getTo(), Carbon::now()->addHours($email_frequency)); //Default 8 hrs
            Cache::store('redis')->put($this->getTo(), $this->getTo(),10); //Default 8 hrs
            Cache::store('redis')->forever($html_key, $list_html);

            $result['need_to_send'] = true;
            $result['html'] = $list_html;
        }

        return $result;

    }

    public function getTo(){
		return $this->to;
	}

	public function setTo($to){
		$this->to = $to;
	}

	public function getHtml(){
		return $this->html;
	}

	public function setHtml($html){
		$this->html = $html;
	}
}
