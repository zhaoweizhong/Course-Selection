<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id'                => $user->id,
            'sid'               => $user->sid,
            'name'              => $user->name,
            'email'             => $user->email,
            'mobile'            => $user->mobile,
            'avatar_url'        => $user->avatar_url,
            'type'              => $user->type,
            'is_admin'          => $user->is_admin,
            'created_at'        => $user->created_at->toDateTimeString(),
            'updated_at'        => $user->updated_at->toDateTimeString(),
        ];
    }
}
