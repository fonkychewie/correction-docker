<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;
use MVC\HttpException;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        return $this->view('index.html', [
            'posts' => Post::latest()->get(),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (! $post) {
            throw new HttpException();
        }

        return $this->view('post.html', [
            'post' => $post,
        ]);
    }

    public function comment(string $slug): Response
    {
        $post = Post::where('slug', $slug)->first();

        if (! $post) {
            throw new HttpException();
        }

        $validated = $this->request->validate($this->request->request->all(), [
            'comment' => ['required', ['lengthMin', 10]],
        ], $this->session);

        if (! $validated) {
            $this->response->setStatusCode(Response::HTTP_FOUND);
            $this->response->headers->set('Location', '/articles/' . $slug);
            return $this->response;
        }

        // On enregistre le commentaire
        $comment = new Comment();
        $comment->content = $validated['comment'];
        $comment->post_id = $post->id;
        $comment->user_id = $this->session->get('user_id');
        $comment->save();

        // On créé une variable de session flash pour afficher un message de statut
        $this->session->getFlashBag()->set('status', 'Commentaire publié');

        $this->response->setStatusCode(Response::HTTP_FOUND);
        $this->response->headers->set('Location', '/articles/' . $slug);
        return $this->response;
    }
}
