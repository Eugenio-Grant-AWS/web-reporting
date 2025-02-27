<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\laravelusers\App\Http\Controllers\UsersManagementController;

class CustomUserManagementController extends UsersManagementController
{

    private $_authEnabled;
    private $_rolesEnabled;
    private $_rolesMiddlware;
    private $_rolesMiddleWareEnabled;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_authEnabled = config('laravelusers.authEnabled');
        $this->_rolesEnabled = config('laravelusers.rolesEnabled');
        $this->_rolesMiddlware = config('laravelusers.rolesMiddlware');
        $this->_rolesMiddleWareEnabled = config('laravelusers.rolesMiddlwareEnabled');

        if ($this->_authEnabled) {
            $this->middleware('auth');
        }

        if ($this->_rolesEnabled && $this->_rolesMiddleWareEnabled) {
            $this->middleware($this->_rolesMiddlware);
        }
    }


    public function create()
    {
        $roles = [];

        if ($this->_rolesEnabled) {
            $roles = config('laravelusers.roleModel')::all();
        }

        $data = [
            'rolesEnabled'  => $this->_rolesEnabled,
            'roles'         => $roles,
        ];

        return view(config('laravelusers.createUserBlade'))->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'firstname'                => 'required|string|max:255',
            'lastname'                 => 'required|string|max:255',
            'email'                    => 'required|email|max:255|unique:users',
            'password'                 => 'required|string|confirmed|min:6',
            'password_confirmation'    => 'required|string|same:password',
        ];

        if ($this->_rolesEnabled) {
            $rules['role'] = 'required';
        }

        $messages = [
            'firstname.required'       => trans('laravelusers::laravelusers.messages.firstnameRequired'),
            'firstname.string'         => trans('laravelusers::laravelusers.messages.firstnameInvalid'),
            'lastname.required'        => trans('laravelusers::laravelusers.messages.lastnameRequired'),
            'lastname.string'          => trans('laravelusers::laravelusers.messages.lastnameInvalid'),
            'email.required'           => trans('laravelusers::laravelusers.messages.emailRequired'),
            'email.email'              => trans('laravelusers::laravelusers.messages.emailInvalid'),
            'password.required'        => trans('laravelusers::laravelusers.messages.passwordRequired'),
            'password.min'             => trans('laravelusers::laravelusers.messages.PasswordMin'),
            'password.max'             => trans('laravelusers::laravelusers.messages.PasswordMax'),
            'role.required'            => trans('laravelusers::laravelusers.messages.roleRequired'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create the user with firstname and lastname instead of name
        $user = config('laravelusers.defaultUserModel')::create([
            'firstname'    => strip_tags($request->input('firstname')),
            'lastname'     => strip_tags($request->input('lastname')),
            'email'        => $request->input('email'),
            'password'     => Hash::make($request->input('password')),
        ]);

        if ($this->_rolesEnabled) {
            $user->attachRole($request->input('role'));
            $user->save();
        }

        return redirect('users')->with('success', trans('laravelusers::laravelusers.messages.user-creation-success'));
    }


    public function edit($id)
    {
        $user = config('laravelusers.defaultUserModel')::findOrFail($id);
        $roles = [];
        $currentRole = [];

        if ($this->_rolesEnabled) {
            $roles = config('laravelusers.roleModel')::all();

            foreach ($user->roles as $user_role) {
                $currentRole[] = $user_role->id;
            }
        }

        // Prepare data to pass to the view
        $data = [
            'user' => $user,
            'rolesEnabled' => $this->_rolesEnabled,
        ];

        if ($this->_rolesEnabled) {
            $data['roles'] = $roles;
            $data['currentRole'] = $currentRole;
        }

        // Pass the data to the view with loggedInAdminId
        return view(config('laravelusers.editIndividualUserBlade'), $data)
            ->with('loggedInAdminId', Auth::user()->id);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Fetch the user by ID
        $user = config('laravelusers.defaultUserModel')::find($id);

        // Check if email or password is being updated
        $emailCheck = ($request->input('email') != '') && ($request->input('email') != $user->email);
        $passwordCheck = $request->input('password') != null;

        // Default rules for firstname and lastname
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
        ];

        // If email is being changed, add email validation rules
        if ($emailCheck) {
            $rules['email'] = 'required|email|max:255|unique:users';
        }

        // If password is being changed, add password validation rules
        if ($passwordCheck) {
            $rules['password'] = 'required|string|min:6|max:20|confirmed';
            $rules['password_confirmation'] = 'required|string|same:password';
        }

        // Conditionally apply the role validation
        // Skip validation for the logged-in admin's own profile
        if ($this->_rolesEnabled && Auth::user()->id !== $user->id) {
            // Only apply role validation for non-admin users or when editing someone else's profile
            $rules['role'] = 'required';
        }

        // Validate the request data based on the rules
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user details
        $user->firstname = strip_tags($request->input('firstname'));
        $user->lastname = strip_tags($request->input('lastname'));

        // If email is changed, update email
        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        // If password is changed, update password
        if ($passwordCheck) {
            $user->password = Hash::make($request->input('password'));
        }

        // Update roles if roles are enabled and the role is provided
        if ($this->_rolesEnabled && Auth::user()->id !== $user->id) {
            // Detach previous roles and attach the new ones
            $user->detachAllRoles();
            $user->attachRole($request->input('role'));
        }

        // Save the user
        $user->save();

        // Redirect back with success message
        return back()->with('success', trans('laravelusers::laravelusers.messages.update-user-success'));
    }
}
