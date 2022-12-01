<?php

namespace App\Http\Controllers;

use App\Models\score;
use Exception;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected $elector;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        try {
            $data = score::all();
            return response()->json($data);
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }

    public function getVoted()
    {
        try {
            $data = score::where('vote', '!=', '');
            return response()->json($data);
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }

    public function getOnce($id)
    {
        try {
            $data = score::find($id);
            return response()->json($data);
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $electors = score::insert($request->all());
            return response()->json();
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\score  $elector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $elector = score::find($id);
            $elector->fill($request->all());
            $elector->save();

            return response()->json();
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\score  $elector
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            score::destroy($id);
            return response()->json();
        } catch (Exception $err) {
            return response()->json([
                'error' => $err
            ]);
        }
    }
}
