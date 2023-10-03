<?php
// CRUD operations for worker entity

/**
* @OA\Get(path="/worker", tags={"worker"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all workers from the API. ",
 *         @OA\Response( response=200, description="List of workers.")
 * )
 */
Flight::route('GET /worker', function(){
  Flight::json(Flight::workerService()->get_workers());
});

/**
* @OA\Get(path="/worker/{id}", tags={"worker"}, security={{"ApiKeyAuth": {}}},
*     summary="Return individual worker from the API. ",
*     @OA\Parameter(in="path", name="id", example=37, description="Id of worker"),
*     @OA\Response(response="200", description="Fetch individual worker")
* )
*/
Flight::route('GET /worker/@id', function($id){
  Flight::json(Flight::workerService()->get_by_id($id));
});

/**
 * @OA\Post(path="/worker",tags={"worker"},security={{"ApiKeyAuth": {}}},
 * @OA\RequestBody(description="Worker info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="worker_job_id", type="integer", example="1", description="ID of the  workers job"),
 *        @OA\Property(property="worker_name", type="string", example="Worker", description="Worker's name"),
 *        @OA\Property(property="worker_city", type="string", example="Mostar", description="City of residence"),
 *        @OA\Property(property="worker_phone_number", type="string", example="061222333", description="Worker's phone number"),
 *        @OA\Property(property="worker_address", type="string", example="Some description", description="Worker's address"),
 *        @OA\Property(property="worker_email", type="string", example="worker@email.com", description="Worker's email address"),
 *        @OA\Property(property="description", type="string", example="short description", description="Worker's description")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Success message"),
 * @OA\Response(response="400", description="Not unique")
 * )
 */
Flight::route('POST /worker', function(){
  try{
    Flight::json(Flight::workerService()->post_worker(Flight::get("user"),Flight::request()->data->getData()));
  }catch (\Exception $e) {
    Flight::json(["message" => "Doctor with same information already exists"], 400);

  }
});

/**
 * @OA\Put(path="/worker/{id}",tags={"worker"},security={{"ApiKeyAuth": {}}},
 * @OA\Parameter(in="path", name="id", example=41, description="Id of worker"),
 * @OA\RequestBody(description="Worker info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="worker_job_id", type="integer", example="9", description="ID of the  workers job"),
 *        @OA\Property(property="worker_name", type="string", example="Worker", description="Worker's name"),
 *        @OA\Property(property="worker_city", type="string", example="Mostar", description="City of residence"),
 *        @OA\Property(property="worker_phone_number", type="string", example="061222333", description="Worker's phone number"),
 *        @OA\Property(property="worker_address", type="string", example="Some description", description="Worker's address"),
 *        @OA\Property(property="worker_email", type="string", example="worker@email.com", description="Worker's email address"),
 *        @OA\Property(property="description", type="string", example="Short description", description="Worker's edescription")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Updated worker"),
 * @OA\Response(response="400", description="Not unique"),
 * @OA\Response(response="403", description="Forbidden")
 * )
 */
Flight::route('PUT /worker/@id', function($id){
  try{
    $data = Flight::request()->data->getData();
    $response = Flight::workerService()->update_worker($id, $data, Flight::get("user"));
    if($response){
      Flight::json($response);
    }else{
      Flight::json(["message" => "Can't update this doctor"], 403);
    }
  }catch (\Exception $e) {
    Flight::json(["message" => "Doctor with same information already exists"], 400);
  }
});

/**
* @OA\Delete(
*     path="/worker/{id}", security={{"ApiKeyAuth": {}}},
*     description="Delete worker",
*     tags={"worker"},
*     @OA\Parameter(in="path", name="id", example=38, description="Worker's ID"),
*     @OA\Response(
*         response=200,
*         description="Worker deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('DELETE /worker/@id', function($id){
  Flight::workerService()->delete_worker(Flight::get("user"),$id);
  Flight::json(["message" => "deleted"]);
});
/**
* @OA\Get(path="/worker/{id}/review", tags={"worker"}, security={{"ApiKeyAuth": {}}},
*     @OA\Parameter(in="path", name="id", example=37, description="Id of worker"),
*     @OA\Response(response="200", description="Individual reviews for worker")
* )
*/
Flight::route('GET /worker/@id/review', function($id){
  Flight::json(Flight::reviewService()->get_review_by_worker_id($id));
});

?>
