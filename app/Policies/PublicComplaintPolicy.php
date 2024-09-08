<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PublicComplaint;
use App\Models\User;

class PublicComplaintPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any PublicComplaint');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, PublicComplaint $publiccomplaint): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view PublicComplaint');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create PublicComplaint');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, PublicComplaint $publiccomplaint): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update PublicComplaint');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, PublicComplaint $publiccomplaint): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete PublicComplaint');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, PublicComplaint $publiccomplaint): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, PublicComplaint $publiccomplaint): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
