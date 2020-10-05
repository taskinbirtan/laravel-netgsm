<?php

namespace TaskinBirtan\LaravelNetgsmSms;

class Netgsm
{
    public static function sendBulkSms($phones, $message)
    {
        $xmlData = static::setXml($phones, $message . '' . time());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.netgsm.com.tr/sms/send/xml");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function sendSingleSms($phone, $message)
    {
        $message = $message . '%20';
        $apiUrl = "http://api.netgsm.com.tr/bulkhttppost.asp?usercode=" . env('NETGSM_USERNAME') . "&password=" . env('NETGSM_PASSWORD') . "&gsmno=$phone&message=$message&msgheader=" . env('NETGSM_HEADER');
        $apiUrl = str_replace(' ', '%20', $apiUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private static function setXml($phones, $message)
    {
        foreach ($phones as $phone):
            $replace = '<no>' . $phone . '</no>';
        endforeach;
        return '<?xml version="1.0" encoding="UTF-8"?>
             <mainbody>
             <header>
             <company dil="TR">Netgsm</company>
             <usercode>'.env('NETGSM_USERNAME').'</usercode>
             <password>'.env('NETGSM_PASSWORD').'</password>
             <type>1:n</type>
             <msgheader>'.env('NETGSM_HEADER').'</msgheader>
             </header>
             <body>
             <msg>
             <![CDATA[' . $message . ']]>
             </msg>
             ' . $replace . '
             </body>
             </mainbody>';
    }
}
