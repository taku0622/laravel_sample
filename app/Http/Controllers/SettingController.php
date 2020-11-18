<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $push_important = $event["push_important"]; //false
        $push_cancel = $event["push_cancel"]; //true
        $push_event = $event["push_event"]; //true
        // 学部判定
        $department = substr($number, 0, 2);
        error_log($department);
        // DB::table('students')->insert([
        //     'user_id' => $userId,
        //     'number' => $number,
        //     'department' => $department,
        //     'push_new' => $push_new,
        //     'push_important' => $push_important,
        //     'push_cancel' => $push_cancel,
        //     'push_event' => $push_event,
        // ]);
        return "success connect~";
    }
}
