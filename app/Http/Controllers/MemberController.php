<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index() {
        $members = Member::orderBy('created_at', 'desc')->get();
        return view('members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'diskon_persen' => 'required|integer|min:0|max:100',
            'expired_at' => 'required|date',
        ]);

        $kode = 'MBR-' . strtoupper(Str::random(4));

        Member::create([
            'kode_member' => $kode,
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'diskon_persen' => $request->diskon_persen,
            'expired_at' => $request->expired_at,
            'poin' => 0,
            'status' => 'active'
        ]);

        return redirect()->route('members.index')->with('success', 'Member baru berhasil didaftarkan.');
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'diskon_persen' => 'required|integer|min:0|max:100',
            'expired_at' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($request->all());

        return redirect()->route('members.index')->with('success', 'Data member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }
}
