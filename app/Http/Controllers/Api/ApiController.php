<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Core\Tasks;
use App\Models\Core\Task;

class ApiController extends Controller
{

    public function index() {
        return $this->json("Barefilter RESTful API");
    }
    
    protected function json($data) {
        $response = array("success" => false);
        if(!isset($data["error"])){
            $response["success"] = true;
            $response["data"] = $data;
        }else{
            $response["error"] = $data["error"];
        }
        return response()->json($response);
    }

    public function tasks(){
        return $this->json(
            Tasks::execute()
        );
    }

    public function contact(Request $request){
        return $this->json(
            Tasks::add(Task::$contact, $request->all())
        );
    }

    public function bookTechnicalService(Request $request){
        return $this->json(
            Tasks::add(Task::$bookTechnicalService, $request->all())
        );
    }
}
