<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dictionary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DictionaryController extends Controller
{
    public function getDictionaries(Request $request){
        $user_id = $request->user()->id;
        return Dictionary::where('user_id', '=', $user_id)->get();
    }

    public function createDictionary(Request $request){
        try{
            $user_id = $request->user()->id;
            $validateDictionary = Validator::make($request->all(),
                [
                    'name' => ['required', Rule::unique('dictionaries', 'name')->where('user_id', $user_id)]
                ]);

            if($validateDictionary->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateDictionary->errors()
                ], 401);
            }

            $dictionary = Dictionary::create([
                'name' => $request -> name,
                'user_id' => $user_id,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Словарь успешно создан',
                'dictionary' => $dictionary,
            ]);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function editDictionary(Request $request) {
        try{
            $user_id = $request->user()->id;
            $validateDictionary = Validator::make($request->all(),
                [
                    'id' => ['required', Rule::exists('dictionaries', 'id')->where('user_id', $user_id)],
                    'name' => ['required', Rule::unique('dictionaries', 'name')->where('user_id', $user_id)]
                ]);

            if($validateDictionary->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateDictionary->errors()
                ], 401);
            }
            $dictionary = Dictionary::find($request -> id);
            $dictionary->name = $request->name;

            if($dictionary->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'dictionary is changed',
                ], 200);
            }
        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }
    public function deleteDictionary(Request $request, $id){
        try{
            $user_id = $request->user()->id;
            if(!count(Dictionary::where('id', $id)->where('user_id', $user_id)->get())){
                return response()->json([
                    'status' => false,
                    'message' => 'dictionary not found',
                ], 404);
            }
            $dictionary = Dictionary::find($id);
            if($dictionary->delete()){
                return response()->json([
                    'status' => true,
                    'message' => 'dictionary is deleted',
                    'dictionary' => $dictionary
                ], 200);
            }


        }catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
