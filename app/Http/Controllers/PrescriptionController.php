<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prescriptions = Prescription::all() ;
        foreach($prescriptions as $prescription){
            $count = $prescription->customers->count() ;
            $prescription->replicate()->fill([
                'components' => $prescription->components,
                'customers' => $count,
            ]);
        }
        return response("{\"msg\" : \"success\", \"error\" : false, \"data\" : $prescriptions}",200)->header("Content-Type", "application/json") ;

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
        $notes = $request->input('notes') ;
        $creation_date = $request->input('creation_date') ;
        $price = $request->input('price') ;

        $file_name  = "" ;

        if($request->presc_image){
            $file_name = 'prescImage'.time() . '.' . $request->presc_image->getClientOriginalExtension();
            $request->presc_image->move(public_path('images/prescriptions'), $file_name);
        } 
        
        if($p = Prescription::create([
            'name' => $name,
            'notes' => $notes,
            'creation_date' => $creation_date,
            'price' => (int) $price,
            'presc_image' => 'prescriptions/'.$file_name
        ])){
            return response("{\"msg\" : \"success\", \"error\" : false, \"data\" : $p}",200)->header("Content-Type", "application/json") ;
        }
        return response("{\"msg\" : \"could not add Prescription\", \"error\" : true, \"data\" : null}",200)->header('Content-Type', 'application/json') ;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pre = Prescription::find($id) ; 
        if($pre)
            return $pre;
        return response("{'msg' : 'prescription not found', 'error' : true}",200)->header('Content-Type', 'application/json') ;

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
        $notes = $request->input('notes') ;
        $creation_date = $request->input('creation_date') ;
        $price = $request->input('price') ;

        $pre = Prescription::find($id) ;
        if($pre) {
            $pre->name = $name ; 
            $pre->notes = $notes ; 
            $pre->creation_date = $creation_date ; 
            $pre->price = $price ;
            if($pre->save())
                return response("{'msg' : 'success', 'error' : null}",200)->header("Content-Type", "application/json") ;
            return response("{'msg' : 'could not update the prescription', 'error' : true}",200)->header('Content-Type', 'application/json') ;  
        }
        return response("{'msg' : 'unknown prescription', 'error' : true}",200)->header('Content-Type', 'application/json') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pre = Prescription::find($id) ;
        if($pre){
            if($pre->delete())
                return response("{'msg' : 'success', 'error' : null}",200)->header("Content-Type", "application/json") ;
            return response("{'msg' : 'could not delete the prescription', 'error' : true}",200)->header('Content-Type', 'application/json') ;  
        }
        return response("{'msg' : 'unknown prescription', 'error' : true}",200)->header('Content-Type', 'application/json') ;
    }
}
