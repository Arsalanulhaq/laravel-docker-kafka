<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all();

        return response()->json([
            'status' => true,
            'members' => $members
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member = Member::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Member Created successfully!",
            'member' => $member
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
//    public function show(Member $member)
//    {
//        return view('members.show',compact('member'));
//    }
    public function show($id)
    {
        $member = Member::find($id);
        if(!empty($member))
        {
            return response()->json($member);
        }
        else
        {
            return response()->json([
                "message" => "Member not found"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        return view('members.edit',compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
//        dd($member);
        $member->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "Member Updated successfully!",
            'member' => $member
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json([
            'status' => true,
            'message' => "Member Deleted successfully!",
        ], 200);
    }
}
