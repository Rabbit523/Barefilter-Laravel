<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Core\Users;
use App\Services\Core\Addresses;

class UsersController extends ApiController
{
    
    public function init() {
        return $this->json(
            Users::init()
        );
    }

    public function me() {
        return $this->json(
            Users::me()
        );
    }

    public function profile($uid) {
        return $this->json(
            Users::profile($uid)
        );
    }

    public function authenticate(Request $request) {
        return $this->json(
            Users::authenticate($request->input("email"), $request->input("password"))
        );
    }

    public function getPasswordResetCode($email = null) {
        return $this->json(
            Users::getPasswordResetCode($email)
        );
    }

    public function join(Request $request) {
        return $this->json(
            Users::join($request)
        );
    }

    public function addMember(Request $request) {
        return $this->json(
            Users::addMember($request->all())
        );
    }

    public function addPartner(Request $request) {
        return $this->json(
            Users::addPartner($request->all())
        );
    }

    public function members($q = null) {
        return $this->json(
            Users::searchMembers($q)
        );
    }

    public function partners($q = null) {
        return $this->json(
            Users::searchPartners($q)
        );
    }


    public function password(Request $request){
        return $this->json(
            Users::changePassword($request)
        );
    }

    public function resetPassword(Request $request){
        return $this->json(
            Users::changePasswordWithCode($request)
        );
    }

    public function update(Request $request){
        return $this->json(
            Users::update($request)
        );
    }

    public function delete(Request $request){
        return $this->json(
            Users::delete($request->all())
        );
    }

    public function registered($email){
        return $this->json(
            Users::registered($email)
        );
    }

    public function myAddresses($uid){
        return $this->json(
            Addresses::getMyAddresses($uid)
        );
    }

    public function addAddress(Request $request){
        return $this->json(
            Addresses::add($request)
        );
    }
    public function deleteAddress(Request $request){
        return $this->json(
            Addresses::delete($request)
        );
    }
}
