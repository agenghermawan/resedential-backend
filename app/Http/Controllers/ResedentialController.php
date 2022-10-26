<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResedentialRequest;
use App\Http\Resources\ResedentialResource;
use App\Models\Resedential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResedentialController extends Controller
{
    /**
     *  Displaying all Resedential Data.
     */
    public function index()
    {
        try {
            $FetchAllResedentials = ResedentialResource::collection(Resedential::all());
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Residentials Data Retrieved Successfully',
                'data' => $FetchAllResedentials
            ], 200);
        } catch (\Throwable $th) {
             return response()->json([
                'code' => 500,
                'status' => 'Failed',
                'message' => 'Internal Server Error',
            ], 500);
        }

    }

    /**
     * Store or Add Resedential Data.
     * Dont forget to Storage Link :)
     */
    public function store(ResedentialRequest $request)
    {
        try{
            $getNameImage = $request->file('image')->getClientOriginalName();
            $ResedentialStore = new Resedential();
            $ResedentialStore->name = $request->name;
            $ResedentialStore->unit_number = $request->unit_number;
            $ResedentialStore->type = $request->type;
            $ResedentialStore->description = $request->description;
            $ResedentialStore->image = $request->file('image')->storeAs('assets/resedential-image',$getNameImage,'public');
            $ResedentialStore->save();
            return response()->json([
                'code' => 201,
                'status' => 'Created',
                'message' => 'Resedential Data Added Successfully',
                'data' => new ResedentialResource($ResedentialStore)
            ], 201);
        }   catch(\Throwable $th){
            return response()->json([
                'code' => 500,
                'status' => 'Failed',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    /**
     * Displaying one of the Resedential Data.
     *  @param  int  $id
     */
    public function show($id)
    {
        try{
            if(Resedential::where('id', $id)->exists()){
                $FetchOneResedential = new ResedentialResource(Resedential::find($id));
                return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Residential Data Retrieved Successfully',
                    'data' => $FetchOneResedential,
                ],200);
            }   else{
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Resedential not found',
                ],404);
            }
        }catch(\Throwable $th){
            return response()->json([
                'code' => 500,
                'status' => 'Failed',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    /**
     * Update the specified Residential Data.
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        try{
            $validatorUpdate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:45'],
            'unit_number' => ['required', 'integer'],
            'type' => ['required','string', 'max:20'],
            'description' => ['required', 'string', 'max:200'],
            'image' => ['image', 'max:2048' ,'mimes:jpg,jpeg,png,svg'],
            ]);

            if ($validatorUpdate->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'The given data was invalid.',
                    'errors' => $validatorUpdate->errors(),
                ], 422);
            }

            if(Resedential::where('id', $id)->exists()){
                $ResedentialUpdate = Resedential::find($id);
                $ResedentialUpdate->name = $request->name;
                $ResedentialUpdate->unit_number = $request->unit_number;
                $ResedentialUpdate->type = $request->type;
                $ResedentialUpdate->description = $request->description;

                // Delete Old Image
                if(!is_null($request->file('image'))){
                    $tes = Storage::disk('public')->delete($ResedentialUpdate->image);
                    $getNameImage = $request->file('image')->getClientOriginalName();
                    $ResedentialUpdate->image = $request->file('image')->storeAs('assets/resedential-image',$getNameImage,'public');
                }

                $ResedentialUpdate->save();
                return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Resedential Data Update Successfully',
                    'data' => new ResedentialResource($ResedentialUpdate),
                ],200);
            }else{
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Resedential not found',
                ],404);
            }
        }catch(\Throwable $th){
            return response()->json([
                'code' => 500,
                'status' => 'Failed',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    /**
     * Remove the specified Resedential Data.
     * @param  int  $id
     */
    public function destroy($id)
    {
        try{
            if(Resedential::where('id', $id)->exists()){
                $FindResedentialById = Resedential::find($id);
                $FindResedentialById->delete();
                return response()->json([
                    'code'=> 200,
                    'status' => 'success',
                    'message' => 'Residential Data Deleted Successfully',
                ],200);
            }else{
                return response()->json([
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Resedential not found',
                ],404);
            }
        }catch(\Throwable $th){
            return response()->json([
                'code' => 500,
                'status' => 'Failed',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }
}
