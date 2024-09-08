<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any Role');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Activity $activity): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view Role');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create Role');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Activity $activity): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update Role');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Activity $activity): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete Role');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Activity $activity): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Activity $activity): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
