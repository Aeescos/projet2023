import { AuthService } from '../service/AuthService.js';
import { Personnes } from '../entity/peronnes.js';
// Utilisation dans le composant ConnexionComponent

export class login {
  
    constructor() {
        this.authService = new AuthService();
        this.personnesLogin = new Personnes();
    }

    setMessage(){
        if (this.authService.IsLogged()) {
          this.message = 'Vous êtes connecté';
          this.loginSuccess = true;
          this.invalidLogin = false;
        } else {
          this.invalidLogin = true;
          this.loginSuccess = false;
          this.message = 'Identifiant ou mot de passe incorrecte'
        }
    }

    handleLogin(username, password) {
        this.personnesLogin = {
          username: username,
          password: password,
        };
    
        if (this.personnesLogin.username === '' || this.personnesLogin.password === '') {
          this.password = '';
          this.setMessage();
          return;
        }
    
        this.authService.login(this.personnesLogin)
          .then(result => { // Utilisez .then au lieu de .done
            if (result && result.token && result.refresh_token) {
              localStorage.setItem('token', result.token);
              localStorage.setItem('refreshtoken', result.refresh_token);
              this.setMessage();
              this.password = '';
              setTimeout(() => {
                console.log(result.token);
                window.location.href = '/';
              }, 2000);
            } else {
                setTimeout(() => {
                    console.log(result);
                  }, 2000);
              localStorage.clear();
              this.password = '';
              this.setMessage();
            }
          })
          .catch(error => { // Utilisez .catch pour gérer les erreurs
            console.error('Error:', error);
            localStorage.clear();
            this.password = '';
            this.setMessage();
          });
    }


}
$(document).ready(function(){
    const loginInstance = new login(); // Créez une instance de la classe login
    styleLogin();
    // Attach a submit handler to the form
    $("#loginForm").on("submit", function(event) {
      // Stop form from submitting normally
      event.preventDefault();
  
      // Get some values from elements on the page:
      const $form = $(this);
      const username = $form.find("input[name='username']").val();
      const password = $form.find("input[name='password']").val();
  
      loginInstance.handleLogin(username, password); // Utilisez l'instance de login pour appeler handleLogin
    });

    function styleLogin() {
        //reacte btn submite
        $(".limiter  .container-login100-form-btn .login100-form-btn").hover(function(){
            $(this).css({
              "background": "linear-gradient(to left, #50b245, #614b55, #42a4fa)",
              "transform": "scale(1)",
              "transition": "all 0.4s"
            });
          }, function(){
            $(this).css({
              "background": "linear-gradient(to left, #a445b2, #d41872, #fa4299)",
              "transform": "scale(0.9)",
          });
        });

        //reacte input 

      $(".input_1").mouseenter(function(){
        $(".wrap-input_1").css({
          "border-bottom": "1px solid #d41872",
          "transition": "all 0.4s"
        });
        $(this).css({
          "background": "linear-gradient(to left, #ccc, #cca, #acc)",
          "transform": "scale(1)",
        });
      });

      $(".input_1").mouseleave(function(){
        if ($(this).val().length > 0) {
          $(".wrap-input_1").css({
            "border-bottom": "1px solid #d41872",
            "transition": "all 0.4s"
          });
          $(this).css({
            "background": "linear-gradient(to left, #ccc, #cca, #acc)",
            "transform": "scale(1)",
          });
        }else{
          $(".wrap-input_1").css({
            "border-bottom": "1px solid #e6e6e6",
            "transition": "all 0.4s"
          });
          $(this).css({
            "background": "none",
            "transform": "scale(0.9)",
          });
        }
      });


      $(".input_2").mouseenter(function(){
        $(".wrap-input_2").css({
          "border-bottom": "1px solid #d41872",
          "transition": "all 0.4s"
        });
        $(this).css({
          "background": "linear-gradient(to left, #ccc, #cca, #acc)",
          "transform": "scale(1)",
        });
      });

      $(".input_2").mouseleave(function(){
        if ($(this).val().length > 0) {
          $(".wrap-input_1").css({
            "border-bottom": "1px solid #d41872",
            "transition": "all 0.4s"
          });
          $(this).css({
            "background": "linear-gradient(to left, #ccc, #cca, #acc)",
            "transform": "scale(1)",
          });
        }else{
          $(".wrap-input_2").css({
            "border-bottom": "1px solid #e6e6e6",
            "transition": "all 0.4s"
          });
          $(this).css({
            "background": "none",
            "transform": "scale(0.9)",
          });
        }
      });



    }
    
});


