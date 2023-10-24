<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FirstSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'username'=>'admin',
            'password'=>Hash::make('admin'),
            'role'=>'admin'
        ]);

        User::create([
            'name'=>'Tenizen Bank',
            'username'=>'bank',
            'password'=>Hash::make('bank'),
            'role'=>'bank'
        ]);

        User::create([
            'name'=>'Tenizen Mart',
            'username'=>'kantin',
            'password'=>Hash::make('kantin'),
            'role'=>'kantin'
        ]);

        User::create([
            'name'=>'Kamila',
            'username'=>'kamila',
            'password'=>Hash::make('kamila'),
            'role'=>'siswa'
        ]);

        Student::create([
            'user_id'=>'4',
            'nis'=>'12345',
            'classroom'=>'XII RPL'
        ]);

        Category::create([
            'name'=>'Minuman'
        ]);

        Category::create([
            'name'=>'Makanan'
        ]);

        Category::create([
            'name'=>'Snack'
        ]);

        Product::create([
            'name'=>'Lemon Ice Tea',
            'price'=>'5000',
            'stock'=>100,
            'photo'=>'duhbvfdbvdfbv',
            'description'=>'Es Lemon',
            'category_id'=>1,
            'stand'=>2
        ]);

        Product::create([
            'name'=>'Meat Ball',
            'price'=>'10000',
            'stock'=>50,
            'photo'=>'djgbjdfjdfgj',
            'description'=>'Bakso',
            'category_id'=>2,
            'stand'=>1
        ]);

        Product::create([
            'name'=>'Risoles',
            'price'=>'3000',
            'stock'=>50,
            'photo'=>'duhbvfdbvdfbv',
            'description'=>'Risol',
            'category_id'=>3,
            'stand'=>1
        ]);

        Wallet::create([
            'user_id'=>4,
            'credit'=>100000,
            'debit'=>null,
            'description'=>'Pembukaan Tabungan'
        ]);

        Wallet::create([
            'user_id'=>4,
            'credit'=>15000,
            'debit'=>null,
            'description'=>'Pembelian'
        ]);

        Wallet::create([
            'user_id'=>4,
            'credit'=>20000,
            'debit'=>null,
            'description'=>'Pembelian'
        ]);

        Transaction::create([
            'user_id'=>4,
            'product_id'=>1,
            'status'=>'di keranjang',
            'order_id'=>"INV_12345",
            'price'=>5000,
            'quantity'=>1
        ]);

        Transaction::create([
            'user_id'=>4,
            'product_id'=>2,
            'status'=>'di keranjang',
            'order_id'=>"INV_12345",
            'price'=>10000,
            'quantity'=>1
        ]);

        Transaction::create([
            'user_id'=>4,
            'product_id'=>3,
            'status'=>'di keranjang',
            'order_id'=>"INV_12345",
            'price'=>3000,
            'quantity'=>2
        ]);

        $total_debit=0;

        $transactions=Transaction::where('order_id' == 'INV_12345');

        foreach($transactions as $transaction){
            $total_price=$transaction->price * $transaction->quantity;
            $total_debit += $total_price;
        };

        Wallet::create([
            'user_id'=>4,
            'credit'=>$total_debit,
            'description'=>'Pembelian produk'
        ]);

        foreach($transactions as $transaction){
            Transaction::find($transaction->id)->update([
                'status'=>'dibayar'
            ]);
        };

        foreach($transactions as $transaction){
            Transaction::find($transaction->id)->update([
                'status'=>'diambil'
            ]);
        };

        foreach($transactions as $transaction){
            Transaction::find($transaction->id)->update([
                'status'=>'di keranjang'
            ]);
        };
    }
}
