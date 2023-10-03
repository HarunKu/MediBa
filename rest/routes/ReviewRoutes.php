<?php
// CRUD operations for review entity

/**
* @OA\Get(path="/review", tags={"review"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all reviews from the API. ",
 *         @OA\Response( response=200, description="List of reviews.")
 * )
 */
Flight::route('GET /review', function(){
  Flight::json(Flight::reviewService()->get_all());
});

/**
* @OA\Get(path="/review/{id}", tags={"review"}, security={{"ApiKeyAuth": {}}},
*     summary="Return individual review from the API. ",
*     @OA\Parameter(in="path", name="id", example=7, description="Id of review"),
*     @OA\Response(response="200", description="Fetch individual review")
* )
*/
Flight::route('GET /review/@id', function($id){
  Flight::json(Flight::reviewService()->get_by_id($id));
});

/**
 * @OA\Post(path="/review",tags={"review"},security={{"ApiKeyAuth": {}}},
 * @OA\RequestBody(description="Review info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="worker_id", type="integer", example="37", description="Id of worker"),
 *        @OA\Property(property="review_grade", type="integer", example="5", description="Review grade"),
 *        @OA\Property(property="review_comment", type="string", example="Some comment", description="Review text")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Success message")
 * )
 */
Flight::route('POST /review', function(){
  $data = Flight::request()->data->getData();
  Flight::json(Flight::reviewService()->post_review(Flight::get("user"),$data));
});



/**
* @OA\Delete(
*     path="/review/{id}", security={{"ApiKeyAuth": {}}},
*     description="Delete review",
*     tags={"review"},
*     @OA\Parameter(in="path", name="id", example=8, description="Review ID"),
*     @OA\Response(
*         response=200,
*         description="Review deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('DELETE /review/@id', function($id){
  Flight::reviewService()->delete_review(Flight::get("user"),$id);
  Flight::json(["message" => "deleted"]);
});

?>
