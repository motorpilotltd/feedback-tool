<?php

beforeEach(function () {
    setupData();
});

it('has /user/viewprofile/ page available when there\'s a current user logged in.', function () {
    login();
    $response = $this->get(route('user.viewprofile', ['user' => $this->userBasic->id]));
    $response->assertStatus(200);
});

it('redirects to login when visiting /user/viewprofile page without logging in', function () {
    $response = $this->get(route('user.viewprofile', ['user' => $this->userBasic->id]));
    $response->assertStatus(302)
        ->assertRedirectToRoute('login');
});

it('shows the user details in the /user/viewprofile/ page with hidden email if not admin/not profile owner', function () {
    login();
    $response = $this->get(route('user.viewprofile', ['user' => $this->userBasic->id]));
    $response->assertSee($this->userBasic->name);
    $response->assertDontSee($this->userBasic->email);
    $response->assertSee(hideEmailAddress($this->userBasic->email));
});

it('shows the user email in the /user/viewprofile/ page when', function ($user) {
    login($user);
    $response = $this->get(route('user.viewprofile', ['user' => $this->userBasic->id]));
    $response->assertSee($this->userBasic->name);
    $response->assertSee($this->userBasic->email);
})->with([
    'super admin logged in' => fn () => $this->userSuperAdmin,
    'profile owner logged in' => fn () => $this->userBasic,
]);
