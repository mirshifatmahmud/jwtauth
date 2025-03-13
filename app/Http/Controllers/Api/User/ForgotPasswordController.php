<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
{

    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));

    // return $status === Password::RESET_LINK_SENT?response()->json([
    //     'message' => __($status)
    // ],200) : response()->json([
    //     'message' => __($status)
    // ],400);


    $token = Str::random(60);
    $resetLink = url('/reset-password/me?token=' . $token);

    Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

    return response()->json(['message' => 'Reset link sent to your email'], 200);


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
}

}
