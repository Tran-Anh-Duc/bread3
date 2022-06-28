<?php

namespace Modules\ViewCpanel\Http\Middleware;

use Closure;
use Modules\ViewCpanel\Helpers\Authorization;
use Illuminate\Support\Facades\Log;
use Modules\MongodbCore\Entities\UserCpanel as User;
use Modules\MongodbCore\Repositories\Interfaces\UserCpanelRepositoryInterface;

class TokenIsValid
{
    private $userRepo;

    public function __construct(UserCpanelRepositoryInterface $userRepository) {
       $this->userRepo = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty($request->input('access_token'))) {
            Log::info('Access_token: ' . print_r($request->input('access_token'), true));
            $user = Authorization::validateToken($request->input('access_token'), true);
            $token = $request->input('access_token');
            if (!empty($user->email)) {
                if ($this->validToken($user->email, $token)) {
                    $userDB = $this->userRepo->findByEmail($user->email);
                    session(['user' => $userDB]);
                } else {
                    echo 'Permission denied!'; exit;
                }
            }
        }
        $user = session('user');
        Log::info('User Info: ' . print_r($user, true));
        if (!$user) {
            echo 'Permission denied!'; exit;
        }
        if (!$this->validToken($user[User::EMAIL], $user[User::TOKEN_WEB])) {
            echo 'Permission denied!'; exit;
        }
        return $next($request);
    }

    protected function validToken($email, $token) {
        $userDB = $this->userRepo->findByEmail($email);
        if ($userDB[User::TOKEN_WEB] == $token) {
            return true;
        } else {
            return false;
        }
    }
}
