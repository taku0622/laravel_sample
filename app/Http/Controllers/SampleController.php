<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
// use App\Models\Frameworks;

class SampleController extends Controller
{
    public function index()
    {
        $fruit_list = [
            [
                "name" => "apple",
                "price" => 100
            ],
            [
                "name" => "banana",
                "price" => 300
            ],
            [
                "name" => "peach",
                "price" => 500
            ],
        ];

        $total = 0;
        foreach ($fruit_list as $fruit) {
            $total += $fruit["price"];
        }

        return view('sample', [
            "fruit_list" => $fruit_list,
            "total" => $total
        ]);
    }

    public function select()
    {
        $pochi = Pet::find(1);

        return view('select', [
            "pochi" => $pochi
        ]);
    }

    public function selectMany()
    {
        $pets = Pet::orderBy('id', 'asc')->get();

        return view('select_many', [
            "pets" => $pets
        ]);
    }

    public function insert()
    {
        $pet = new Pet();

        $pet->name = "shiro";
        $pet->birthday = "1980/06/16";
        $pet->gender = "female";

        $pet->save();

        return "データを挿入しました";
    }

    public function delete()
    {
        Pet::orderBy('id', 'desc')->first()->delete();

        return "データを削除しました";
    }

    public function update()
    {
        Pet::orderBy('id', 'desc')->first()->update(['name' => 'jonny']);

        return "データを更新しました";
    }
}
