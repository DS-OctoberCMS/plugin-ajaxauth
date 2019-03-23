(function ($)
{
    const mainAuth = mainAuth || {

        /*
         * Properties
         *===================*/

        sendAuthActions: {
            signIn:  'ajaxAuth::onSignin',
            signUp:  'ajaxAuth::onRegister',
            restore: 'resetPassword::onRestorePassword',
            reset:   'resetPassword::onResetPassword',
        },

        defaultEmptyInputs: [
            '#authInputEmailForgot',
            '#authInputCode',
            '#authInputNewPass',
        ],

        /*
         * Actions
         *==========*/

        loadStart: function()
        {
            $.oc.flashMsg.DEFAULTS.interval = 15;               // set default flash message interval
            this.sel('button[name="signIn"]').focus();  // set default form focus
        },

        events: function()
        {
            /*
             * auth inputs value center and left position (@fix bug - for default browser values)
             */
            $('body.auth-body').click(function(){
                mainAuth.sel('input.fixForDefaultValBrowser').removeClass('fixForDefaultValBrowser');
            });
            $.each(this.defaultEmptyInputs, function(key, val){
                let $this = $(val);
                if (! $this.val())
                    $this.val('');
            });

            /*
             * toggle auth - forgot blocks
             */
            let $formAuth         = this.sel('.form-auth');
            let $formForgot       = this.sel('.form-forgot');
            let $formReset        = this.sel('.form-reset');
            let $authInputAllPass = this.sel('input[type="password"]');
            let $alertBlocks      = this.sel('.alert');
            let fadeDuration      = 500;

            let funToggleAction = function() {
                $authInputAllPass.val('');
                $alertBlocks.removeClass('visible');
            };
            this.sel('.forgot-link').click(function(){
                $formReset.fadeOut();
                $formAuth.fadeOut(fadeDuration, function(){
                    funToggleAction();
                    $formForgot.fadeIn(fadeDuration);
                });
            });
            this.sel('button[name="forgotBack"]').click(function(){
                $formReset.fadeOut();
                $formForgot.fadeOut(fadeDuration, function(){
                    funToggleAction();
                    $formAuth.fadeIn(fadeDuration);
                });
            });
            this.sel('button[name="resetBack"]').click(function(){
                $formForgot.fadeOut();
                $formReset.fadeOut(fadeDuration, function(){
                    funToggleAction();
                    $formAuth.fadeIn(fadeDuration);
                });
            });

            /*
             * auth ajax logic
             */
            $.each(this.sendAuthActions, function(key, val){
                mainAuth.sel('button[name="'+key+'"]').click(function(){
                    let $this = $(this);
                    let $form = $this.closest('form');

                    /* redirect action */
                    switch (key)
                    {
                        case 'signIn': $form.attr('data-request-redirect', '/');  break;
                        case 'signUp': $form.removeAttr('data-request-redirect'); break;
                    }

                    /* attachment loading */
                    mainAuth.sel('button[data-attach-loading]').removeAttr('data-attach-loading');
                    $this.attr('data-attach-loading','');

                    $form.data('request', val);
                    $form.submit();
                });
            });
        },

        /*
         * Helpers
         *===================*/

        sel: function(selector){
            return $('.block-signin '+selector);
        },

        /*
         * START
         *=======*/

        init: function()
        {
            this.loadStart();
            this.events();
        }
    };

    $(document).ready(function() {
        mainAuth.init();
    });
})(jQuery);