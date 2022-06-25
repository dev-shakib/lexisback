<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Mail\sendEmail;
use Carbon\Carbon;
use Validator;
use Exception;
use Log;

class AuthController extends Controller
{
    /**
     * Attemp to Register user.
     *
     * @return json
    */
    public function registerTeacher(Request $request){

        $input = $request->only(['user_id', 'email', 'password']);

        $validate_data = [
            'user_id' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        return $this->sendOTPTeacher($input);
    }

    /**
     * Register send OTP to email.
     *
     * @return json
    */
    public function sendOTPTeacher($userData){
        
        $otp = rand(1000,9999);
        Log::info("otp = ".$otp);

        $mail_details = [
            'subject' => 'Lexismat - OTP Verification',
            'body' => 'Your OTP is : '. $otp
        ];
        
        \Mail::to($userData['email'])->send(new sendEmail($mail_details));

        $data = [
            'user_id' => $userData['user_id'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'otp' => $otp,
            'status' => 200,
            'message' => "OTP sent successfully"
        ];
        
        return response()->json($data);
    }

    /**
     * Verify OTP and Register user.
     *
     * @return json
    */
    public function verifyOTPTeacher(Request $request){
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $input = $request->only(['user_id', 'email', 'password', 'otp', 'confirm_otp']);
        
        if($input['otp'] == $input['confirm_otp']){
            $teacher_role = Role::where(['name'=>'teacher', 'guard_name'=>'api'])->first();
            $teacher_role->givePermissionTo('create activity');
            $teacher_role->givePermissionTo('read activity');
            $teacher_role->givePermissionTo('update activity');
            $teacher_role->givePermissionTo('delete activity');
            $teacher_role->givePermissionTo('create word');
            $teacher_role->givePermissionTo('read word');
            $teacher_role->givePermissionTo('update word');
            $teacher_role->givePermissionTo('delete word');
            $user = User::create([
                'user_id' => $input['user_id'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
            $user->assignRole($teacher_role);
            
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'success' => true,
                'message' => 'User Created Successfully',
                'access_token' => $accessToken
            ], 201);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'The OTP entered is incorrect.'
            ], 200);
        }
    }

    /**
     * Teacher List.
     *
     * @return json
    */
    public function teacherList(){

        $teachers = User::get();

        return response()->json($teachers);
    }

    /**
     * Login user.
     *
     * @return json
    */
    public function loginTeacher(Request $request){

        $input = $request->only(['user_id', 'password']);

        $validate_data = [
            'user_id' => 'required|string|min:3',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        // authentication attempt
        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            
            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.',
            ], 401);
        }
    }

    /**
     * Logout user.
     *
     * @return json
     */
    public function logout(){
        
        $access_token = auth()->user()->token();

        // logout from only current device
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        // use this method to logout from all devices
        // $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }
}