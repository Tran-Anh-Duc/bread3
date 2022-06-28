<?php


namespace Modules\ViewCpanel\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\MongodbCore\Entities\UserCpanel as User;
use Modules\MongodbCore\Repositories\GroupRoleRepository;
use Modules\MongodbCore\Repositories\Interfaces\UserCpanelRepositoryInterface as UserCpanelRepository;
use Modules\MongodbCore\Repositories\RoleRepository;

class KsnbValid
{
    private $userRepo;
    private $groupRoleRepository;

    public function __construct(UserCpanelRepository $userRepository,
                                GroupRoleRepository $groupRoleRepository)
    {
        $this->userRepo = $userRepository;
        $this->groupRoleRepository = $groupRoleRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

     public function handle($request, Closure $next)
    {
        log::info("Middleware KsnbValid");
        $user = session('user');
        Log::info('User Info: ' . print_r($user, true));
        if (!$user) {
            echo 'Permission denied!'; exit;
        }
        if (!$this->validToken($user[User::EMAIL])) {
             echo 'Permission denied!'; exit;
        }
        return $next($request);
    }

// check_role_ksnb all staff ksnb

    public function validToken($email)
    {
        $user = $this->groupRoleRepository->getEmailGroupKsnb();
        if (in_array($email, $user)) {
            return true;
        }else {
            return false;
        }
    }







}
