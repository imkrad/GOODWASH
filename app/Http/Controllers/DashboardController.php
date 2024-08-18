<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ListStatus;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Http\Resources\CustomerResource;

class DashboardController extends Controller
{
    use HandlesTransaction;

    public function index(Request $request){
        if(!\Auth::check()){
            return inertia('Auth/Login');
        }else{
            return inertia('Dashboard/Index',[
                'statuses' => $this->statuses(),
                'counts' => $this->counts()
            ]);
        }
    }

    public function customers(Request $request){
        $data = CustomerResource::collection(
            Customer::query()
            ->with('barangay:code,name','status')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('firstname', 'LIKE', "%{$keyword}%")->orWhere('lastname', 'LIKE', "%{$keyword}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($request->sort, function ($query, $sort) use ($request) {
                if($request->sortby == 'Created At'){
                    $query->orderBy('created_at',$request->sort);
                }else{
                    $query->orderBy('reserved_at',$request->sort);
                }
            })
            ->paginate($request->count)
        );
        return $data;
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            $data = Customer::with('barangay:code,name','status')->where('id',$request->id)->update(['status_id' => $request->status_id]);
            $data = Customer::with('barangay:code,name','status')->where('id',$request->id)->first();
            return [
                'data' => new CustomerResource($data),
                'message' => 'Booking updated!', 
                'info' => "You've successfully updated the booking.",
            ];
        });
        
        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    private function counts(){
        $statuses = ListStatus::all();
        foreach($statuses as $status){
            $counts[] = Customer::where('status_id',$status['id'])->count();
        }
        return $counts;
    }

    private function statuses(){
        $data = ListStatus::where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'color' => $item->color,
                'others' => $item->others,
            ];
        });
        return $data;
    }

}
