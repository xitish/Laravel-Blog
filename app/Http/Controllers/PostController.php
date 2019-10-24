<?php

namespace App\Http\Controllers;

use App\Post;
use App\Like;
use App\Tag;
use App\User;
use Auth;
use Gate;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at','desc')->paginate(2);
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        /* if(!Auth::check())
        {
            return redirect()->back();
        } */
        $posts = Post::orderBy('title','asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost( $id)
    {
        $post = Post::where('id','=',$id)->with('likes')->with('user')->first();              //Same as Post::find($id)  = sign in where clausecan also  be removed
        return view('blog.post', ['post' => $post]);
    }

    public function getLike( $id)
    {
        $post = Post::where('id','=',$id)->first();              //Same as Post::find($id)  = sign in where clausecan also  be removed
        $like = new Like();
        $post->likes()->save($like);
        return redirect()->back();
    }

    public function getAdminCreate()
    {
        /* if(!Auth::check())
        {
            return redirect()->back();
        } */
        $tags = Tag::all();
        return view('admin.create',['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
        /* if(!Auth::check())
        {
            return redirect()->back();
        } */
        $post = Post::find($id);
        $tags = Tag::all();
        return view('admin.edit', ['post' => $post, 'postId' => $id,'tags' => $tags]);
    }

    public function postAdminCreate(Request $request)
    {
        $this->validate($request, [ 
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $user = Auth::user();
       /*  if(!$user)
        {
            return redirect()->back();
        } */

        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        $user->posts()->save($post);
        $post->tags()->attach($request->input('tags') === null ? []:$request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post Created with title : '.$request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {
        /* if(!Auth::check())
        {
            return redirect()->back();
        } */
        $this->validate($request, [ 
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        $post = Post::find($request->input('id'));
        if(Gate::denies('update-post', $post))
        {
            return redirect()->back()->with('msg','You do not have credentials to edit this post.');
        }
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
        /* 
        $post->tags()->detach();
        $post->tags()->attach($request->input('tags') === null ? []:$request->input('tags')); 
        */
        $post->tags()->sync($request->input('tags') === null ? []:$request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post Edited with title : '.$request->input('title')); 
    }

    public function getAdminDelete($id)
    {
        /* if(!Auth::check())
        {
            return redirect()->back();
        } */
        $post = Post::find($id);
        if(Gate::denies('update-post', $post))
        {
            return redirect()->back()->with('msg','You do not have credentials to delete this post.');
        }
        $post->likes()->delete();
        $post->tags()->detach();

        $post->delete();
        return redirect()->route('admin.index')->with('info', 'Post Deleted : ');
    }

    public function test(Request $request)
    {
        dd('You reached Here');
    }
   
}
