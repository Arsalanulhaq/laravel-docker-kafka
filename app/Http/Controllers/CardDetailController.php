<?php

namespace App\Http\Controllers;

use App\Models\CardDetail;
use Illuminate\Http\Request;

class CardDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $card_details = CardDetail::all();

        return response()->json([
            'status' => true,
            'card_details' => $card_details
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('card_details.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card_detail = CardDetail::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "CardDetail Created successfully!",
            'card_detail' => $card_detail
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CardDetail  $card_detail
     * @return \Illuminate\Http\Response
     */
//    public function show(CardDetail $card_detail)
//    {
//        return view('card_details.show',compact('card_detail'));
//    }
    public function show($id)
    {
        $card_detail = CardDetail::find($id);
        if(!empty($card_detail))
        {
            return response()->json($card_detail);
        }
        else
        {
            return response()->json([
                "message" => "CardDetail not found"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CardDetail  $card_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(CardDetail $card_detail)
    {
        return view('card_details.edit',compact('card_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CardDetail  $card_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CardDetail $card_detail)
    {
//        dd($card_detail);
        $card_detail->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "CardDetail Updated successfully!",
            'card_detail' => $card_detail
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CardDetail  $card_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CardDetail $card_detail)
    {
        $card_detail->delete();
        return response()->json([
            'status' => true,
            'message' => "CardDetail Deleted successfully!",
        ], 200);
    }
}
