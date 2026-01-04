<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('tipe')->get();
        return view('payment_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'        => 'required|in:cash,transfer,qris',
            'nama_metode' => 'required|string|max:255',
            'qr_image'    => 'nullable|image|max:2048',
        ]);

        $data = [
            'tipe'         => $request->tipe,
            'nama_metode'  => $request->nama_metode,
            'nama_bank'    => $request->nama_bank,
            'no_rekening'  => $request->no_rekening,
            'nama_pemilik' => $request->nama_pemilik,
            'is_active'    => $request->has('is_active') ? 1 : 0,
        ];

        // HANDLE UPLOAD QR
        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')
                ->store('qris', 'public');
        }

        PaymentMethod::create($data);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Payment method berhasil ditambahkan');
    }


    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate([
            'tipe'         => 'required|in:cash,transfer,qris',
            'nama_metode'  => 'required|string|max:255',
            'nama_bank'    => 'nullable|string|max:100',
            'no_rekening'  => 'nullable|string|max:100',
            'nama_pemilik' => 'nullable|string|max:100',
            'qr_image'     => 'nullable|image|max:2048',
            'is_active'    => 'boolean',
        ]);

        if ($request->hasFile('qr_image')) {
            if ($paymentMethod->qr_image) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $data['qr_image'] = $request->file('qr_image')->store('qris', 'public');
        }

        $paymentMethod->update($data);

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method berhasil diperbarui');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->qr_image) {
            Storage::disk('public')->delete($paymentMethod->qr_image);
        }

        $paymentMethod->delete();

        return back()->with('success', 'Payment method dihapus');
    }
}
