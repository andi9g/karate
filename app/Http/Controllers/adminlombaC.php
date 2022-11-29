<?php

namespace App\Http\Controllers;

use App\Models\tandingM;
use Illuminate\Http\Request;

class adminlombaC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        

        return view('pages.pagesAdminLomba');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function show(tandingM $tandingM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function edit(tandingM $tandingM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tandingM $tandingM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function destroy(tandingM $tandingM)
    {
        //
    }
}
