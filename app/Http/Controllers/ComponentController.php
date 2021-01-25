<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Component;
use App\Models\PrescriptionComponent;
use App\Models\Prescription;

class ComponentController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Components = Component::all() ;
        return response("{\"msg\": \"success\", \"error\": false, \"data\": $Components }", 200)->header("Content-Type", "application/json") ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name') ;
        $quantity = $request->input('quantity') ;
        $expiry_date = $request->input('expiry_date') ;
        $unit = $request->input('unit') ;
        $price = $request->input('price') ;

        
        if($id = Component::create([
            'name' => $name,
            'quantity' => $quantity,
            'expiry_date' => $expiry_date,
            'unit' => $unit , 
            'price' => $price

        ])){
            return response("{\"msg\": \"success\", \"error\": false, \"data\": $id}",200)->header("Content-Type", "application/json") ;
        }
        return response("{\"msg\": \"error adding component\", \"error\": true, \"data\": null}",200)->header('Content-Type', 'application/json') ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pre = Component::find($id) ; 
        if($pre)
            return response()->json([
                "msg" => "success" ,
                "error" => false , 
                "data" => $pre
            ]);
        return response("{\"msg\": \"Component not found\", \"error\": true, \"data\": null}",200)->header('Content-Type', 'application/json') ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->input('name') ;
        $quantity = $request->input('quantity') ;
        $expiry_date = $request->input('expiry_date') ;
        $unit = $request->input('unit') ;
        $price = $request->input('price') ;

        $comp = Component::find($id) ;
        if($comp) {
            $comp->name = $name ; 
            $comp->quantity = $quantity ; 
            $comp->expiry_date = $expiry_date ; 
            $comp->unit = $unit;
            $comp->price = $price ;
        
            if($comp->save())
                return response("{\"msg\" : \"success\", \"error\" : false, \"data\" : null}",200)->header("Content-Type", "application/json") ;
            return response("{\"msg\" : \"could not update the prescription\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;  
        }
        return response("{\"msg\" : \"unknown prescription\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comp = Component::find($id) ;
        $dependents = PrescriptionComponent::where('component_id', $id)->get() ;

        if($comp){
            if(count($dependents) == 0){
                if($comp->delete())
                    return response("{\"msg\" : \"success\", \"error\" : false, \"data\" : null}",200)->header("Content-Type", "application/json") ;
                return response("{\"msg\" : \"Could not delete the Component\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;  
            }
            else
                return response()->json([
                    "msg" => "This component is used in prescriptions, please un-assign it first" , 
                    "error" => true ,
                    "data" => null 
                ]);
        }
        return response("{\"msg\" : \"Unknown Component\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
    }

    public function assignComponent(Request $request){
        /**
         * this function needs to be refactored and simplified 
         * but for now let's keep it like that 
         */
        $comp_id = $request->input('component_id');
        $prescription_id = $request->input('prescription_id');
        $quantity = $request->input('quantity');

        $presc = Prescription::find($prescription_id) ; 
        $comp  = Component::find($comp_id) ;

        if($presc == null){
            return response("{\"msg\" : \"Invalid Prescription\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
        }
        if($comp == null){
            return response("{\"msg\" : \"Invalid Component\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
        }


        $pre = PrescriptionComponent::where('prescription_id', $prescription_id)->where('component_id', $comp_id)->first();
        if($pre != null) {
            //get the remaining amount of the component ; 
            if($comp->quantity < $quantity)
                //return error - not enough component amount ;
                return response("{\"msg\" : \"Not Enough Component amount \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
            else {
                //calculate the new component quantity 
                $comp->quantity = $comp->quantity - $quantity ;
                //update Component amount 
                if($comp->save()){
                    $pre->quantity = $pre->quantity + $quantity ;
                    if($pre->save())
                    //return assign successfull
                        return response("{\"msg\" : \"assigned successfully\", \"error\" : false, \"data\" : null}",200)->header("Content-Type", "application/json") ;
                    else {
                    //undo the change on the amount 
                    //return error 
                    $comp->quantity = $comp->quantity + $quantity ;
                    $comp->save() ;
                    return response("{\"msg\" : \"Not Enough Component amount \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
                    }
                }
                //return error -- can't assign the component
                return response("{\"msg\" : \"Can't assign this component \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
            } 
        }
        else {
            //get the remaining amount of the component ; 
            if($comp->quantity < $quantity)
                //return error - not enough component amount ;
                return response("{\"msg\" : \"Not Enough Component amount \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
            else {
                //calculate the new component quantity 
                $comp->quantity = $comp->quantity - $quantity ;
                //update Component amount 
                if($comp->save()){
                    if(PrescriptionComponent::create([
                        'prescription_id' => $prescription_id , 
                        'comp_id' => $comp_id , 
                        'quantity' => $quantity,
                    ]))
                    //return assign successfull
                        return response("{\"msg\" : \"assigned successfully\", \"error\" : false, \"data\" : null}",200)->header("Content-Type", "application/json") ;
                    else {
                    //undo the change on the amount 
                    //return error 
                    $comp->quantity = $comp->quantity + $quantity ;
                    $comp->save() ;
                    return response("{\"msg\" : \"Not Enough Component amount \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
                    }
                }
                //return error -- can't assign the component
                return response("{\"msg\" : \"Can't assign this component \", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
            } 
        }

    }
}