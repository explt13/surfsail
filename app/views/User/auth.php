<main class="page page_auth">
    <div class="container">
        <section class="auth">
            <div class="auth__blur"></div>
            <div class="auth__container">
                <div class="auth__body">
                    <div class="auth__content">
                        <div class="auth__header"></div>
                        <div class="auth__title-header">
                            <div class="auth__title">Welcome</div>
                            <div class="auth__subtitle">Welcome to Surfsail. Online Surf Shop</div>
                        </div>
                        <button class="auth__oauth">Continue With Google</button>
                        <div class="auth__or">Or</div>
                        <form class="auth__form form-auth form" action="/user/register" method="get">
                            <div class="form__field _req">
                                <input placeholder="Email" data-name="Email" type="text" name="auth[email]" class="form__email" />
                            </div>
                            <div class="form__field _req">
                                <input data-minlen="8" data-name="Password" placeholder="Password" type="password" name="auth[password]" class="form__password" />
                            </div>
                            <div class="form__field _req">
                                <input data-minlen="8" data-name="Password" placeholder="Confirm password" type="password" name="auth[password-confirm]" class="form__password-confirm" />
                            </div>
                            <div class="form__field">
                                <div class="form__remember">
                                    <input id="remember-me" name="auth[remember]" type="checkbox" class="checkbox-csm"></input>
                                    <label for="remember-me" class="checkbox-csm__label"><span class="checkbox-csm__text">Remember me</span></label>
                                </div>
                            </div>
                            <button type="submit" class="form-auth__submit form__submit"><span class="_fade_anim">Register</span></button>
                        </form>
                        <div class="auth__change">
                            <span class="auth__change-message">Already have an account?</span>
                            <span class="auth__change-method">Log in</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>