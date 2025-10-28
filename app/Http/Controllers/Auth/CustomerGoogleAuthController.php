<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class CustomerGoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('customer.login')->withErrors(['msg' => 'Erro ao autenticar com Google.']);
        }

        $customer = Customer::where('email', $googleUser->getEmail())->first();

        if (!$customer) {
            $customer = Customer::create([
                'name'     => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'google_id' => $googleUser->getId(),
            ]);
        }

        Auth::guard('customer')->login($customer, true);

        return redirect()->route('customer.profile');
    }
}
