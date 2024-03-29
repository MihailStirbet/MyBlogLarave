<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all(); 
        return view('article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validarea datelor de intrare
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'content' => 'required|string',
            // Puteți adăuga și alte reguli de validare pentru user_id, category_id, etc., în funcție de necesitățile dvs.
        ]);

        // Salvarea articolului în baza de date
        $article = new Article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->content = $request->content;
        $article->user_id = auth()->user()->id;
        $article->category_id = 1; // Presupunând că articolul este asociat cu utilizatorul autentificat
        // Dacă aveți un câmp category_id, trebuie să îl setați aici în funcție de cerințele dvs.
        $article->image = $request->image;

        $article->published_at = now(); // Puteți ajusta cum doriți

        $article->save();

        return redirect()->route('article')->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('article.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        // Validarea datelor de intrare

        // Găsirea articolului în baza de date după ID
        $article = Article::findOrFail($id);

        // Actualizarea datelor articolului
        $article->title = $request->title;
        $article->description = $request->description;
        $article->content = $request->content;
        $article->image = $request->image;
        // Salvarea modificărilor în baza de date
        $article->save();

        // Redirecționare înapoi către pagina de index a articolelor cu un mesaj de succes
        return redirect()->route('article')->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Găsește articolul în baza de date după ID
        $article = Article::findOrFail($id);

        $article->delete();

        return redirect()->route('article')->with('success', 'Article deleted successfully.');
    }
}
