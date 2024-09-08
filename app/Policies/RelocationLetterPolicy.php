<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\RelocationLetter;
use App\Models\User;

class RelocationLetterPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any RelocationLetter');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, RelocationLetter $relocationletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view RelocationLetter');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create RelocationLetter');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, RelocationLetter $relocationletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update RelocationLetter');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, RelocationLetter $relocationletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete RelocationLetter');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, RelocationLetter $relocationletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, RelocationLetter $relocationletter): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
