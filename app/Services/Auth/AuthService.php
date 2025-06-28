<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTFactory;

class AuthService implements AuthServiceInterface
{

    protected $authRepository, $userRepository;

    public function __construct(AuthRepository $authRepository, UserRepository $userRepository)
    {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function login(string $username, string $password)
    {
        if (!$token = JWTAuth::attempt([
            'username' => $username,
            'password' => $password
        ])) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user();

        $accessPayload = JWTFactory::customClaims([
            'sub' => $user->id,
            'role' => $user->getRoleNames()->first() ?? 'user',
            'type' => 'access'
        ])->make();

        $accessToken = JWTAuth::encode($accessPayload)->get();

        $idPayload = JWTFactory::customClaims([
            'sub' => $user->id,
            'username' => $user->username,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'type' => 'id'
        ])->make();

        $idToken = JWTAuth::encode($idPayload)->get();

        $refreshPayload = JWTFactory::customClaims([
            'sub' => $user->id,
            'type' => 'refresh'
        ])->setTTL(60 * 24 * 7)->make();

        $refreshToken = JWTAuth::encode($refreshPayload)->get();

        Cookie::queue('id_token', $idToken, 60, '/', null, false, false);
        Cookie::queue('access_token', $accessToken, 60, '/', null, false, true);
        Cookie::queue('refresh_token', $refreshToken, 10080, '/', null, false, true);


        return response()
            ->json([
                'message' => 'Login user is successfull',
            ]);
    }

    public function refreshToken(string $refreshToken)
    {
        $payload = JWTAuth::setToken($refreshToken)->getPayload();

        if ($payload->get('type') !== 'refresh') {
            throw new \Exception('Token is not a refresh token');
        }

        $userId = $payload->get('sub');
        $user = $this->userRepository->find($userId);

        $accessPayload = JWTFactory::customClaims([
            'sub' => $user->id,
            'role' => $user->getRoleNames()->first() ?? 'user',
            'type' => 'access',
        ])->make();


        $accessToken = JWTAuth::encode($accessPayload)->get();

        $idPayload = JWTFactory::customClaims([
            'sub' => $user->id,
            'username' => $user->username,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'type' => 'id',
        ])->make();

        $idToken = JWTAuth::encode($idPayload)->get();

        Cookie::queue('access_token', $accessToken, 60, '/', null, false, true);
        Cookie::queue('id_token', $idToken, 60, '/', null, false, false);

        return [
            'message' => 'Token refreshed successfully',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
