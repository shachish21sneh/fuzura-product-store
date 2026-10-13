<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactInquiry::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->latest()->paginate(15)->withQueryString();
        $totalInquiries = ContactInquiry::count();
        $unreadCount = ContactInquiry::unread()->count();

        return view('admin.inquiries.index', compact('inquiries', 'totalInquiries', 'unreadCount'));
    }

    public function show($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        return response()->json(['success' => true, 'inquiry' => $inquiry]);
    }

    public function updateStatus(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:unread,in_progress,replied,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['status'] === 'replied' && !$inquiry->replied_at) {
            $validated['replied_at'] = now();
        }

        $inquiry->update($validated);

        ActivityLog::record(
            'INQUIRY_STATUS_UPDATE',
            "Inquiry #{$inquiry->id} from {$inquiry->name} marked as {$inquiry->status}",
            'inquiry',
            $inquiry->id
        );

        return redirect()->route('admin.inquiries.index')->with('success', "Inquiry #{$inquiry->id} status updated successfully.");
    }

    public function destroy($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiryId = $inquiry->id;
        $inquiry->delete();

        ActivityLog::record(
            'INQUIRY_DELETED',
            "Inquiry #{$inquiryId} removed",
            'inquiry',
            $inquiryId
        );

        return redirect()->route('admin.inquiries.index')->with('success', "Inquiry ticket #{$inquiryId} removed successfully.");
    }
}
