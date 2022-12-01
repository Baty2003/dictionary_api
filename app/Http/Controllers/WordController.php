<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WordController extends Controller
{
    public function getWordForDictionary(Request $request, $id){
        try{
            $userId = $request->user()->id;
            if(!Dictionary::where('id', $id)->where('user_id', $userId)->exists()){
               return response()->json([
                   'status'=> false,
                   'message' => 'Dictionary not exists'
               ]);
            }

            return Word::where('dictionary_id', $id)->get();

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createWord(Request $request) {
        try{
            $userId = $request->user()->id;
            $validateUser = Validator::make($request->all(),
                [
                    'english' => "required",
                    'russian' => "required",
                    'dictionary_id' => ["required", Rule::exists('dictionaries', 'id')->where('user_id', $userId)]
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $word = Word::create([
                'english' => $request -> english,
                'russian' => $request -> russian,
                'transcription' => $request -> transcription ?? '',
                'dictionary_id' => $request -> dictionary_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Слово успешно добавлено',
                'word' => $word,
            ],201);

        } catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function editWord(Request $request) {
        try{
            $userId = $request->user()->id;
            $validateUser = Validator::make($request->all(),
                [
                    'id' => ["required", Rule::exists('words', 'id')],
                    'english' => "required",
                    'russian' => "required",
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $word = Word::find($request -> id);
            $dictionary = Dictionary::where('id', $word->dictionary_id ?? 0)->where('user_id', $userId)->exists();

            if(!$dictionary){
                return response()->json([
                    'status' => false,
                    'message' => 'current word not found',
                ], 404);
            }
            $word = Word::find($request->id);
            $word->english = $request->english;
            $word->russian = $request->russian;
            $word->transcription = $request->transcription ?? 'null';
            $result = $word->save();
            if($result){
                return response()->json([
                    'status' => true,
                    'message' => 'word is changed',
                    'word' => $word,
                ],201);
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
    public function deleteWord(Request $request, $id) {
        try{
            $word = Word::find($id);
            $userId = $request->user()->id;
            $dictionary = Dictionary::where('id', $word->dictionary_id ?? 0)->where('user_id', $userId)->exists();
            if(!$dictionary){
                return response()->json([
                    'status' => false,
                    'message' => 'current word not found',
                ], 404);
            }
            if($word->delete()){
                return response()->json([
                    'status' => true,
                    'message' => 'word is deleted',
                    'word' => $word,
                ],200);
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
