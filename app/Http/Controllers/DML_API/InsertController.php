<?php

namespace App\Http\Controllers\DML_API;

use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Converter;
use App\Helpers\Generator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\History;

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
            $dbs = explode("_", $db);

            for($i = 0; $i < $len; $i++){
                $jsonPK = Converter::getEncoded($request->primary_key_format);
                $jsonCol = Converter::getEncoded($request->column_format);
                $detail = json_decode($jsonPK, true);
                $col = json_decode($jsonCol, true);

                // PK Section
                if($detail[0]['is_increment']){
                    $i++;
                    $result .= "<p>INSERT INTO ".$request->table_name_format." VALUES (".$i.", ";
                    $i--;
                } else {
                    if($detail[0]['factory'] == "uuid"){
                        // seperate 2 uuid by length
                        $id = "'".Generator::getUUID()."'";
                    }
                    $result .= "<p>INSERT INTO ".$request->table_name_format." VALUES (".$id.", ";
                }

                // FK Section

                // Non-Keys Section
                $nonKeys = "";
                $nk_i = 0;
                foreach($col as $cl){
                    //String
                    if($cl['factory'] == "fname" || $cl['factory'] == "date" || $cl['factory'] == "prgh" || $cl['factory'] == "uname" || $cl['factory'] == "mail" || $cl['factory'] == "pass"){
                        if($cl['factory'] == "fname"){
                            $val = fake()->name();
                        } else if($cl['factory'] == "date"){
                            //$val = fake()->dateTimeBetween('now', '+2 days');
                        } else if($cl['factory'] == "prgh"){
                            $val = fake()->paragraph();
                        } else if($cl['factory'] == "uname"){
                            $val = fake()->username();
                        } else if($cl['factory'] == "mail"){
                            $val = fake()->unique()->safeEmail();
                        } else if($cl['factory'] == "pass"){
                            $val = fake()->password();
                        }
                        $nonKeys .= "'".$val."'";
                    }

                    if($nk_i <= count($col) - 2){
                        $result .= $nonKeys.",";
                    } else {
                        $result .= $nonKeys.");</p> ";
                    }
                    $nonKeys = "";
                    
                    $nk_i++;
                }
            }

            if($request->save_format == "true"){
                if($request->save_dummy == "true"){
                    $syntax = $result;
                } else {
                    $syntax = null;
                }
                History::create([
                    'id' => Generator::getUUID(), 
                    'user_id' => 1, // For now 
                    'is_public' => 1, // For now, 
                    'generate_type' => strtoupper($type), 
                    'database_id' => $dbs[0], 
                    'method_id' => $method, 
                    'return_type' => "html", // for now 
                    'syntax' => $syntax, 
                    'total_rows' => $len, 
                    'total_column' => count($col), 
                    'table_name' => $request->table_name_format, 
                    'created_at' => date("Y-m-d H:i"), 
                    'updated_at' => null, 
                    'deleted_at' => null
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Dummy created',
                'properties' => [
                    'column_type' => strtoupper($type),
                    'database' => ucfirst($dbs[1]),
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
