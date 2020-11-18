<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function insertStudent(Request $request)
    {
        error_log("hello.....");
        error_log("request is" . $request->getContent());
        $events = json_decode($request->getContent(), true);
        $event = $events[0];
        $userId = $event["id"];
        error_log($userId);
        $number = $event["number"];
        error_log($number);
        $push_new = $event["push_new"];
        error_log($push_new);
        $push_important = $event["push_important"];
        error_log($push_important);
        $push_cancel = $event["push_cancel"];
        error_log($push_cancel);
        $push_event = $event["push_event"];
        error_log($push_event);
        return "success connect~";
    }
}
