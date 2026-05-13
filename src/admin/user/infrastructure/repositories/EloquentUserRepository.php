<?php

namespace Src\admin\user\infrastructure\repositories;

use App\Models\UserModel;
use Src\admin\user\domain\valueObjects\UserPassword;
use Src\shared\domain\entities\User;
use Src\shared\domain\exceptions\UserNotFoundException;
use Src\shared\domain\repositories\UserRepositoryInterface;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $userModel = UserModel::find('id', $id);

        if (! $userModel) {
            return null;
        }

        return $this->toUser($userModel);
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $userModel = UserModel::query()->where('email', $email->value())->first();

        if (! $userModel) {
            return null;
        }

        return $this->toUser($userModel);
    }

    public function save(User $user): void
    {
        if ($user->id() === null) {
            $model = UserModel::create([
                'name' => $user->username()->value(),
                'email' => $user->email()->value(),
                'password' => $user->passwordHash(),
            ]);

            $reflection = new \ReflectionClass($user);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setValue($user, $model->id);
        } else {
            UserModel::where('id', $user->id())->update([
                'name' => $user->username()->value(),
                'email' => $user->email()->value(),
                'password' => $user->passwordHash(),
            ]);
        }
    }

    public function delete(int $userId): void
    {
        $deleted = UserModel::query()->where('id', $userId)->delete();

        if ($deleted === 0) {
            throw new UserNotFoundException($userId);
        }
    }

    private function toUser(UserModel $model): User
    {
        return new User(
            id: $model->id,
            username: new UserName($model->name),
            email: new UserEmail($model->email),
            password: UserPassword::fromHash($model->password)
        );
    }
}
