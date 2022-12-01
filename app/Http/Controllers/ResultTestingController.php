<?php

namespace App\Http\Controllers;

use App\Models\ResultTesting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ResultTestingController extends Controller
{
    public function getResultsTesting(Request $request){
        try{
            $user_id = $request -> user() -> id;
            return ResultTesting::where('user_id', $user_id)->get();
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createResultTesting(Request $request){
        try{
            $validateUser = Validator::make($request->all(),
                [
                    'name_dictionary' => 'required',
                    'count_words' => 'required|numeric',
                    'count_true' => 'required|numeric',
                    'count_false' => 'required|numeric',
                    'time_testing_seconds' => 'required|numeric',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $result = ResultTesting::create([
                "name_dictionary" => $request->name_dictionary,
                "count_words" => $request->count_words,
                "count_true" => $request->count_true,
                "count_false" => $request->count_false,
                "time_testing_seconds" => $request->time_testing_seconds,
                "user_id" => $request->user()->id,
            ]);

            if($result){
                return response()->json([
                    'status'=>true,
                    'message'=> "result testing was created",
                    'word'=>$result,
                ], 200);

            }

            return response()->json([
                'status' => false,
                'message' => 'error',
            ], 500);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
