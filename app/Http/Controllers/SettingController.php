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
        $userId = $events["id"];
        error_log($userId);
        $number = $events["number"];
        error_log($number);
        $push_new = $events["push_new"];
        error_log($push_new);
        $push_important = $events["push_important"];
        error_log($push_important);
        $push_cancel = $events["push_cancel"];
        error_log($push_cancel);
        $push_event = $events["push_event"];
        error_log($$push_event);
        return "success connect~";
    }
}
