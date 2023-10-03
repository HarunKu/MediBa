<?php

// CRUD operations for job entity

/**
* @OA\Get(path="/job", tags={"job"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all jobs from the API. ",
 *         @OA\Response( response=200, description="List of jobs.")
 * )
 */
Flight::route('GET /job', function(){
  Flight::json(Flight::jobService()->get_all());
});

/**
* @OA\Get(path="/job/{id}", tags={"job"}, security={{"ApiKeyAuth": {}}},
*     summary="Return individual job from the API. ",
*     @OA\Parameter(in="path", name="id", example=1, description="Id of job"),
*     @OA\Response(response="200", description="Fetch individual job")
* )
*/
Flight::route('GET /job/@id', function($id){
  Flight::json(Flight::jobService()->get_by_id($id));
});

/**
* @OA\Get(path="/job/{id}/worker", tags={"job"}, security={{"ApiKeyAuth": {}}},
*     @OA\Parameter(in="path", name="id", example=1, description="Id of job"),
*     @OA\Response(response="200", description="Individual workers for job")
* )
*/
Flight::route('GET /job/@id/worker', function($id){
  Flight::json(Flight::workerService()->get_worker_by_job_id($id));
});


/**
 * @OA\Post(path="/job",tags={"job"},security={{"ApiKeyAuth": {}}},
 * @OA\RequestBody(description="Job info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="job_name", type="string", example="Job name", description="Name of the job"),
 *        @OA\Property(property="job_description", type="string", example="Some description", description="Description of the job")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Success message"),
 * @OA\Response(
 *         response=403,
 *         description="Admin access required"
 *     )
 * )
 */
Flight::route('POST /job', function(){
  $user=Flight::get("user");
  if($user["r"] != "ADMIN"){
    Flight::json(["message" => "Admin access required"], 403);
  }
  else{
    Flight::json(Flight::jobService()->add(Flight::request()->data->getData()));
  }
});

/**
 * @OA\Put(path="/job/{id}",tags={"job"},security={{"ApiKeyAuth": {}}},
 * @OA\Parameter(in="path", name="id", example=1, description="Id of job"),
 * @OA\RequestBody(description="Job info", required=true,
 *    @OA\MediaType(
 *      mediaType="application/json",
 *      @OA\Schema(
 *        @OA\Property(property="job_name", type="string", example="Some job name", description="Name of the job"),
 *        @OA\Property(property="job_description", type="string", example="Some description", description="Job description")
 *      )
 *    )
 *   ),
 * @OA\Response(response="200", description="Updated job"),
 * @OA\Response(
 *         response=403,
 *         description="Admin access required"
 *     )
 * )
 */
Flight::route('PUT /job/@id', function($id){
  $user=Flight::get("user");
  if($user["r"] != "ADMIN"){
    Flight::json(["message" => "Admin access required"], 403);
  }
  else{
    $data = Flight::request()->data->getData();
    Flight::json(Flight::jobService()->update($id, $data));
  }
});

/**
* @OA\Delete(
*     path="/job/{id}", security={{"ApiKeyAuth": {}}},
*     description="Delete job",
*     tags={"job"},
*     @OA\Parameter(in="path", name="id", example=1, description="Job ID"),
*     @OA\Response(
*         response=200,
*         description="Job deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     ),
*     @OA\Response(
*         response=403,
*         description="Admin access required"
*     )
* )
*/

Flight::route('DELETE /job/@id', function($id){
  $user=Flight::get("user");
  if($user["r"] != "ADMIN"){
    Flight::json(["message" => "Admin access required"], 403);
  }
  else{
    Flight::jobService()->delete($id);
    Flight::json(["message" => "deleted"]);
  }
});

?>
