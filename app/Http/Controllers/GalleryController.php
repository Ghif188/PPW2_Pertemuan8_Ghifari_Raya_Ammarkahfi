<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

/**
* @OA\Info(
*   description="Latihan Praktikum 12",
*   version="0.0.1",
*   title="Gallery API documentation",
*   termsOfService="http://swagger.io/terms/",
*   @OA\Contact(
*       email="ghifarirayaammarkahfi@mail.ugm.ac.id"
*   ),
*   @OA\License(
*       name="Apache 2.0",
*       url="http://www.apache.org/licenses/LICENSE-2.0.html"
*   )
* )
*/

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $no = 1;
        // $data = Post::where('picture', '!=', 'noimage.png')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30);
        // return view('gallery.index', compact('data', 'no'));

        $response = Http::get('http://127.0.0.1:8000/api/gallery');
        $objectResponse = $response->body();
        $data = json_decode($objectResponse, true)['data'];
        return view('gallery.index', compact('data', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        try {
            if ($request->hasFile('picture')) {
                //$filenameWithExt = $request->file('picture')->getClientOriginalName();
                //$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('picture')->getClientOriginalExtension();
                $basename = uniqid() . time();
                $filenameSimpan = "{$basename}.{$extension}";
                $request->file('picture')->storeAs('posts_image', $filenameSimpan);
            } else {
                $filenameSimpan = 'noimage.png';
            }
            $response = Http::attach('picture', file_get_contents($request->picture), $filenameSimpan )->post('http://127.0.0.1:8000/api/gallery-store', [
                'title' => $request->title,
                'description' => $request->description,
            ]);

            if ($response->successful()) {
                return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
            }
        } catch (\Throwable $th) {
            return redirect('gallery')->with('error', $th);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Post::where('id', $id)->first();
        return view('gallery.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
            Post::where('id', $id)->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'picture' => $path,
            ]);
        } else {
            Post::where('id', $id)->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ]);
        }
        return redirect()->route('gallery.index')->withSuccess('You have successfully edit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::where('id', $id)->first();
        Storage::delete('public/post_image/' . $post->image);
        $post->delete();
        return redirect()->route('gallery.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    /**
     * @OA\Get(
     *  path="/api/gallery",
     *  tags={"Gallery"},
     *  summary="all data gallery",
     *  description="Get all data Gallery",
     *  operationId="gallery",
     *  @OA\Response(
     *      response=200,
     *      description="success",
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="error",
     *  )
     *)
     */

    public function gallery()
    {
        try {
            $data = Post::whereNotNull('picture')->get();
            return response()->json([
                'data' => $data,
                'response' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => $th
            ], 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/gallery-store",
     *     summary="store gallery",
     *     tags={"Store Gallery"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="title gallery",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="description gallery",
     *                 ),
     *                 @OA\Property(
     *                     property="picture",
     *                     type="string",
     *                     format="binary",
     *                     description="picture gallery",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="error",
     *     )
     * )
     */
    public function storeGallery(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);
        try {
            if ($request->hasFile('picture')) {
                $extension = $request->file('picture')->getClientOriginalExtension();
                $basename = uniqid() . time();
                $filenameSimpan = "{$basename}.{$extension}";
                $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
            } else {
                $filenameSimpan = 'noimage.png';
            }
            $post = new Post;
            $post->picture = $filenameSimpan;
            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->save();
            return response()->json([
                'picture' => $post->picture,
                'title' => $post->title,
                'description' => $post->description,
                'response' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => $th
            ], 500);
        }
    }
}
