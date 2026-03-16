<?php

namespace App\Http\Controllers;

use App\Models\FeeType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    public function list()
    {
        $data['getRecord']    = FeeType::getRecord();
        $data['header_title'] = 'Fee Types';
        return view('admin.fee_type.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add Fee Type';
        return view('admin.fee_type.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'amount'    => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'status'    => 'required|in:0,1',
        ]);

        FeeType::create([
            'name'        => trim($request->name),
            'amount'      => $request->amount,
            'frequency'   => $request->frequency,
            'description' => $request->description,
            'status'      => $request->status,
            'created_by'  => Auth::id(),
        ]);

        return redirect('admin/fee_type/list')->with('success', 'Fee Type created successfully.');
    }

    public function edit($id)
    {
        $data['getRecord']    = FeeType::getSingle($id);
        if (empty($data['getRecord'])) abort(404);
        $data['header_title'] = 'Edit Fee Type';
        return view('admin.fee_type.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'amount'    => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'status'    => 'required|in:0,1',
        ]);

        $record = FeeType::getSingle($id);
        $record->update([
            'name'        => trim($request->name),
            'amount'      => $request->amount,
            'frequency'   => $request->frequency,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect('admin/fee_type/list')->with('success', 'Fee Type updated successfully.');
    }

    public function delete($id)
    {
        $record = FeeType::getSingle($id);
        $record->is_delete = 1;
        $record->save();
        return redirect('admin/fee_type/list')->with('success', 'Fee Type deleted.');
    }
}