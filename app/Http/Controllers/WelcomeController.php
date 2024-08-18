<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\DropdownService;
use App\Traits\HandlesTransaction;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormSubmissionMail;

class WelcomeController extends Controller
{
    use HandlesTransaction;
    
    public function __construct(DropdownService $dropdown){
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        return inertia('Welcome',[
            'barangays' => $this->dropdown->barangays('097332000')
        ]); 
    }

    public function store(CustomerRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            
            $data = Customer::create(array_merge($request->all(),[
                'reserved_at' => $request->date.' '.$request->time,
                'status_id' => 1
            ]));
            return [
                'data' => $data,
                'message' => 'Your booking was completed successfully!', 
                'info' => "We will call you for confirmation. Thank you for choosing us."
            ];
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
