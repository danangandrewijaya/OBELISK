<?php

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Dashboard > Import
Breadcrumbs::for('import.form', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Import Data', route('import.form'));
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > Perbandingan CPL
Breadcrumbs::for('siklus.compare-cpl', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Perbandingan CPL', route('siklus.compare-cpl'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > User Management > Roles
Breadcrumbs::for('user-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Roles', route('user-management.roles.index'));
});

// Home > Dashboard > User Management > Roles > [Role]
Breadcrumbs::for('user-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('user-management.roles.index');
    $trail->push(ucwords($role->name), route('user-management.roles.show', $role));
});

// Home > Dashboard > User Management > Permission
Breadcrumbs::for('user-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Permissions', route('user-management.permissions.index'));
});

// Siklus
Breadcrumbs::for('siklus.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Siklus', route('siklus.index'));
});

Breadcrumbs::for('siklus.create', function (BreadcrumbTrail $trail) {
    $trail->parent('siklus.index');
    $trail->push('Tambah Siklus', route('siklus.create'));
});

Breadcrumbs::for('siklus.edit', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus.index');
    $trail->push('Edit Siklus', route('siklus.edit', $siklus));
});

Breadcrumbs::for('siklus.show', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus.index');
    $trail->push($siklus->nama, route('siklus.show', $siklus));
});

Breadcrumbs::for('siklus.configure', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus.show', $siklus);
    $trail->push('Konfigurasi', route('siklus.configure', $siklus));
});


// Siklus2
Breadcrumbs::for('siklus2.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Siklus', route('siklus2.index'));
});

Breadcrumbs::for('siklus2.create', function (BreadcrumbTrail $trail) {
    $trail->parent('siklus2.index');
    $trail->push('Tambah Siklus', route('siklus2.create'));
});

Breadcrumbs::for('siklus2.edit', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus2.index');
    $trail->push('Edit Siklus', route('siklus2.edit', $siklus));
});

Breadcrumbs::for('siklus2.show', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus2.index');
    $trail->push($siklus->nama, route('siklus2.show', $siklus));
});

Breadcrumbs::for('siklus2.configure', function (BreadcrumbTrail $trail, $siklus) {
    $trail->parent('siklus2.show', $siklus);
    $trail->push('Konfigurasi', route('siklus2.configure', $siklus));
});
