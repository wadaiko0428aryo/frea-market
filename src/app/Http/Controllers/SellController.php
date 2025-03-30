<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Http\Requests\SellRequest;
use Illuminate\Support\Facades\Auth;

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

    // 出品機能
    public function storeSell(SellRequest $request)
    {
        $imagePath = null;
        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id'     => Auth::id(),
            'image'       => $imagePath,
            'category'    => $request->category,
            'condition'   => $request->condition,
            'name'        => $request->name,
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        if(is_array($request->category)) {
            foreach ($request->category as $categoryName) {
                $category = Category::where('category', $categoryName)->first();
                if($category) {
                    $item->categories()->attach($category->id);
                }
            }
        }

        return redirect()->route('myPage')->with('message', '商品を出品しました');
    }
}
