<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {

        return view('welcome');
    }

    public function store(Request $request)
    {


        // $imageData = explode("/", $request->file);



        $tmp_file = TempFile::where('folder', $request->file)->first();

        if ($tmp_file) {

            Storage::copy('post/tmp/' . $tmp_file->folder . '/' . $tmp_file->file, 'post/' . $tmp_file->folder . '/' . $tmp_file->file);

            Post::create([
                'name' => $request->name,
                'file' => $tmp_file->folder . '/' . $tmp_file->file
            ]);

            Storage::deleteDirectory('post/tmp/' . $tmp_file->folder);
            $tmp_file->delete();
            return redirect('/')->with('success', 'OK');
        }

        return redirect('/')->with('danger', 'Faça uploada da imagem');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $file_name = $image->getClientOriginalName();
            $folder = uniqid();
            $image->storeAs('post/tmp/' . $folder, $file_name);
            TempFile::create([
                'folder' => $folder,
                'file' => $file_name
            ]);
            // return $folder . '/' . $file_name;
            return $folder;
        }
    }

    public function delete()
    {
        $tmp_file = TempFile::where('folder', request()->getContent())->first();
        if($tmp_file){
            Storage::deleteDirectory('post/tmp/' . $tmp_file->folder);
            $tmp_file->delete();
            return response('');
        }
    }
}
