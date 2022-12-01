<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\ErrorWord;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

//select english, russian from error_words, words, dictionaries where dictionaries.user_id = 2 and dictionaries.id = words.dictionary_id and words.id = error_words.word_id;
class ErrorWordController extends Controller
{
    public function getErrorsWords(Request $request){
        try{
            $userId = $request->user()->id;
            return DB::table('error_words')->join('words', function($join) {
                $join->on('error_words.word_id', '=', 'words.id');
            })->join('dictionaries', function ($join) {
                $join->on('words.dictionary_id', '=', 'dictionaries.id');
            })->where('user_id', $userId)->select(["error_words.id", "error_words.word_id" , "words.english", 'words.russian', 'words.transcription'])->get();

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function addErrorWord(Request $request){
        try{

        $validateUser = Validator::make($request->all(),
            [
                'word_id' => ["required", Rule::unique('error_words', 'word_id')],
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $userId = $request->user()->id;
            $word = Word::find($request -> word_id);
            $dictionary = Dictionary::where('id', $word->dictionary_id ?? 0)->where('user_id', $userId)->exists();

            if(!$dictionary){
                return response()->json([
                    'status' => false,
                    'message' => 'current word not found',
                ], 404);
            }

            ErrorWord::create([
                "word_id" => $request -> word_id,
            ]);

            return response()->json([
                'status'=>true,
                'message'=> "error word is added",
                'word'=>$word,
            ], 200);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteErrorWord(Request $request, $id){
        try{
            if(!ErrorWord::where('id', $id)->exists()){
                return response()->json([
                    'status' => false,
                    'message' => 'current word not found',
                ], 404);
            }

            $userId = $request->user()->id;
            $error_word = ErrorWord::find($id);
            $word = Word::find($error_word -> word_id);
            $dictionary = Dictionary::where('id', $word->dictionary_id ?? 0)->where('user_id', $userId)->exists();
            if(!$dictionary){
                return response()->json([
                    'status' => false,
                    'message' => 'current word not found',
                ], 404);
            }
            if($error_word->delete()){
                return response()->json([
                    'status' => true,
                    'message' => 'error word is deleted',
                ], 200);
            }

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            "status" => false,
            "message" => "Error"
        ], 500);
    }
}
