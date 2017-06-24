<?php
require_once(__DIR__. '/vendor/autoload.php');
require_once(__DIR__. '/Define.php');
use Trello\Client;
date_default_timezone_set('Asia/Tokyo');

class Schedule
{
    public function __construct() {}
    public function find_schedules(): string
    {
        $client = new Client();
        $client->authenticate(Define::$api_key, Define::$access_token, Client::AUTH_URL_CLIENT_ID);
        $lists = $client->api('boards')->lists()->all(Define::$board_id);
        $result = date('Y/m/d H:i:s'). "\n";
        foreach ($lists as $list) {
            $cards = $client->api("lists")->cards()->filter($list['id'], 'open');
            if (empty($list['name'])) continue;
            foreach ($cards as $card) {
                if (!empty($card)) {
                    $has_deadline = preg_match('/^(.+)T.+$/', $card['due'], $match);
                    $result .= $this->parse_text($list['name'], $card['name'], $has_deadline ? $match[1] : '');
                }
            }
        }
        return $result;
    }

    private function parse_text(string $list_name, string $title, string $deadline): string
    {
        return sprintf("%s=>\n[チケット名=> %s, 締め切り=> %s]\n", $list_name, $title, !empty($deadline) ? $deadline : "ありません");
    }
}


