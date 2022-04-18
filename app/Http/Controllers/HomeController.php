<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Mails;
use App\Models\Personals;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// use Dacastro4\LaravelGmail\Src\Facade\LaravelGmail;


class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        if(!LaravelGmail::check())
        {
            return redirect('/oauth/gmail');
        }

        $this->access_token = LaravelGmail::getToken()['access_token'];
        $this->url = "https://www.googleapis.com/gmail/v1/users/me/";
        $this->user = LaravelGmail::user();

        $personaladd = Personals::updateOrCreate([
            'mail' => $this->user,
        ],[
            'mail' => $this->user, 'access_token' => $this->access_token
        ]);

        $this->personal_id = Personals::where("mail", $this->user)->first()->id;
    }


    public function index($pageToken=null){
        // $accessToken = LaravelGmail::makeToken();
        //     @if(LaravelGmail::check())
        //     <a href="{{ url('oauth/gmail/logout') }}">logout</a>
        // @else
        //     <a href="{{ url('oauth/gmail') }}">login</a>
        // @endif
        // dd(LaravelGmail::check());
        if (LaravelGmail::check()) {
            // $messages = Http::get($this->url."messages?maxResults=10&q=in:inbox&access_token=".$this->access_token."&pageToken=".$pageToken);
            $messages = Http::get($this->url."messages?q=in:inbox&access_token=".$this->access_token."&pageToken=".$pageToken);
            // dd($response);,
            $customers = array();
            $messages = json_decode($messages)->messages;
            $sayac = 0;
            foreach($messages as $message){

                    $messageDetail = Http::get("https://gmail.googleapis.com/gmail/v1/users/me/messages/".$message->threadId."?access_token=".$this->access_token);
                    // return $this->gmailBodyDecode(json_decode($messageDetail)->raw);
                    $messageExport = '';
                    $json = json_decode($messageDetail)->payload->headers;
                    $from = '';
                    $headers = $json;
                    $snippet = json_decode($messageDetail)->snippet;
                    
                    foreach($headers as $hkey => $header){
                        if ($header->name == 'From'){

                            $customers[$message->threadId] = $header->value;
                        }
                    }
                // $sayac++;
                    $personal_id = Personals::where("mail", $this->user)->first()->id;
                 // return $customers->id;                 

                 if(isset(json_decode($messageDetail)->payload->parts)){
                     $parts = json_decode($messageDetail)->payload->parts;
                     foreach($parts as $part){
                         $messageExport .= isset($part->body->data) ? $part->body->data : '';
                     }
                     // return "parts";
                 }else{
                    //  return json_decode($messageDetail)->payload->body->data;
                     $messageExport .= json_decode($messageDetail)->payload->body->data;
                 }
                //  echo "<p>".$messageExport."</p>";

                $mails = Mails::updateOrCreate([
                    'mail_id' => $message->threadId,
                    'personal_id' => $personal_id,
                ],[
                    'mail_id' => $message->threadId,
                    'personal_id' => $personal_id,
                    'mail_content' => $messageExport,
                    'snippet' => $snippet
                ]);
                
                


            }
            // dd($customers);
            foreach($customers as $ckey => $cvalue){
                if(strstr($cvalue, '<')){
                    $data = explode(' <', $cvalue);
                    $data = explode('>', $data[1]);
                    $email = $data[0];
                }else {
                    $email = $cvalue;
                }
                $customers = Customers::updateOrCreate([
                    'email' => $email
                ],[
                    'email' => $email, 'name' => $email, 'personal_id' => $this->personal_id
                ]);
                
                // echo $customers->id;
                $mails = Mails::where('mail_id', $ckey)->first();
                $mails->customer_id = $customers->id;
                $mails->save();
            }


            // dd($messages);
            // echo $messages[0]->getHtmlBody;
            // foreach($messages as $message){
            //     echo $message->getMessagesResponse();
            // }
            echo '<a href="'.url('oauth/gmail/logout').'">logout</a>';
            # code...
        } else {
            echo '<a href="'.url('oauth/gmail').'">login</a>';
            # code...
        }
    }

    public function gmailBodyDecode($data) {
        $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data)); 
        //from php.net/manual/es/function.base64-decode.php#118244
    
        $data = quoted_printable_decode($data);
        return $data;
    }     

    public function From($array)
    {
        // returns if the input integer is even
        if($array === 'From')
        return TRUE;
        else 
        return FALSE; 
    }
}
