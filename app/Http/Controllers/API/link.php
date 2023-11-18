<?php

namespace App\Http\Controllers\API;

use App\Models\File;
use App\Models\random;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class link extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('welcome');
    }


    /**
     * Display a downnload view.
     */
    public function download_display(string $id)
    {
        $find = File::where('shortcode', $id)->first();
        // dd($find);
        if ($find) {
            if ($find->expiration_date > now()) {
                return view('download');
            }else {
                abort(404, "The link has expired ");
            }
           
        }else {
            abort(404, "Sorry, this page doesn't exist");
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {
        $uploadedFile = $request->file('file');
        // dd($uploadedFile);

        // Validate the uploaded file
        // $request->validate([
        //     'file' => 'required|image|mimes:jpeg,png,jpg,gif', // adjust the validation rules as needed
        // ]);

        // Move the uploaded file to the desired directory
        // $path = $request->file('file')->store('container');
        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->storeAs('uploads', Str::orderedUuid() . '.' . $uploadedFile->getClientOriginalExtension(), 'public');
       

        // Get the uploader's IP address
        $uploaderIp = $request->ip();


         // Create a new link record
         $random = new random;
         $random->unique_number = random::generateShortCode();

         $currentURL = url()->current();
        // Store file information in the database
        File::create([
            'filename' => $uploadedFile->getClientOriginalName(),
            'filepath' => $filePath,
            'uploader_ip' => $uploaderIp,
            'Extension' => $uploadedFile->getClientOriginalExtension() ?? 'Null',
            'shortcode' => $random->unique_number,
        ]);
        // You can also store the file information in the database if needed
        // Example: File::create(['name' => $uploadedFile->getClientOriginalName(), 'path' => $destinationPath]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'fileLink' => 'localhost:8000/download' .'/'.$random->unique_number 
        ]);
       } catch (\Throwable $th) {
        Log::info('Uploaded file path: ' . $filePath);

        throw $th;
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



     /**
     * Download the specified resource from storage.
     */
    public function download(Request $request)
    {
        $find = File::where('shortcode', $request->data)->first();
        // dd($find);
        if ($find && Storage::disk('public')->exists($find->filepath)) {
            if ($find->expiration_date > now()) {
                return Storage::url($find->filepath, $find->original_name);

            }else {
                abort(404, "The link has expired ");
            }
           
        }else {
            abort(404, "Sorry, this page doesn't exist");
        }
    }
}
