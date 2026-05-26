<?php

namespace Src\admin\user\infrastructure\repositories;

use App\Models\AdminModel;
use Src\admin\user\domain\entities\Admin;
use Src\admin\user\domain\exceptions\AdminNotFoundException;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\admin\user\domain\valueObjects\AdminRole;

class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function findById(int $id): ?Admin
    {
        $adminModel = AdminModel::with('user')->where('id', $id)->first();

        if (! $adminModel) {
            return null;

        }

        return $this->toAdmin($adminModel);
    }

    public function findByUserId(int $userId): ?Admin
    {
        $adminModel = AdminModel::with('user')->where('user_id', $userId)->first();

        if (! $adminModel) {
            return null;

        }

        return $this->toAdmin($adminModel);
    }

    public function findAll(): array
    {
        $adminModels = AdminModel::with('user')->get();

        return $adminModels->map(fn ($model) => $this->toAdmin($model))->toArray();
    }

    public function save(Admin $admin): void
    {
        logger()->debug('EloquentAdminRepository::save() -> Trying to create admin');

        if ($admin->id() === null) {
            AdminModel::create([
                'user_id' => $admin->userId(),
                'role' => $admin->role()->value(),
                'is_active' => $admin->isActive(),
                'created_by' => $admin->createdBy(),
            ]);

        } else {
            AdminModel::where('id', $admin->id())->update([
                'user_id' => $admin->userId(),
                'role' => $admin->role()->value(),
                'is_active' => $admin->isActive(),
            ]
            );
        }
    }

    public function delete(Admin $admin): void
    {
        $id = $admin->id();
        $deleted = AdminModel::query()->where('id', $id)->delete();

        if ($deleted === 0) {
            throw new AdminNotFoundException($id);
        }
    }

    public function exists(int $id): bool
    {
        return AdminModel::query()->where('id', $id)->exists();
    }

    private function toAdmin(AdminModel $adminModel): Admin
    {
        return new Admin(
            id: $adminModel->id,
            userId: $adminModel->user_id,
            role: AdminRole::from($adminModel->role),
            isActive: $adminModel->is_active,
            createdBy: $adminModel->created_by,
            createdAt: $adminModel->created_at,
            updatedAt: $adminModel->updated_at
        );
    }
}
