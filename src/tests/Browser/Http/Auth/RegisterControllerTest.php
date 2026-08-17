<?php

namespace Tests\Browser\Http\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterControllerTest extends DuskTestCase
{
    use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    public function test_example(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPathIs('/');
        });
    }

    public function test_display_validation_error_messages_with_alpine(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('username', 'トマト 太郎')
                ->type('loginId', '@@')
                ->type('email', '**@tete')
                ->type('password', '||||')
                ->type('passwordConfirmation', '||')
                ->click('@submit-button')
                ->assertPathIs('/register')

                ->waitFor('@username-error-message')

                ->assertSeeIn('@username-error-message', 'お名前は、2文字以下にしてください。')
                ->assertSeeIn('@loginId-error-message', 'ログインIDは、4文字以上にしてください。')
                ->assertSeeIn('@loginId-error-message', 'ログインIDには、有効な正規表現を指定してください。');
        });
    }
}
