<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Upload;
use Image;
use App\Library\UploadStoringPath as StoringPaths;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uploads = DB::table('uploads')
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
        return response()->json([
            'images' => $uploads
        ]);
        //
    }

    /**
     * Search through tags of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // dd('test');
        $search = $request->input('search');
        $uploads = Upload::withAnyTag($search)
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
        return response()->json([
            'images' => $uploads
        ]);
        //
    }

    /**
     * Return all tags associated with Uploads
     *
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        // dd('test');
        $tags = Upload::existingTags();
        return response()->json([
            'tags' => $tags
        ]);
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
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,bmp,png,pdf,gif',
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $paths = new StoringPaths();
            $upload = new Upload();
            $upload->type =  $file->getClientMimeType() == 'application/pdf' ? 'pdf':'img';
            $upload->name = uniqid().'.'.$file->getClientOriginalExtension();

            switch ($upload->type) {
                case 'pdf':
                    $file->move($paths->store_path,$upload->name);
                    $upload->src = $paths->url.$upload->name;
                    $upload->thumb_src = 'PDF'; // icon will be printed on the front end
                    break;
                case 'img':

                    $img_thumb_name = "thumb_$upload->name";

                    $img = Image::make($file->getRealPath());
                    if($img->mime() == 'image/gif') {
                        // if the image is a gif
                        // store original file to preserve animation
                        $file->move($paths->store_path,$upload->name);
                    }else{
                        $img->save($paths->store_path.$upload->name);
                    }
                    $img->fit(200, 200);
                    $img->save($paths->store_path.$img_thumb_name);

                    $upload->thumb_src = $paths->url.$img_thumb_name;
                    $upload->src = $paths->url.$upload->name;
                    break;
            }

            $upload->save();
            return $upload;
        }
        else {
            return "oops";
        }
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
        $image = Upload::with('tagged')->findOrFail($id);
        $tags = $image->tagNames();
        $image['image_tags'] = $tags;
        unset($image['tagged']);

        return response()->json([
            'image' => $image
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $upload = Upload::find($id);

        $name = $request->input('names');
        $tags = $request->input('tags');

        if ($name) {
            $upload->name = $name;
        }
        // dd($request->exists('tags'));

        if ($request->exists('tags') && $tags) {
            $tagArray = explode(',', $tags);
            $upload->retag($tagArray);
        } else if ($request->exists('tags') && !$tags) {
            $upload->untag();
        }

        $upload->save();

        $tags = $upload->tagNames();
        $upload['image_tags'] = $tags;
        unset($upload['tagged']);

        return response()->json([
            'image' => $upload
        ]);
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
