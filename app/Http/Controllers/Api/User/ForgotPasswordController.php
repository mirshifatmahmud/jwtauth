<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation error' => $validator->errors()], 422);
        }

        // Generate token and reset link
        $token = Str::random(60);
        $resetLink = url('/reset/email?token=' . $token);

        // Check if the email already has a token
        $query = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$query) {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            // Send email using the email from the request
            Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

            return response()->json(['message' => 'Reset link sent to your email'], 200);
        } else {
            return response()->json(['message' => 'A token already exists for this email'], 200);
        }
    }



    // public function sendResetLinkEmail(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['validation error',$validator->errors()],422);
    //     }

    //     $token = Str::random(60);
    //     $resetLink = url('/reset/email?token=' . $token);

    //     $query = DB::table('password_reset_tokens')->where('email',$request->email)->first();

    //     if((!$query)){
    //         DB::table('password_reset_tokens')->insert([
    //             'email' => $request->email,
    //             'token' => $token,
    //             'created_at' => now()
    //         ]);
    //         Mail::to($request->email)->send(new ResetPasswordMail($resetLink));
    //         return response()->json(['message' => 'Reset link sent to your email'], 200);
    //     }else{
    //         return response()->json(['message' => 'already token add into password_reset_tokens table of database'], 200);
    //     }


        // $status = Password::sendResetLink($request->only('email'));

        // return $status === Password::RESET_LINK_SENT?response()->json([
        //     'message' => __($status)
        // ],200) : response()->json([
        //     'message' => __($status)
        // ],400);



        // Mail::to($request->email)->send(new ResetPasswordMail($resetLink));
        // Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        // return response()->json(['message' => 'Reset link sent to your email'], 200);


        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 422);
        // }

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // // return $status;

        // // return $status === Password::RESET_LINK_SENT
        // //     ? response()->json(['message' => __($status)], 200)
        // //     : response()->json(['error' => __($status)], 400);

        // $token = Str::random(60);
        // // return $token;
        // $resetLink = url('/reset-password/me?token=' . $token);

        // Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        // return response()->json(['message' => 'Reset link sent to your email'], 200);
    // }

}
