<?php

namespace App\Http\Controllers;

use App\Models\WorkItem;
use App\Models\WorkItemType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class WorkItemTypeController extends Controller
{
    public function index(Request $request){
        if(!auth()->user()->can('viewAny',WorkItemType::class)){
            return view('not_authorized');
        }

        $order = $request->order;
        $sort =  $request->sort;

        $data = WorkItemType::filter(request(['q']))
            ->when(isset($request->order), function ($query) use ($order, $sort) {
                return $query->orderBy($order, $sort);
            })->when(!isset($request->order), function ($query) {
                return $query->orderBy('created_at','DESC');
            })
            ->paginate(20)->withQueryString();
        return view('work_item_category.index',[
            'work_item_category' => $data
        ]);
    }

    public function create(){
        if(auth()->user()->cannot('create',WorkItemType::class)){
            return view('not_authorized');
        }

        return view('work_item_category.create');
    }

    public function store(Request $request)
    {
        if(auth()->user()->cannot('create',WorkItemType::class)){
            return view('not_authorized');
        }

        $this->validate($request,[
            'code' => 'required|unique:work_item_types',
            'title' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $category = new WorkItemType([
                'code' => $request->code,
                'title'=> $request->title,
            ]);
            $category->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('work-item-category');

        } catch (Exception $e) {
            DB::rollback();
            return redirect('work-item-category/create')->withErrors($e->getMessage());
        }
    }

    public function show(WorkItemType $workItemType)
    {
        if(auth()->user()->cannot('viewAny',WorkItemType::class)){
            return view('not_authorized');
        }
        return view('work_item_category.edit',[
            'work_item_category' => $workItemType
        ]);
    }

    public function update(Request $request, WorkItemType $workItemType)
    {
        if(auth()->user()->cannot('update',WorkItemType::class)){
            return view('not_authorized');
        }
        $this->validate($request,[
            Rule::unique('work_item_types')->ignore($workItemType->id),
            'title' => 'required',
        ]);

        try {
            DB::begintransaction();
            $workItemType->code = $request->code;
            $workItemType->title = $request->title;
            $workItemType->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('work-item-category/'.$workItemType->id);

        } catch (Exception $e){
            DB::rollback();
            return redirect('work-item-category/'.$workItemType->id)->withErrors($e->getMessage());
        }
    }

    public function destroy(WorkItemType $workItemType)
    {
        try{
            $workItemType->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Item Successfully Deleted'
            ]);
        } catch (Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
