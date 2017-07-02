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
        $result = "";
        foreach ($lists as $list) {
            $cards = $client->api("lists")->cards()->filter($list['id'], 'open');
            if (empty($list['name'])) continue;
            foreach ($cards as $card) {
                if (!empty($card)) {
                    $has_deadline = preg_match('/^(.+)T.+$/', $card['due'], $match);
                    if ($has_deadline && in_array($list['name'], Define::$send_lists) && $this->is_dead($match[1])) {
                        $result .= $this->parse_text($list['name'], $card['name'], $match[1]);
                    }
                }
            }
        }
        return date('Y/m/d H:i:s'). "\n".
            (empty($result) ? "特に締め切りが近いタスクはありません" : $result);
    }

    private function parse_text(string $list_name, string $title, string $deadline): string
    {
        return sprintf("%s=>\n[チケット名=> %s, 締め切り=> %s]\n", $list_name, $title, !empty($deadline) ? $deadline : "ありません");
    }

    private function is_dead(string $com_date_time): bool
    {
        $type = new DateTime();
        $type->modify(Define::$deadline);
        $deadline = $type->format('Y-m-d H:i:s');
        if ($com_date_time <= $deadline) return true;
        else return false;
    }
}


