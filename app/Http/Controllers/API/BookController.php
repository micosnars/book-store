<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use App\Models\Book;
use OpenApi\Annotations as OA;

/**
 * Class Book.
 * 
 * @author Mico <micosnars@gmail.com>
 */


class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"books"},
     *     summary="Display a listing of items",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     )
     * ) 
     */

    public function index()
    {
        return Book::get();
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"books"},
     *     summary="Store a newly created item",
     *     operationId="store",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     * @OA\RequestBody(
     *         description="Book object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Book",
     *             example={
     *                 "title": "Eating Clean",
     *                 "author": "Robb Wolf",
     *                 "publisher": "Kawan Pustaka",
     *                 "publication_year": "2016",
     *                 "cover": "https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1482170055i/33511107.jpg",
     *                 "description": "The book is about eating clean and healthy food",
     *                 "price": "100000"
     *             }
     *         )
     *     )
     * ) 
     */
    
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:books',
                'author' => 'required|max:100',
            ]);
            if ($validator->fails()) {
                throw new HttpException(400, "invalid data - {$validator->messages()->first()}");
            }
            $book = new Book();
            $book->fill($request->all())->save();
            return $book;
        }
        catch(\Exception $exception)
        {
            throw new HttpException(400, "invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"books"},
     *     summary="Display the specified item",
     *     operationId="show",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found" 
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item that needs to be displayed",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     * )
     */

    public function show($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }
        return $book;
    }

    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     tags={"books"},
     *     summary="Update the specified item",
     *     operationId="update",
     *     @OA\Response(
     *         response=404,
     *         description="Item not found",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of item that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Book object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Book",
     *             example={
     *                 "title": "Eating Clean",
     *                 "author": "Robb Wolf",
     *                 "publisher": "Kawan Pustaka",
     *                 "publication_year": "2016",
     *                 "cover": "https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1482170055i/33511107.jpg",
     *                 "description": "The book is about eating clean and healthy food",
     *                 "price": "100000"
     *             }
     *         )
     *     )
     * )
     */ 

     public function update(Request $request, $id)
     {
        $book = Book::findOrFail($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }
        
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:books',
                'author' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                throw new HttpException(400, "invalid data - {$validator->messages()->first()}");
            }
            $book->fill($request->all())->save();
            return response()->json(array('message' => 'Update successfully'), 200);
        }
        catch(\Exception $exception) {
            throw new HttpException(400, "invalid data - {$exception->getMessage()}");
        }
     }

     /**
      * @OA\Delete(
      *     path="/api/books/{id}",
      *     tags={"books"},
      *     summary="Delete the specified item",
      *     operationId="destroy",
      *     @OA\Response(
      *         response=404,
      *         description="Item not found",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Invalid input",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful",
      *         @OA\JsonContent()
      *     ),
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ID of item that needs to be deleted",
      *         required=true,
      *         @OA\Schema(
      *             type="integer",
      *             format="int64"
      *         )
      *     ),
      * )
      */

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            throw new HttpException(404, "Item not found");
        }

        try {   
            $book->delete();
            return response()->json(array('message' => 'Delete successfully'), 200);
        }
        catch(\Exception $exception) {
            throw new HttpException(400, "invalid data - {$exception->getMessage()}");
        }
    }   
}
