<?php
include(__DIR__. '/Schedule.php');
include(__DIR__. '/SlackNotification.php');


$schedule_instance = new Schedule();
$notification = new SlackNotification();
$notification->send_message($schedule_instance->find_schedules());
