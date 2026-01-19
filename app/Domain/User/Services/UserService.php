<?php

namespace App\Domain\User\Services;


use App\Domain\User\Models\User;
use App\Domain\Utils\Image\ImageUploader;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    private ImageUploader $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function register(string $email, string $password, ?string $name = null): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        event(new Registered($user));

        return $user;
    }

    public function updateProfile(Authenticatable $user, array $attributes): User
    {
        info(__DIR__ . ' ' . json_encode($attributes));
        if (isset($attributes['email'])) {
            $user->email = $attributes['email'];
        }

        if (isset($attributes['name'])) {
            $user->name = $attributes['name'];
        }

        if (isset($attributes['avatar'])) {
            /** @var UploadedFile $file */
            $file = $attributes['avatar'];
            $name = 'profile/avatar/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            $this->imageUploader->uploadImage($name, $file);

            $user->avatar = $name;
        }

        $user->save();

        return $user;
    }

    public function getOperatorByExtension(string $extension): User|null
    {
        return User::where('phone_ext', '=', $extension)->first();
    }
}
