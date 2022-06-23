<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Validator;
use Exception;
use Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendEmail;

class AuthController extends Controller
{
    /**
     * Register user.
     *
     * @return json
     */
    public function register(Request $request)
    {

        $input = $request->only(['userId', 'email', 'password']);

        $validate_data = [
            'userId' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        // $user = User::create([
        //     'name' => $input['name'],
        //     'email' => $input['email'],
        //     'password' => Hash::make($input['password'])
        // ]);


        // event(new Registered($user));

        // return response(['user' => $user, 'access_token' => $accessToken]);

        return $this->sendOTP($input);

    }


    public function sendOTP($userData)
    {
        $otp = rand(1000,9999);
        Log::info("otp = ".$otp);
        // $user = User::where('email','=',$userData->email)->update(['otp' => $otp]);

        // if($user){
            $mail_details = [
                'subject' => 'Lexismat - OTP Verification',
                'body' => 'Your OTP is : '. $otp
            ];
        
            \Mail::to($userData['email'])->send(new sendEmail($mail_details));

            // $accessToken = $user->createToken('authToken')->accessToken;
            $data = [
                'userId' => $userData['userId'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'otp' => $otp,
                'status' => 200,
                'message' => "OTP sent successfully"
            ];
        
            return response()->json($data);

        // }
        // else{
        //     return response(["status" => 401, 'message' => 'Invalid']);
        // }
    }

    /**
     * Login user.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
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
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    /**
     * Access method to authenticate.
     *
     * @return json
     */
    public function userDetail()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully.',
            'data' => auth()->user()
        ], 200);
    }

    /**
     * Logout user.
     *
     * @return json
     */
    public function logout()
    {
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