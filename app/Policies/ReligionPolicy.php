<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Religion;
use App\Models\User;

class ReligionPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any Religion');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Religion $religion): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view Religion');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create Religion');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Religion $religion): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update Religion');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Religion $religion): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete Religion');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Religion $religion): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Religion $religion): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
