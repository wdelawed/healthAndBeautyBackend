<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PrescriptionsHistory;
use Illuminate\Http\Request;
use App\Http\NetworkResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers =  Customer::all() ;
        foreach($customers as $customer){
            $prescriptions = $customer->prescriptions ;
            $customer->replicate()->fill([
                "prescriptions" => $prescriptions, 
            ]) ;
        }
        return response("{\"msg\" : \"success \", \"error\" : false, \"data\" : $customers}",200)->header("Content-Type", "application/json");
     
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
       $age = $request->input('age') ;
       $before_img = $request->input('before_img') ;
       $after_img = $request->input('after_img') ;
       $notes = $request->input('notes') ;

       if($request->before_img ) {
        $file_name = 'beforeImg'.time() . '.' . $request->before_img->getClientOriginalExtension();
        $request->before_img->move(public_path('images'), $file_name);
       }else {
           $file_name = "placeholder.jpg";
       }
       
      
        
        if(Customer::create([
            'name' => $name , 
            'age'  => $age , 
            'before_img' => $file_name , 
            'after_img' => '' , 
            'notes' => $notes
        ])){
            
            return response("{'msg' : 'success', 'error' : null, 'data' : null}",200)->header("Content-Type", "application/json") ;
        }
        return response("{'msg' : 'could not add customer', 'error' : true, 'data': null}",200)->header('Content-Type', 'application/json') ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $customer->replicate()->fill([
            "prescriptions" => $customer->prescriptions, 
            "count" => $customer->prescriptions->count(),
        ]) ;
        if($customer != null)
            return response("{\"msg\" : \"success\", \"error\" : false, \"data\" : $customer}",200)->header('Content-Type', 'application/json') ;
        return response("{\"msg\" : \"success \", \"error\" : false, \"data\" : null}",200)->header('Content-Type', 'application/json') ;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $customer->name  = $request->input('name') ; 
        $customer->age   = $request->input('age') ; 
        $customer->notes = $request->input('notes') ; 

    
        if($customer->save()){
            return response("{'msg' : 'success', 'error' : false, 'data': null}",200)->header("Content-Type", "application/json") ;
        }
        return response("{'msg' : 'could not update customer data', 'error' : true, 'data' : null}",200)->header('Content-Type', 'application/json') ;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        foreach($customer->prescriptions as $pre){
            $pre->delete() ;
        }
        if($customer->delete()){
            return response("{'msg' : 'success', 'error' : false, 'data' : null}",200)->header("Content-Type", "application/json") ;
        }
        return response("{'msg' : 'could not delete customer', 'error' : true, 'data': null}",200)->header('Content-Type', 'application/json') ;
    }

     /**
     * update Customer after Image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAfterImage(Request $request)
    {
        if($request->after_img){
            $file_name = 'afterImg'.time() . '.' . $request->after_img->getClientOriginalExtension();
            $request->after_img->move(public_path('images'), $file_name);

            $customer = Customer::find($request->input("id")) ;
            if($customer) {
                $customer->after_img = $file_name ; 
                if($customer->save())
                    return response('{"msg" : "success", "error" : false, "data": null}',200)->header("Content-Type", "application/json") ;
                else
                    return response("{'msg' : 'could not update customer', 'error' : true, 'data': null}",200)->header('Content-Type', 'application/json') ;
            }
            return response("{'msg' : 'invalid customer', 'error' : true, 'data': null}}",200)->header('Content-Type', 'application/json') ;
        } 
         return response("{'msg' : 'invalid image ', 'error' : true, 'data': null}}",200)->header('Content-Type', 'application/json') ;
    }
    

    
}
