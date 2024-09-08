<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LetterTemplate;
use App\Models\User;

class LetterTemplatePolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view-any LetterTemplate');
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, LetterTemplate $lettertemplate): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('view LetterTemplate');
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('create LetterTemplate');
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, LetterTemplate $lettertemplate): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('update LetterTemplate');
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, LetterTemplate $lettertemplate): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('delete LetterTemplate');
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, LetterTemplate $lettertemplate): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ restorePermission }}');
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, LetterTemplate $lettertemplate): bool
  {
    return $user->checkPermissionTo('*') || $user->checkPermissionTo('{{ forceDeletePermission }}');
  }
}
