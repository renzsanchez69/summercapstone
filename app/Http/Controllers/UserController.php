<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Iatstuti\Database\Support\CascadeSoftDeletes;

use App\User as User;
use App\Sellers as Sellers;
use App\Orders;
use DateTime;
use Redirect;
use Session;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $date = new DateTime($user->date_of_birth);
        $now = new DateTime();
        $interval = $now->diff($date);
        $seller = Sellers::where('user_id', $id)->get();

        if ($seller->isEmpty()) {
            $seller = false;
        }

        return view('users.profile', [
            'user' => $user,
            'age' => $interval->y,
            'seller' => $seller[0]
        ]);
    }

    /**
     * Update user profile.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function update(User $user)
    {
        if (Auth::user()->email == request('email')) {
            $this->validate(request(), [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string'],
                'date_of_birth' => ['required', 'date'],
                'address' => ['required', 'string'],
                'phone_number' => ['required', 'regex:/(7)[0-9]{7}/']
            ]);

            $user->name = request('name');
            $user->date_of_birth = request('date_of_birth');
            $user->gender = request('gender');
            $user->address = request('address');
            $user->phone_number = request('phone_number');
        } else {
            $this->validate(request(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'gender' => ['required', 'string'],
                'date_of_birth' => ['required', 'date'],
                'address' => ['required', 'string'],
                'phone_number' => ['required', 'regex:/(7)[0-9]{7}/']
            ]);

            $user->name = request('name');
            $user->email = request('email');
            $user->date_of_birth = request('date_of_birth');
            $user->gender = request('gender');
            $user->address = request('address');
            $user->phone_number = request('phone_number');
        }

        $user->save();
        Session::flash('success', 'Profile Updated!');
        return back();
    }

    /**
     * Show the update password form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updatePasswordForm()
    {
        return view('users.password');
    }

    /**
     * Update the user password.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updatePassword(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            //Function validates if the correct password is inputted to update new password
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ]);

        $user->password = Hash::make($request->password);

        $user->save();
        Auth::logout();

        return redirect('/login')->with('message', 'Please try logging in to your account with your new password!');
    }

    /**
     * Show the deactivate account form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deactivateForm()
    {
        return view('users.deactivate');
    }

    /**
     * Deactivate account.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            //Function validates if the correct password is inputted to update new password
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ]);

        $user->delete();
        Auth::logout();

        return redirect('/login')->with('deac', 'Account has been successfully deactivated.');
    }

    /**
     * Update user address.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string'
        ]);


        $id = Auth::user()->id;
        $user = User::find($id);

        $user->address = $request->address;

        $user->save();

        Session::flash('success', 'Address updated!');
        return back();
    }

    /**
     * Retrieve user order history.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function history(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        $history = Orders::select(
            'orders.id',
            'orders.order_date',
            'orders.delivery_status',
            'orders.total AS orderTotalAmount'
        )
            ->where('user_id', $id)->paginate(12);

        $history = Orders::join('orders_products', 'orders.id', '=', 'orders_products.orders_id')
            ->join('products', 'orders_products.products_id', '=', 'products.id')
            ->select(
                'orders.id',
                'orders.order_date',
                'orders.delivery_status',
                'orders.total AS order_total_amount',
                'orders_products.qty',
                'orders.payment_method',
                'products.price AS presyo',
                'products.name'
            )
            ->where('user_id', $id)->paginate(12);

        return view('users.history.list', ['history' => $history]);
    }


    /**
     * Retrieve user specific order history.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function order($id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $order = Orders::find($id);
        $products = $order->products;
        $product_subtotal = 0.00;
        $product_total = [];
        $cart_items = 0;
        $product_seller_username = [];

        foreach ($products as $product) {
            $product_subtotal += $product->pivot->qty * $product->price;
            $cart_items += $product->pivot->qty;
            array_push(
                $product_total,
                ($product->pivot->qty * $product->price)
            );
            array_push($product_seller_username, $product->seller->user->username);
        }
        return view('users.history.details', [
            'order' => $order,
            'products' => $products,
            'product_subtotal' => $product_subtotal,
            'product_total' => $product_total,
            'cart_items' => $cart_items,
            'product_username' => $product_seller_username
        ]);
    }

    public function confirmOrder($id)
    {
        $order = Orders::findOrFail($id);
        $order->delivery_status = 3;
        $order->save();
        Session::flash('confirmSuccess', 'Order was successfully received!');
        return back();
    }
}
