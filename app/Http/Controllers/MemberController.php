<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('transactions')->paginate(10);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $validated['member_id'] = 'MEM' . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['membership_date'] = now();

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member registered successfully!');
    }

    public function show(Member $member)
    {
        $member->load('transactions.book');
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        if ($member->transactions()->where('status', 'borrowed')->exists()) {
            return redirect()->route('members.index')
                ->with('error', 'Cannot delete member with active borrows!');
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully!');
    }
}
