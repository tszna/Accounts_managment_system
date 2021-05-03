<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Address\Address;
use App\Models\AdministrationEmployee\AdministrationEmployee;
use App\Models\Professor\Professor;
use App\Models\User\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        Log::info("Pobranie wszystkich użytkowników");

        $users = User::all()->load(['professor', 'administrationEmployee']);
        return response()->json(
            UserResource::collection($users)
        );
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function get(User $user)
    {
        Log::info("Pobranie użytkownika #" . $user->id);

        return response()->json(
            UserResource::make($user)
        );
    }

    /**
     * @param StoreUserRequest $request
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

        Log::info("Dodanie użytkownika #" . $user->id);

        return response()->json(
            UserResource::make($user)
        );
    }

    /**
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request)
    {
        $mailIsUnique = User::query()
            ->where('id', '!=' , $request->get('id'))
            ->where('email', '=', $request->get('email'))
            ->count() === 0;

        if (!$mailIsUnique) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'email' => 'The email has already been taken.',
                    ],
                    JsonResponse::HTTP_BAD_REQUEST
                )
            );
        }

        $phoneIsUnique = !$request->has('professor') || Professor::query()
            ->where('user_id', '!=' , $request->get('id'))
            ->where('phone', '=', $request->get('professor')['phone'])
            ->count() === 0;

        if (!$phoneIsUnique) {
            throw new HttpResponseException(
                response()->json(
                    [
                        'phone' => 'The phone has already been taken.',
                    ],
                    JsonResponse::HTTP_BAD_REQUEST
                )
            );
        }

        $user = DB::transaction(function () use ($request) {
//            $user = User::query()->where('id', '=', $request->get('id'))->first();
            $user = User::find($request->get('id'));

            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->email = $request->get('email');
            $user->password = $request->get('password');

            $user->save();

            if ($user->isAdministrationEmployee()) {
                $user->administrationEmployee->delete();
                $user->administrationEmployee->correspondenceAddress->delete();
                $user->administrationEmployee->homeAddress->delete();
            }

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

            if ($user->isProfessor()) {
                $user->professor->delete();
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

        Log::info("Aktualizacja użytkownika #" . $user->id, $user->getChanges());

        return response()->json(
            UserResource::make($user->refresh())
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
                $user->administrationEmployee->delete();

                if ($user->administrationEmployee->correspondence_address_id === $user->administrationEmployee->home_address_id) {
                    $user->administrationEmployee->correspondenceAddress->delete();
                } else {
                    $user->administrationEmployee->correspondenceAddress->delete();
                    $user->administrationEmployee->homeAddress->delete();
                }
            }

            $user->delete();
        });

        Log::info("Usunięcie użytkownika #" . $user->id);

        return response()->json(null);
    }
}
