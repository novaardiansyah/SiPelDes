<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ResidentLetter;
use App\Models\User;

class ResidentLetterPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any ResidentLetter');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, ResidentLetter $residentletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view ResidentLetter');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create ResidentLetter');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, ResidentLetter $residentletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update ResidentLetter');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, ResidentLetter $residentletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete ResidentLetter');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, ResidentLetter $residentletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, ResidentLetter $residentletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
