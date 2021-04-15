<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Address\Address;
use App\Models\AdministrationEmployee\AdministrationEmployee;
use App\Models\Professor\Professor;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller 
{
    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json(
            UserResource::collection(User::all())
        );
    }

    /**
     * @return JsonResponse
     */
    public function get(User $user)
    {
        return response()->json(
            UserResource::make($user)
        );
    }

    /**
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            if ($request->has('administration_employee')) {
                $correspondenceAddressData = $request->get('administration_employee')['correspondence_address'];

                $correspondenceAddress = Address::create([
                    'voivodeship' => $correspondenceAddressData['voivodeship'],
                    'city' => $correspondenceAddressData['city'],
                    'postal_code' => $correspondenceAddressData['postal_code'],
                    'street' => $correspondenceAddressData['street'],
                    'number' => $correspondenceAddressData['number'],
                ]);

                $homeAddressData = $request->get('administration_employee')['home_address'];

                $homeAddress = Address::create([
                    'voivodeship' => $homeAddressData['voivodeship'],
                    'city' => $homeAddressData['city'],
                    'postal_code' => $homeAddressData['postal_code'],
                    'street' => $homeAddressData['street'],
                    'number' => $homeAddressData['number'],
                ]);

                AdministrationEmployee::create([
                    'user_id' => $user->id,
                    'correspondence_address_id' => $correspondenceAddress->id,
                    'home_address_id' => $homeAddress->id,
                ]);
            }

            if ($request->has('professor')) {
                $professorData = $request->get('professor');

                Professor::create([
                    'user_id' => $user->id,
                    'phone' => $professorData['phone'],
                    'level_of_education' => $professorData['level_of_education'],
                ]);
            }

            return $user;
        });

        return response()->json(
            UserResource::make($user)
        );
    }

    /**
     * @return JsonResponse
     */
    public function delete(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->isProfessor()) {
                $user->professor()->delete();
            }
    
            if ($user->isAdministrationEmployee()) {
                if ($user->administrationEmployee->correspondence_address_id === $user->administrationEmployee->home_address_id) {
                    $user->administrationEmployee->correspondenceAddress->delete();
                } else {
                    $user->administrationEmployee->correspondenceAddress->delete();
                    $user->administrationEmployee->homeAddress->delete();
                }
            }
    
            $user->delete();
        });

        return response()->json(null);
    }
}