<?php
require_once(__DIR__. '/Define.php');

class SlackNotification
{
    public function __construct() {}

    private function notificate(string $message): bool
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->create_options($message));
        $result = curl_exec($ch);
        curl_close($ch);
        return !empty($result);
    }

    private function create_options(string $message): array
    {
        return array(
            CURLOPT_URL => Define::$url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->create_post_info($message),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
        );

    }

    private function create_post_info(string $message): string
    {
        $body = array(
            'channel' => Define::$channel,
            'username' => Define::$user_name,
            'text' => $message,
        );
        return "payload=". urlencode(json_encode($body));
    }

    public function send_message(string $message)
    {
        try {
            $isSend = $this->notificate($message);
            if (!$isSend) throw new RuntimeException;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }
}
