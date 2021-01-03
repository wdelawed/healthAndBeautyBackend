<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Component::all() ;
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
        
        if(Component::create([
            'name' => $name,
            'quantity' => $quantity,
            'expiry_date' => $expiry_date,
        ])){
            return response("{'msg' : 'success', 'error' : null}",200)->header("Content-Type", "application/json") ;
        }
        return response("{'msg' : 'could not add Component', 'error' : true}",200)->header('Content-Type', 'application/json') ;

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
            return $pre ;
        return response("{'msg' : 'Component not found', 'error' : true}",200)->header('Content-Type', 'application/json') ;

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

        $comp = Component::find($id) ;
        if($comp) {
            $comp->name = $name ; 
            $comp->quantity = $quantity ; 
            $comp->expiry_date = $expiry_date ; 
        
            if($comp->save())
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
        $comp = Component::find($id) ;
        if($comp){
            if($comp->delete())
                return response("{'msg' : 'success', 'error' : null}",200)->header("Content-Type", "application/json") ;
            return response("{'msg' : 'could not delete the component', 'error' : true}",200)->header('Content-Type', 'application/json') ;  
        }
        return response("{'msg' : 'unknown component', 'error' : true}",200)->header('Content-Type', 'application/json') ;
    }
}
