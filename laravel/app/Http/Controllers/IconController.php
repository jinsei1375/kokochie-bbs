<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Icon;
use Illuminate\Http\Request;
use App\Http\Requests\IconRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IconRequest $request)
    {

        
        $id = Auth::id();
        $icon = new Icon();
        $icon->user_id = $id;
        //拡張子付きでファイル名を取得
        $filenameWithExt = $request->file("icon")->getClientOriginalName();
        //ファイル名のみを取得
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //拡張子を取得
        $extension = $request->file("icon")->getClientOriginalExtension();
        //保存のファイル名を構築
        $filenameToStore = $filename."_".time().".".$extension;

        $icon->file_name = $filenameToStore;
        

        $icon->save();

        $request->file("icon")->storeAs('public/img/icon', $filenameToStore);

        return redirect()->to('/user/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::id();
        return view('icon.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IconRequest $request, $id)
    {

        $icon = Icon::find($id);

        $filenameWithExt = $request->file("icon")->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file("icon")->getClientOriginalExtension();
        $filenameToStore = $filename."_".time().".".$extension;

        $icon->file_name = $filenameToStore;

        $icon->save();
        $request->file("icon")->storeAs('public/img/icon', $filenameToStore);
        
        return redirect()->route('icon.edit', Auth::user()->icon->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
