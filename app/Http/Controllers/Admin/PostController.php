<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPostNotification;

use App\Post;
use App\Category;
use App\Tag;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(9);
        
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_data = $request->all();

        $request->validate($this->getValidationRules());
        $new_post = new Post();
        $new_post->fill($form_data);
        
        $new_post->slug = Post::getUniqueSlugFromTitle($form_data['title']);

        // store immagine del post
        if(isset($form_data['image'])) {
            // 1- Mettere l'immagine caricata nella cartella di Storage
            $img_path = Storage::put('post_covers', $form_data['image']);
            
            // 2- Salvare il path al file nella colonna cover del post
            $new_post->cover = $img_path;
        }

        $new_post->save();

        // update the relation in database table
        if(isset($form_data['tags'])) {
            $new_post->tags()->sync($form_data['tags']);
        }

        Mail::to('editor@boolpress.it')->send(new NewPostNotification($new_post));

        return redirect()->route('admin.posts.show', ['post' => $new_post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
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
        $form_data = $request->all();
        $request->validate($this->getValidationRules());

        $post = Post::findOrFail($id);
        
        // Aggiorno lo slug soltanto se l'utente in fase di modifica cambia il titolo
        if($form_data['title'] != $post->title) {
            $form_data['slug'] = Post::getUniqueSlugFromTitle($form_data['title']);
        }

        // cancella la vecchia immagine, ne carica una nuova, e aggiorna la colonna del database
        if($form_data['image']) {
            if($post->cover) {
                Storage::delete($post->cover);
            }
            $img_path = Storage::put('post_covers', $form_data['image']);
            $form_data['cover'] = $img_path;
        }
        
        $post->update($form_data);

        if(isset($form_data['tags'])) {
            $post->tags()->sync($form_data['tags']);
        } else {
            // Se non esiste la chiave tags in form_data significa che l'utente ha rimosso il check da tutti i tag
            // con sync vuoto si rimuovono dal database
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        // setta i tag con l'id del post come vuoti
        $post->tags()->sync([]);
        // cancella il path cover del post
        if($post->cover) {
            Storage::delete($post->cover);
        }

        $post->delete();

        return redirect()->route('admin.posts.index');
    }

    // UTILITIES FUNCTIONS
    protected function getValidationRules() {
        return [
            'title' => 'required|max:255',
            'content' => 'required|max:60000',
            'category_id' => 'exists:categories,id|nullable',
            'tags' => 'exists:tags,id',
            'image' => 'mimes:jpg,bmp,png|max:512'
        ];
    }
}
