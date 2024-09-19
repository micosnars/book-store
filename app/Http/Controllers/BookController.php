<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\HttpException;
use App\Http\Controllers\Controller;
use App\Models\Book;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $book = new Book();
            $book->fill($request->validated())->save();
            $response = [
                'message' => 'Data berhasil disimpan',
                'data' => $book
            ];
            return $book;
        }
        catch(\Exception $exception)
        {
            throw new HttpException(500, "invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * remove the specified resource from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        
        return response()->json(null, 204);
    }   
}
