<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insurances = Insurance::all();

        return response()->json([
            'status' => true,
            'insurances' => $insurances
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('insurances.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $insurance = Insurance::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Insurance Created successfully!",
            'insurance' => $insurance
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
//    public function show(Insurance $insurance)
//    {
//        return view('insurances.show',compact('insurance'));
//    }
    public function show($id)
    {
        $insurance = Insurance::find($id);
        if(!empty($insurance))
        {
            return response()->json($insurance);
        }
        else
        {
            return response()->json([
                "message" => "Insurance not found"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function edit(Insurance $insurance)
    {
        return view('insurances.edit',compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Insurance $insurance)
    {
        $insurance->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Insurance Updated successfully!",
            'insurance' => $insurance
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();
        return response()->json([
            'status' => true,
            'message' => "Insurance Deleted successfully!",
        ], 200);
    }
}
