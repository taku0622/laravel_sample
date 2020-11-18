<?php

namespace App\Http\Controllers;

use Hamcrest\Text\IsEmptyString;
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
        $number = $event["number"];
        $push_new = $event["push_new"]; //false
        error_log(empty($push_new));
        $push_important = $event["push_important"]; //false
        error_log(empty($push_important));
        $push_cancel = $event["push_cancel"]; //true
        error_log(empty($push_cancel));
        $push_event = $event["push_event"]; //true
        error_log(empty($push_event));
        return "success connect~";
    }
}
