<div class="page page_auth">
    <div class="container">
        <section class="auth">
            <div class="auth__blur"></div>
            <div class="auth__container">
                <div class="auth__body">
                    <div class="auth__content">
                        <div class="auth__title-header">
                            <div class="auth__title">Welcome</div>
                            <div class="auth__subtitle">to Surfsail. Online Surf Shop</div>
                        </div>
                        <button class="auth__oauth">Continue With Google</button>
                        <div class="auth__or">Or</div>
                        <form class="auth__form form form-validate" action="#" method="get">
                            <div class="form__field form__field_auth _req">
                                <input placeholder="Email" data-name="Email" type="text" name="auth[email]" class="form__email" />
                            </div>
                            <div class="form__field form__field_auth _req">
                                <input data-viewable placeholder="Password" data-minlen="8" id="p1" data-name="Password" type="password" name="auth[password]" class="form__password" />
                            </div>
                            <div class="form__field form__field_auth _req">
                                <input data-viewable placeholder="Confirm password" data-minlen="8" data-for="p1" data-name="Password" type="password" pswd_confirm name="auth[password-confirm]" class="form__password-confirm" />
                            </div>
                            <div class="form__field form__field_auth">
                                <div class="form__remember checkbox checkbox_remember-me">
                                    <input id="remember-me" name="auth[remember]" type="checkbox" class="checkbox__input"></input>
                                    <label for="remember-me" tabindex="0" class="checkbox__label checkbox__label_remember-me">
                                        <div class="checkbox__box checkbox_box_remember-me"></div>
                                        <div class="checkbox__text checkbox__text_remember-me">Remember me</div>
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="form__submit form__submit_auth"><span class="_fade_anim" data-changeable data-name="submitFormButton"></span></button>
                        </form>
                        <div class="auth__change">
                            <span class="_fade_anim auth__change-message" data-changeable data-name="switchFormText"></span>
                            <a role="link" class="_fade_anim auth__change-form" tabindex="0" data-changeable data-name="switchFormButton"></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>