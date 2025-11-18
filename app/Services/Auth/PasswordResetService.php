<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Events\PasswordEmail;
use App\Events\PasswordChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\Auth\PasswordResetInterface;
use Illuminate\Http\Request;

class PasswordResetService implements PasswordResetInterface
{
     private string $table = 'password_reset_tokens';

     /**
      * This defines the amount of time (in minutes) the password reset link will be valid for.
      * @var int
      */
     private int $expiresAfter = 10;

     public function __construct(
          private readonly UserRepositoryInterface $userRepository,
          private readonly Request $request
     ) {}

     public function sendResentLink(string $email): void
     {
          DB::transaction(function () use ($email) {
               $user = $this->userRepository->findBy('email', $email);

               $token = Str::random(32);

               $now = now()->copy();

               DB::table($this->table)->upsert(
                    ['email' => $user->email, 'token' => Hash::make($token), 'created_at' => $now],
                    ['email'],
                    ['token', 'created_at']
               );

               event( new PasswordEmail( $user, $token, $now->addMinutes($this->expiresAfter)) );
          });
     }

     public function resetPassword(string $email, string $hash, string $password): void
     {
          DB::transaction(function () use ($email, $hash, $password) {
               $token = $this->getToken($email);

               $this->abortIfExpiredOrInvalid($token, $hash);

               $user = $this->userRepository->findBy('email', $email);

               $this->userRepository->updatePassword($user, $password);

               $this->deleteToken($email);

               event( new PasswordChanged($user) );
          });
     }

     private function getToken(string $email)
     {
          $token = DB::table($this->table)
                         ->where('email', $email)
                         ->first();

          throw_if(
               is_null($token),
               throw ValidationException::withMessages(__('Password reset link is invalid'))
          );

          return $token;
     }

     private function abortIfExpiredOrInvalid($token, $hash): void
     {
          throw_if(
               ! Hash::check($hash, $token->token),
               throw ValidationException::withMessages(__('Password reset link is invalid'))
          );

          $now = now()->copy();
          $expired_at = Carbon::parse($token->created_at)->addMinutes($this->expiresAfter);

          throw_if(
               $now->greaterThan($expired_at),
               throw  ValidationException::withMessages(__('Password reset link has expired'))
          );
     }

     private function deleteToken(string $email): void
     {
          DB::table($this->table)->where('email', $email)->delete();
     }
}
