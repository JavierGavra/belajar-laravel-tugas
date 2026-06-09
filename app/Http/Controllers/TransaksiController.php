<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $cart = app('cart');

        $data = [
            'items' => $cart->getContent(),
            'total' => $cart->getTotal()
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add(Request $request)
    {
        // Menambahkan item menggunakan app('cart')
        app('cart')->add([
            'id'         => $request->input('id'),
            'name'       => $request->input('nama'),
            'price'      => $request->input('harga'),
            'quantity'   => 1,
            'attributes' => [
                'foto' => $request->input('foto')
            ]
        ]);
        
        $message = 'Produk berhasil ditambahkan ke keranjang. ' . 
                   '<a href="' . url('keranjang') . '">Lihat</a>';
        
        return redirect()->back()->with('success', $message);
    }

    public function cart_edit(Request $request)
    {
        // Kita melakukan loop terhadap input array 'qty' dari form
        foreach ($request->input('qty') as $id => $quantity) {
            
            // Update kuantitas barang di keranjang
            // 'relative' => false berarti mengganti nilai lama dengan nilai baru
            app('cart')->update($id, [
                'quantity' => [
                    'relative' => false,
                    'value'    => $quantity
                ]
            ]);
        }

        // Redirect kembali dengan flash message
        return redirect('keranjang')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function cart_delete(int $id)
    {
        // Menghapus item berdasarkan ID
        app('cart')->remove($id);
        
        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    public function cart_clear()
    {
        // Mengosongkan keranjang
        app('cart')->clear();
        
        return redirect()->back()->with('success', 'Keranjang telah dikosongkan');
    }
}