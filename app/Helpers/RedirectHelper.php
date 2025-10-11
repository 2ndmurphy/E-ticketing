<?php

if (!function_exists('redirectRoute')) {
  function redirectRoute($role)
  {
    return match ($role) {
      'admin' => route('admin.dashboard'),
      'maskapai' => route('maskapai.dashboard'),
      'user' => route('user.dashboard'),
      default => abort(403)
    };
  }
}