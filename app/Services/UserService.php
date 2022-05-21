<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function __construct(protected User $model){}

    public function create($attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        $user = $this->model->create($attributes);
        return $user;
    }

    public function updateImage($attributes)
    {
        $user = User::find(auth()->user()->id);
       
        if ($user->avatar) {
            File::delete(public_path().$user->avatar);

            $user->avatar = null;
            $user->update();
        }
        $file  = 'avatar.' . $attributes['avatar']->getClientOriginalExtension(); 
		$path = 'storage/user/' . $user->id. '/';

		if (!File::exists($path)) File::makeDirectory(public_path() . '/' . $path, 0777, true);

		$attributes['avatar']->move($path, $file); 
		$user->avatar = '/' . $path . $file; 
		$user->update();

        return '/' . $path . $file;
    }

    public function getUsers($attributes)
    {
        $perPage = data_get($attributes, 'per_page', 10);

        return User::where('role_id', User::AUTHOR)
            ->paginate($perPage);
    }
}