<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class SellController extends Controller
{
    // 出品画面を表示
    public function sell()
    {
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態がわるい',
        ];

        $categories = Category::all();

        return view('sell', compact('categories', 'conditions'));
    }

    public function storeSell(Request $request)
    {
        $imagePath = null;
        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'image'       => $imagePath,
            'category'    => $request->category,
            'condition'   => $request->condition,
            'name'        => $request->name,
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        return redirect()->route('myPage');
    }
}
