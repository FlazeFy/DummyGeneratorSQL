<?php

namespace App\Http\Controllers\DML_API;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Converter;
use App\Helpers\Generator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InsertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function insertQuery(Request $request, $type, $db, $method, $len)
    {
        try{
            $result = "";
            for($i = 0; $i < $len; $i++){
                $jsonPK = Converter::getEncoded($request->primary_key);
                $jsonCol = Converter::getEncoded($request->column);
                $detail = json_decode($jsonPK, true);
                $col = json_decode($jsonCol, true);

                // PK Section
                if($detail[0]['is_increment']){
                    $i++;
                    $result .= "INSERT INTO ".$request->table_name." VALUES (".$i.", ";
                    $i--;
                } else {
                    if($detail[0]['type'] == "uuid"){
                        // seperate 2 uuid by length
                        $id = "'".Generator::getUUID()."'";
                    }
                    $result .= "INSERT INTO ".$request->table_name." VALUES (".$id.", ";
                }

                // FK Section

                // Non-Keys Section
                $nonKeys = "";
                $nk_i = 0;
                foreach($col as $cl){
                    //String
                    if($cl['type'] == "name" || $cl['type'] == "date" || $cl['type'] == "paragraph" || $cl['type'] == "username" || $cl['type'] == "email" || $cl['type'] == "password"){
                        if($cl['type'] == "name"){
                            $val = fake()->name();
                        } else if($cl['type'] == "date"){
                            //$val = fake()->dateTimeBetween('now', '+2 days');
                        } else if($cl['type'] == "paragraph"){
                            $val = fake()->paragraph();
                        } else if($cl['type'] == "username"){
                            $val = fake()->username();
                        } else if($cl['type'] == "email"){
                            $val = fake()->unique()->safeEmail();
                        } else if($cl['type'] == "password"){
                            $val = fake()->password();
                        }
                        $nonKeys .= "'".$val."'";
                    }

                    if($nk_i <= count($col) - 2){
                        $result .= $nonKeys.",";
                    } else {
                        $result .= $nonKeys."); ";
                    }
                    $nonKeys = "";
                    
                    $nk_i++;
                }

            }

            return response()->json([
                'status' => 'success',
                'message' => 'Dummy created',
                'properties' => [
                    'type' => strtoupper($type),
                    'database' => ucfirst($db),
                    'method' => ucfirst($method)
                ],
                'data' => $result
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
