<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::withCount('expenses')
            ->withSum('expenses', 'amount')
            ->get();

        return view('members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
        ]);

        // Clean WhatsApp number
        $validated['whatsapp'] = preg_replace('/[^0-9]/', '', $validated['whatsapp']);

        $member = Member::updateOrCreate(
            ['whatsapp' => $validated['whatsapp']],
            ['name' => $validated['name']]
        );

        return response()->json([
            'success' => true,
            'member' => $member,
            'message' => 'Member berhasil ditambahkan!',
        ]);
    }

    public function checkIdentity(Request $request)
    {
        $member = Member::where('whatsapp', $request->whatsapp)->first();

        return response()->json([
            'found' => $member !== null,
            'member' => $member,
        ]);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json(['success' => true]);
    }
}
