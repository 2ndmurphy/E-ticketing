<?php

if (!function_exists('redirectRoute')) {
  function redirectRoute($role)
  {
    return match ($role) {
      'admin' => route('admin.dashboard'),
      'maskapai' => route('maskapai.flights.index'),
      'user' => route('user.flights.index'),
      default => abort(403)
    };
  }
}