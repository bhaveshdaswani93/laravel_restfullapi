<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;
use App\Transformers\UserTransformer;

class UserController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        // validate($request,$rules);
        // dd($request->validate($rules));
        $this->validate($request,$rules);
        // return "Hello world";
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationToken();
        $data['admin'] = User::REGULAR_USER;
        $user = User::create($data);
        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $user = User::findOrFail($id);
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        // $user = User::findOrFail($id);
        $rules = [
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'.User::ADMIN_USER.','.User::REGULAR_USER
        ];
        $this->validate($request,$rules);
        if($request->has('name'))
        {
            $user->name = $request->name;
        }
        if($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }
        if($request->has('email') && $user->email != $request->email )
        {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationToken();
            $user->email = $request->email;
        }
        if($request->has('admin'))
        {
            if(!$user->isVerified())
            {
                $this->errorResponse('Only verified user can change admin property',409);
            }

            $user->admin = $request->admin;
        }
        
        if(!$user->isDirty())
        {
            return $this->errorResponse('Nothing to change',422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data'=>$user],200);
    }

    public function verify($token)
    {
        $user = User::where('verification_token','=',$token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();
        return $this->showMessage("User verified successfully.");
    }

    public function resend(User $user)
    {
        if($user->isVerified())
        {
            return $this->errorResponse("The user is already verified.",409);
        }
        // return new UserCreated($user);
         retry(5,function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));        
            },100);
        return $this->showMessage("Verification mail have been sent successfully.");
    }
}
