<?php

namespace admin\faqs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\faqs\Requests\FaqCreateRequest;
use admin\faqs\Requests\FaqUpdateRequest;
use admin\faqs\Models\Faq;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        try {
            $faqs = Faq::
                filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->latest()
                ->paginate(5)
                ->withQueryString();

            return view('faq::admin.index', compact('faqs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faqs: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('faq::admin.createOrEdit');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faqs: ' . $e->getMessage());
        }
    }

    public function store(FaqCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            Faq::create($requestData);
            return redirect()->route('admin.faqs.index')->with('success', 'Faq created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faqs: ' . $e->getMessage());
        }
    }

    /**
     * show faq details
     */
    public function show(Faq $faq)
    {
        try {
            return view('faq::admin.show', compact('faq'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faqs: ' . $e->getMessage());
        }
    }

    public function edit(Faq $faq)
    {
        try {
            return view('faq::admin.createOrEdit', compact('faq'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faq for editing: ' . $e->getMessage());
        }
    }

    public function update(FaqUpdateRequest $request, Faq $faq)
    {
        try {
            $requestData = $request->validated();

            $faq->update($requestData);
            return redirect()->route('admin.faqs.index')->with('success', 'Faq updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load faq for editing: ' . $e->getMessage());
        }
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $faq = Faq::findOrFail($request->id);
            $faq->status = $request->status;
            $faq->save();

            // create status html dynamically        
            $dataStatus = $faq->status == '1' ? '0' : '1';
            $label = $faq->status == '1' ? 'Active' : 'InActive';
            $btnClass = $faq->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $faq->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.faqs.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $faq->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to '.$label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}

