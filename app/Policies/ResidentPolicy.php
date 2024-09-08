<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Resident;
use App\Models\User;

class ResidentPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any Resident');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Resident $resident): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view Resident');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create Resident');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Resident $resident): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update Resident');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Resident $resident): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete Resident');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Resident $resident): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Resident $resident): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
