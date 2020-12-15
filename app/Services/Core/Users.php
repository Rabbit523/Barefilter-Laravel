<?php

namespace App\Services\Core;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Core\User;
use App\Models\Core\Task;

use App\Services\Core\Tasks;
use App\Services\Core\Addresses;
use App\Services\Orders\Manager as OrdersManager;

use App\Mail\ForgotPassword;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Users
{

    public static function init()
    {
        $users = array();
        $admin = ['first_name' => "Barefilter",'last_name' => "Admin",'role_id' => 1,'email' => "kontakt@barefilter.no",'password' => "Valerian"];
        /*$partner = ['first_name' => "Barefilter",'last_name' => "Partner",'role_id' => 2,'email' => "freelancer@rauxmedia.com",'password' => "aBc-Def-GhI"];
        $partner["properties"] = '{"discount": 20, "company_name": "Rauxmedia", "company_number": "1202-6"}';*/
        array_push($users, self::create($admin));
        //array_push($users, self::create($partner));
        return  $users;
    }
    

    private static function create($data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'role_id' => $data['role_id'],
            'email' => $data['email'],
            'phone' => (isset($data['phone'])) ? $data['phone'] : "",
            'shipping_id' => (isset($data['shipping_id'])) ? $data['shipping_id'] : 0,
            'billing_id' => (isset($data['billing_id'])) ? $data['billing_id'] : 0,
            'password' => bcrypt($data['password']),
            "properties" => (isset($data["properties"])) ? json_encode($data["properties"]) : json_encode([])
        ]);
    }


    public static function delete($data){
        $user = User::find($data["uid"]);
        Addresses::deleteUserAddresses($user->id);
        OrdersManager::deleteUserOrders($user->id);
        return $user->delete();
    }

    public static function me()
    {
        return User::where('id', Auth::user()->id)->with(["shipping", "billing"])->first();
    }

    public static function profile($uid)
    {
        return User::where('id', $uid)->with(["shipping", "billing", "orders" => function($q){
            $q->with(["shipping", "products.product", "products.subscription"])->orderBy('created_at', 'desc');
        }])->first();
    }

    public static function authenticate($email, $password)
    {
        $data = ["error" => "Wrong Crendetials"];
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $data = ["user" => Auth::user(), "redirect" => route("dashboard")]; 
        }
        return $data;
    }

    public static function join(Request $request)
    {
        $data = ["error" => "Could not create a new user acount"];
        $user = User::where("email", $request->input('email'))->first();
        if ($user) {
            $data = ["error" => "User already exists"];
        } else {
            $password = uniqid();
            $userData = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'role_id' => User::$memberRole,
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'shipping_id' => 0,
                'billing_id' => 0,
                'password' => $request->input('password')
            ];
            $user = self::create($userData);
            if ($user) {
                Auth::login($user);
                $data = ["user" => $user, "redirect" => route("dashboard")];
                $properties = array("user_id" => $user->id);
                Tasks::add(Task::$welcomeEmail, $properties);
                Tasks::add(Task::$newUserNotificationEmail, $properties);
            }
        }
        return $data;
    }


    public static function addMember($data){
        $password = uniqid();
        $userData = $data["user"];
        $userData["role_id"] = User::$memberRole;
        $userData["password"] = $password;
        $user = self::create($userData);
        $shippingAddressData = $data["addresses"]["shipping"];
        $shippingAddressData["user_id"] = $user->id;
        if($data["addresses"]["same"]){
            $shipping = Addresses::create($shippingAddressData);
            $user->shipping_id = $shipping->id;
            $user->billing_id = $shipping->id;
        } else{
            $billingAddressData = $data["addresses"]["billing"];
            $billingAddressData["user_id"] = $user->id;
            $shipping = Addresses::create($shippingAddressData);
            $billing = Addresses::create($billingAddressData);
            $user->shipping_id = $shipping->id;
            $user->billing_id = $billing->id;
        }
        $user->save();
        $properties = array("user_id" => $user->id, "key" => $password);
        Tasks::add(Task::$automaticSignUpEmail, $properties);
        return $user; 
    }

    public static function addPartner($data)
    {
        $password = uniqid();
        $data["role_id"] = User::$partnerRole;
        $data["password"] = $password;
        $user = self::create($data);
        $properties = array("user_id" => $user->id, "key" => $password);
        Tasks::add(Task::$automaticSignUpEmail, $properties);
        return $user;
    }

    private static function searchByRole($q, $roleId)
    {
        $parts = explode(' ', $q);
        $q = '%' . $q . '%';
        return User::where('role_id', $roleId)
            ->where(function ($query) use ($q, $parts) {
                $query->where('first_name', 'like', '%' . $parts[0] . '%')
                ->where('last_name', 'like', '%' . $parts[count($parts) > 1 ? 1 : 0] . '%')
                ->orWhere('email', 'like', $q);
                // ->orWhere(DB::raw(CONCAT_WS(' ', first_name, last_name)), 'like', $q);
            })
            ->get();
    }

    public static function searchMembers($q)
    {
        if ($q !== null) {
            $users = self::searchByRole($q, User::$memberRole);
        } else {
            $users = User::where('role_id', User::$memberRole)
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }
        return $users;
    }

    public static function searchPartners($q)
    {
        if ($q !== null) {
            $users = self::searchByRole($q, User::$partnerRole);
        } else {
            $users = User::where('role_id', User::$partnerRole)
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }
        return $users;
    }

    public static function changePassword(Request $request)
    {
        $user = User::find($request->input("uid"));
        if (Hash::check($request->input("old"), $user->password)) {
            $user->password = bcrypt($request->input("new"));
            $user->save();
            return $user;
        } else {
            return ["error" => "Old Password does not match"];
        }
    }

    public static function changePasswordWithCode(Request $request)
    {
        $user = User::find($request->input("uid"));
        if (isset($user->properties->code) && $user->properties->code === $request->input('code')) {
            $user->password = bcrypt($request->input("new"));
            $user->save();
            return $user;
        } else {
            return ["error" => "Old Password does not match"];
        }
    }

    public static function update(Request $request)
    {
        $user = User::find($request->input("id"));
        if ($user) {
            $user->first_name = ($request->input("first_name") !== null) ? $request->input("first_name") : $user->first_name;
            $user->last_name = ($request->input("last_name") !== null) ? $request->input("last_name") : $user->last_name;
            $user->phone = ($request->input("phone") !== null) ? $request->input("phone") : $user->phone;
            $user->shipping_id = ($request->input("shipping_id") !== null) ? $request->input("shipping_id") : $user->shipping_id;
            $user->billing_id = ($request->input("billing_id") !== null) ? $request->input("billing_id") : $user->billing_id;    
            $user->properties = ($request->input("properties") !== null) ? json_encode($request->input("properties")) : $user->properties;
            $user->save();
            return $user;
        } else {
            return ["error" => "No user with id " . $request->input("id")];
        }
    }

    public static function registered($email)
    {
        $user = User::where('email', $email)->first();
        return ($user !== null) ? ["error" => $user] : "You're good to go!";
    }

    public static function processOrderUser($data)
    {
        $user = null;
        if (isset($data["uid"])) {
            $user = User::find($data["uid"]);
        }else {
            $shipping = $data["addresses"]["shipping"];
            $email = (isset($shipping['email'])) ? $shipping['email'] : "";
            $user = User::where('email', $email)->first();

            if($user === null ){
                $password = uniqid();
                $email = (!isset($data["guest"]) && $email !== "") ? $email : strtolower($shipping['first_name'] . '.' . $shipping['last_name'] . '-' . $password);
                $user = self::create([
                    'first_name' => $shipping['first_name'],
                    'last_name' => $shipping['last_name'],
                    'role_id' => User::$memberRole,
                    'email' => $email,
                    'phone' => $shipping['phone'],
                    'shipping_id' => 0,
                    'billing_id' => 0,
                    'password' =>$password
                ]);
                $properties = array("user_id" => $user->id, "key" => $password);
                if(!isset($data["guest"])){
                    Tasks::add(Task::$automaticSignUpEmail, $properties);
                    Auth::login($user);
                }
            } else {
                Auth::login($user);
            }
        }
        return $user;
    }


    public static function getPasswordResetCode($email)
    {
        $response = ["error" => "User already registered"];
        $user = User::where(['email' => $email, 'role_id' => User::$memberRole])->first();
        if($user){
            $code = uniqid();
            Mail::to($user->email)->send(new ForgotPassword($user, $code));
            $response = ["code" => $code, "uid" => $user->id];
            $user->properties = json_encode(["code" => $code]);
            $user->save();
        }
        return $response;
    }
}
